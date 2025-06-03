<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use Lunar\Models\Product;

Route::get('/', function () {
    $products = \Lunar\Models\Product::where('status', 'published')->get();
    return view('welcome', compact('products'));
})->name('home');

Route::get('/debug-product/{product}', function (Product $product) {
    $product->load(['media', 'productType.mappedAttributes', 'variants.prices', 'tags']);
    dd([
        'product' => $product->toArray(),
        'media' => $product->media->map(function($media) {
            return [
                'id' => $media->id,
                'url' => $media->getUrl(),
                'path' => $media->path,
                'disk' => $media->disk,
            ];
        })->toArray(),
        'product_type' => $product->productType ? [
            'id' => $product->productType->id,
            'name' => $product->productType->name,
            'attributes' => $product->productType->mappedAttributes->map(function($attr) {
                return [
                    'id' => $attr->id,
                    'name' => $attr->name,
                    'handle' => $attr->handle,
                ];
            })->toArray(),
        ] : null,
         'variants' => $product->variants->map(function($variant) {
            return [
                'id' => $variant->id,
                'sku' => $variant->sku,
                'prices' => $variant->prices->map(function($price) {
                    return [
                        'id' => $price->id,
                        'price' => $price->price,
                        'formatted' => $price->formatted,
                        'currency' => $price->currency->code,
                    ];
                })->toArray(),
            ];
        })->toArray(),
        'tags' => $product->tags->map(function($tag) {
            return [
                'id' => $tag->id,
                'value' => $tag->value,
            ];
        })->toArray(),
    ]);
})->name('debug.product');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
Route::post('/products/{product}/add-to-cart', [ProductController::class, 'addToCart'])->name('products.add-to-cart');

// Cart Routes
Route::get('/cart', [App\Http\Controllers\CartController::class, 'show'])->name('cart.show');
Route::post('/cart/add/{productId}', [App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
Route::post('/cart/remove/{lineId}', [App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/update', [App\Http\Controllers\CartController::class, 'update'])->name('cart.update');

// Checkout Routes
Route::get('/checkout', [App\Http\Controllers\CheckoutController::class, 'show'])->name('checkout.show');
Route::post('/checkout/create-session', [App\Http\Controllers\CheckoutController::class, 'createCheckoutSession'])->name('checkout.create-session');
Route::get('/checkout/success', [App\Http\Controllers\CheckoutController::class, 'success'])->name('checkout.success');
Route::get('/checkout/cancel', [App\Http\Controllers\CheckoutController::class, 'cancel'])->name('checkout.cancel');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/contact', [App\Http\Controllers\ContactController::class, 'show'])->name('contact');
Route::post('/contact', [App\Http\Controllers\ContactController::class, 'send'])->name('contact.send');

require __DIR__.'/auth.php';
