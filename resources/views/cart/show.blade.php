@extends('layouts.app')

@section('title', 'Shopping Cart - Tire Shop')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-3xl font-bold mb-8">Shopping Cart</h1>

        @if($cart && $cart->lines->count() > 0)
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="divide-y divide-gray-200">
                    @foreach($cart->lines as $line)
                        <div class="p-6 flex items-center">
                            <div class="flex-shrink-0 w-24 h-24">
                                @if($line->purchasable->product->media->count() > 0)
                                    <img src="{{ $line->purchasable->product->media->first()->getUrl() }}" 
                                         alt="{{ $line->purchasable->product->translateAttribute('name') }}"
                                         class="w-full h-full object-contain">
                                @else
                                    <div class="w-full h-full bg-gray-100 flex items-center justify-center">
                                        <span class="text-gray-400">No image</span>
                                    </div>
                                @endif
                            </div>

                            <div class="ml-6 flex-1">
                                <h3 class="text-lg font-medium text-gray-900">
                                    {{ $line->purchasable->product->translateAttribute('name') }}
                                </h3>

                                <div class="mt-2 flex items-center justify-between">
                                    <div class="flex items-center">
                                        <form action="{{ route('cart.update') }}" method="POST" class="flex items-center">
                                            @csrf
                                            <label for="quantity-{{ $line->id }}" class="sr-only">Quantity</label>
                                            <input type="number" 
                                                   name="lines[{{ $line->id }}]" 
                                                   id="quantity-{{ $line->id }}"
                                                   value="{{ $line->quantity }}"
                                                   min="1"
                                                   class="w-20 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                            <button type="submit" class="ml-2 text-sm text-blue-600 hover:text-blue-800">
                                                Update
                                            </button>
                                        </form>
                                    </div>

                                    <div class="flex items-center">
                                        <p class="text-lg font-medium text-gray-900">
                                            @php
                                                $price = $line->purchasable->prices->first();
                                                $subtotal = $price ? ($price->price->value * $line->quantity) : 0;
                                                $currency = $price ? $price->currency : null;
                                            @endphp
                                            {{ number_format($subtotal / (10 ** ($currency->decimal_places ?? 2)), $currency->decimal_places ?? 2) }} {{ $currency->code ?? '' }}
                                        </p>
                                        <form action="{{ route('cart.remove', $line->id) }}" method="POST" class="ml-4">
                                            @csrf
                                            <button type="submit" class="text-red-600 hover:text-red-800">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="border-t border-gray-200 px-6 py-4">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Total</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                @if($cart->total)
                                    {{ number_format($cart->total->value / (10 ** ($cart->total->currency->decimal_places ?? 2)), $cart->total->currency->decimal_places ?? 2) }} {{ $cart->total->currency->code }}
                                @else
                                    0.00
                                @endif
                            </p>
                        </div>
                        <a href="{{ route('checkout.show') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Proceed to Checkout
                        </a>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Your cart is empty</h3>
                <p class="mt-1 text-sm text-gray-500">Start shopping to add items to your cart.</p>
                <div class="mt-6">
                    <a href="{{ route('home') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Continue Shopping
                    </a>
                </div>
            </div>
        @endif
    </div>
@endsection 