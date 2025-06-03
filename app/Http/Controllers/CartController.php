<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Lunar\Facades\CartSession;
use Lunar\Models\Cart;
use Lunar\Models\Product;
use Lunar\Models\Country;

class CartController extends Controller
{
    public function show()
    {
        $cart = CartSession::current();
        
        if (!$cart) {
            return view('cart.show', [
                'cart' => null
            ]);
        }

        // Load the cart with its relationships
        $cart = Cart::with([
            'lines.purchasable.product.media',
            'lines.purchasable.product.productType',
            'lines.purchasable.prices'
        ])->find($cart->id);
        
        return view('cart.show', [
            'cart' => $cart
        ]);
    }

    public function add(Request $request, $productId)
    {
        $cart = CartSession::current();
        if (!$cart) {
            // Get default currency and channel
            $currency = \Lunar\Models\Currency::where('default', true)->first();
            $channel = \Lunar\Models\Channel::where('default', true)->first();

            if (!$currency || !$channel) {
                return redirect()->back()->with('error', 'Default currency or channel not found.');
            }

            try {
                $cart = CartSession::create([
                    'currency_id' => $currency->id,
                    'channel_id' => $channel->id,
                ]);
            } catch (\Exception $e) {
                \Log::error('Failed to create cart: ' . $e->getMessage());
                return redirect()->back()->with('error', 'Failed to create cart.');
            }
        }

        $product = Product::find($productId);
        if (!$product) {
            return redirect()->back()->with('error', 'Product not found.');
        }

        $variant = $product->variants->first();
        if (!$variant) {
            return redirect()->back()->with('error', 'No purchasable variant found for this product.');
        }

        // Log variant details for debugging
        \Log::info('Variant details:', [
            'id' => $variant->id,
            'class' => get_class($variant),
            'prices' => $variant->prices->toArray()
        ]);

        // If the cart does not have a shipping address, set a default one with country_id
        if (!$cart->shippingAddress) {
            $country = Country::first();
            if (!$country) {
                return redirect()->back()->with('error', 'No countries found in the database.');
            }
            
            try {
                $addressData = [
                    'first_name' => 'Guest',
                    'last_name' => 'User',
                    'line_one' => 'Default Address',
                    'city' => 'Default City',
                    'postcode' => '00000',
                    'country_id' => $country->id,
                ];
                
                $cart->addAddress($addressData, 'shipping');
            } catch (\Exception $e) {
                \Log::error('Failed to add shipping address: ' . $e->getMessage());
                return redirect()->back()->with('error', 'Failed to set shipping address.');
            }
        }

        try {
            // Log cart details before adding item
            \Log::info('Cart details before adding item:', [
                'cart_id' => $cart->id,
                'currency_id' => $cart->currency_id,
                'channel_id' => $cart->channel_id
            ]);

            $lineData = [
                'quantity' => $request->quantity ?? 1,
                'purchasable' => $variant,
            ];

            \Log::info('Attempting to add line with data:', [
                'quantity' => $lineData['quantity'],
                'purchasable_id' => $variant->id,
                'purchasable_type' => get_class($variant)
            ]);

            $cart->addLines([$lineData]);

            // Log cart details after adding item
            \Log::info('Cart details after adding item:', [
                'cart_id' => $cart->id,
                'lines_count' => $cart->lines->count()
            ]);

        } catch (\Exception $e) {
            \Log::error('Failed to add item to cart: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString(),
                'variant_id' => $variant->id,
                'variant_type' => get_class($variant)
            ]);
            return redirect()->back()->with('error', 'Failed to add item to cart: ' . $e->getMessage());
        }

        return redirect()->route('cart.show')->with('success', 'Item added to cart.');
    }

    public function remove($lineId)
    {
        $cart = CartSession::current();
        
        $cart->remove($lineId);

        return redirect()->back()->with('success', 'Product removed from cart');
    }

    public function update(Request $request)
    {
        $cart = CartSession::current();
        
        foreach ($request->input('lines', []) as $lineId => $quantity) {
            $cart->updateLine($lineId, $quantity);
        }

        return redirect()->back()->with('success', 'Cart updated');
    }
} 