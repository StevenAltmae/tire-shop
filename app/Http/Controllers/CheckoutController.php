<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Lunar\Facades\CartSession;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class CheckoutController extends Controller
{
    public function show()
    {
        $cart = CartSession::current();
        
        if (!$cart || $cart->lines->isEmpty()) {
            return redirect()->route('cart.show')->with('error', 'Your cart is empty.');
        }

        return view('checkout.show', [
            'cart' => $cart
        ]);
    }

    public function createCheckoutSession()
    {
        try {
            \Log::info('Starting checkout session creation');
            
            $cart = CartSession::current();
            \Log::info('Current cart:', ['cart' => $cart ? $cart->toArray() : null]);
            
            if (!$cart || $cart->lines->isEmpty()) {
                \Log::warning('Cart is empty or not found');
                return response()->json(['error' => 'Cart is empty'], 400);
            }

            // Get or create shipping address
            $shippingAddress = $cart->shippingAddress;
            if (!$shippingAddress) {
                // Create a default shipping address if none exists
                $shippingAddress = $cart->addAddress([
                    'type' => 'shipping',
                    'first_name' => 'Guest',
                    'last_name' => 'User',
                    'line_one' => '123 Main St',
                    'city' => 'Default City',
                    'postcode' => '12345',
                    'country_id' => 1, // Use your default country ID
                ]);
            }

            $stripeKey = config('services.stripe.secret');
            \Log::info('Stripe key configured:', ['key_exists' => !empty($stripeKey)]);
            
            Stripe::setApiKey($stripeKey);

            $lineItems = [];
            foreach ($cart->lines as $line) {
                $price = $line->purchasable->prices->first();
                if (!$price) {
                    \Log::warning('No price found for line item', ['line_id' => $line->id]);
                    continue;
                }

                $lineItems[] = [
                    'price_data' => [
                        'currency' => strtolower($price->currency->code),
                        'product_data' => [
                            'name' => $line->purchasable->product->translateAttribute('name'),
                        ],
                        'unit_amount' => $price->price->value,
                    ],
                    'quantity' => $line->quantity,
                ];
            }

            \Log::info('Line items prepared:', ['count' => count($lineItems)]);

            if (empty($lineItems)) {
                \Log::warning('No valid line items found in cart');
                return response()->json(['error' => 'No valid items in cart'], 400);
            }

            \Log::info('Creating Stripe checkout session');
            $checkout_session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => $lineItems,
                'mode' => 'payment',
                'success_url' => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('checkout.cancel'),
                'shipping_address_collection' => [
                    'allowed_countries' => ['US', 'CA', 'GB'], // Add your allowed countries
                ],
            ]);

            \Log::info('Checkout session created successfully', ['session_id' => $checkout_session->id]);
            return response()->json(['id' => $checkout_session->id]);
        } catch (\Exception $e) {
            \Log::error('Stripe checkout error: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Failed to create checkout session: ' . $e->getMessage()], 500);
        }
    }

    public function success(Request $request)
    {
        $cart = CartSession::current();
        if (!$cart) {
            return redirect()->route('cart.show')->with('error', 'Cart not found.');
        }

        // Here you would typically create an order from the cart
        // For now, we'll just clear the cart
        $cart->delete();

        return view('checkout.success');
    }

    public function cancel()
    {
        return redirect()->route('cart.show')->with('error', 'Checkout was cancelled.');
    }
} 