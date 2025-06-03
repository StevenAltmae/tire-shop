@php
use Lunar\Facades\CartSession;
$cart = CartSession::current();
@endphp

<div x-data="{ open: false }" class="relative">
    <button @click="open = !open" class="text-white hover:text-gray-200 focus:outline-none transition duration-150 ease-in-out">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
        </svg>
        @if($cart && $cart->lines->count() > 0)
            <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                {{ $cart->lines->count() }}
            </span>
        @endif
    </button>

    <div x-show="open" 
         @click.away="open = false"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform scale-95"
         x-transition:enter-end="opacity-100 transform scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-95"
         class="absolute right-0 mt-2 w-96 bg-white rounded-lg shadow-lg overflow-hidden z-50">
        
        <div class="p-4">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Shopping Cart</h3>
            
            @if($cart && $cart->lines->count() > 0)
                <div class="space-y-4">
                    @foreach($cart->lines as $line)
                        <div class="flex items-center space-x-4">
                            @if($line->purchasable && $line->purchasable->product && $line->purchasable->product->media->count() > 0)
                                <img src="{{ $line->purchasable->product->media->first()->getUrl() }}" 
                                     alt="{{ $line->purchasable->product->translateAttribute('name') }}" 
                                     class="w-16 h-16 object-contain">
                            @endif
                            <div class="flex-1">
                                <h4 class="text-sm font-medium text-gray-900">
                                    {{ $line->purchasable->product->translateAttribute('name') }}
                                </h4>
                                <p class="text-sm text-gray-500">Qty: {{ $line->quantity }}</p>
                                <p class="text-sm font-medium text-gray-900">
                                    {{ number_format($line->subTotal->value / (10 ** ($line->subTotal->currency->decimal_places ?? 2)), $line->subTotal->currency->decimal_places ?? 2) }} {{ $line->subTotal->currency->code ?? '' }}
                                </p>
                            </div>
                            <form action="{{ route('cart.remove', $line->id) }}" method="POST" class="flex-shrink-0">
                                @csrf
                                <button type="submit" class="text-red-600 hover:text-red-800">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    @endforeach

                    <div class="border-t pt-4">
                        <div class="flex justify-between text-base font-medium text-gray-900">
                            <p>Subtotal</p>
                            <p>{{ number_format($cart->subTotal->value / (10 ** ($cart->subTotal->currency->decimal_places ?? 2)), $cart->subTotal->currency->decimal_places ?? 2) }} {{ $cart->subTotal->currency->code ?? '' }}</p>
                        </div>
                        <div class="mt-6">
                            <a href="{{ route('cart.show') }}" class="flex items-center justify-center px-6 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-gray-900 hover:bg-gray-800 transition duration-150 ease-in-out">
                                View Cart
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-6">
                    <p class="text-gray-500">Your cart is empty</p>
                    <a href="{{ route('home') }}" class="mt-4 inline-block text-gray-900 hover:text-gray-700 transition duration-150 ease-in-out">
                        Continue Shopping
                    </a>
                </div>
            @endif
        </div>
    </div>
</div> 