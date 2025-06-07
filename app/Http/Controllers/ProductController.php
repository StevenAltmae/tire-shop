<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Lunar\Models\Product;
use Lunar\Facades\CartSession;
use Lunar\Models\TaxZone;
use Lunar\Models\TaxClass;
use Lunar\Models\TaxRate;
use Lunar\Models\TaxRateAmount;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::where('status', 'published')
            ->with(['media', 'variants.prices', 'productType.mappedAttributes', 'tags'])
            ->get();

        return view('products.index', [
            'products' => $products
        ]);
    }

    public function show(Product $product)
    {
        $product->load([
            'variants.prices',
            'productType.mappedAttributes',
            'media',
            'tags'
        ]);

        return view('products.show', [
            'product' => $product
        ]);
    }

    public function addToCart(Request $request, Product $product)
    {
        $variant = $product->variants->first();
        
        if (!$variant) {
            return back()->with('error', 'No variant available for this product.');
        }

        $quantity = $request->input('quantity', 1);

        // Get or create default tax zone
        $taxZone = TaxZone::where('default', true)->first();
        if (!$taxZone) {
            $taxZone = TaxZone::create([
                'name' => 'Default Tax Zone',
                'default' => true,
            ]);
        }

        // Get or create default tax class
        $taxClass = TaxClass::first();
        if (!$taxClass) {
            $taxClass = TaxClass::create([
                'name' => 'Default Tax Class',
            ]);
        }

        // Create tax rate if it doesn't exist
        $taxRate = TaxRate::first();
        if (!$taxRate) {
            $taxRate = TaxRate::create([
                'name' => 'Standard Rate',
                'tax_zone_id' => $taxZone->id,
            ]);

            TaxRateAmount::create([
                'tax_rate_id' => $taxRate->id,
                'tax_class_id' => $taxClass->id,
                'percentage' => 20.00,
            ]);
        }

        // Ensure variant has tax class
        if (!$variant->tax_class_id) {
            $variant->update(['tax_class_id' => $taxClass->id]);
        }

        CartSession::add($variant, $quantity);

        return back()->with('success', 'Product added to cart successfully.');
    }
} 