@extends('layouts.app')

@php
use Lunar\Facades\CartSession;
@endphp

@section('title', $product->translateAttribute('name') . ' - Tire Shop')

@push('styles')
<style>
    .product-image {
        transition: transform 0.3s ease;
    }
    .product-image:hover {
        transform: scale(1.05);
    }
    .cart-badge {
        position: absolute;
        top: -8px;
        right: -8px;
        background-color: #ef4444;
        color: white;
        border-radius: 50%;
        padding: 2px 6px;
        font-size: 12px;
    }
</style>
@endpush

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <div class="flex flex-col md:flex-row gap-8">
        {{-- Product Images --}}
        <div class="md:w-1/2" x-data="{ mainImage: '{{ $product->media->first() ? $product->media->first()->getUrl() : '' }}' }">
            <div class="mb-4 flex items-center justify-center bg-gray-100 rounded-lg overflow-hidden" style="height: 400px;">
                @if($product->media->count() > 0)
                    <img :src="mainImage" alt="{{ $product->translateAttribute('name') }}" class="max-h-full max-w-full object-contain product-image">
                @else
                    <div class="p-8 text-center text-gray-500">
                        No images available
                    </div>
                @endif
            </div>
            @if($product->media->count() > 1)
                <div class="grid grid-cols-4 gap-2">
                    @foreach($product->media as $media)
                        <div class="aspect-w-1 aspect-h-1">
                            <img src="{{ $media->getUrl() }}" alt="{{ $product->translateAttribute('name') }}" 
                                class="w-full h-full object-contain cursor-pointer rounded-lg hover:ring-2 hover:ring-blue-500 transition-all" 
                                @click="mainImage = '{{ $media->getUrl() }}'">
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Product Details --}}
        <div class="md:w-1/2 flex flex-col">
            <h1 class="text-3xl font-bold text-gray-900 mb-6">{{ $product->translateAttribute('name') }}</h1>

            @php
                $firstVariant = $product->variants->first();
                $firstPrice = $firstVariant ? $firstVariant->prices->first() : null;
            @endphp

            {{-- Display price --}}
            @if($firstPrice)
                <p class="text-3xl font-bold mb-4 text-gray-900">
                    {{ number_format($firstPrice->price->value / (10 ** ($firstPrice->currency->decimal_places ?? 2)), $firstPrice->currency->decimal_places ?? 2) }} {{ $firstPrice->currency->code ?? '' }}
                </p>
            @endif

            {{-- Add to Cart Form --}}
            <form action="{{ route('cart.add', $product->id) }}" method="POST" class="mb-6">
                @csrf
                <div class="mb-4">
                    <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">Kogus</label>
                    <input type="number" name="quantity" id="quantity" value="1" min="1" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <button type="submit" class="w-full bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors duration-200 flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    Lisa ostukorvi
                </button>
            </form>

            {{-- Display description --}}
            @if($product->translateAttribute('description'))
                <div class="mb-6 bg-white rounded-lg p-6 shadow-sm">
                    <h3 class="text-xl font-bold mb-3 text-gray-900">Description</h3>
                    <p class="text-gray-600">{{ $product->translateAttribute('description') }}</p>
                </div>
            @endif

            {{-- Display all attributes --}}
            <div class="mb-6 bg-white rounded-lg p-6 shadow-sm">
                <h3 class="text-xl font-bold mb-3 text-gray-900">Omadused</h3>
                <ul class="space-y-2">
                    @foreach($product->productType->mappedAttributes as $attribute)
                        @php
                            $value = $product->translateAttribute($attribute->handle);
                        @endphp
                        @if($value)
                            <li class="flex justify-between py-2 border-b border-gray-100">
                                <span class="font-medium text-gray-700">{{ $attribute->translate('name') }}:</span>
                                <span class="text-gray-600">{{ $value }}</span>
                            </li>
                        @endif
                    @endforeach
                    @if($product->tags->count() > 0)
                        <li class="flex justify-between py-2 border-b border-gray-100">
                            <span class="font-medium text-gray-700">Omadused:</span>
                            <span class="text-gray-600">{{ $product->tags->pluck('value')->join(', ') }}</span>
                        </li>
                    @endif
                </ul>
            </div>

            {{-- Display performance labels if they exist --}}
            @php
                $fuelEfficiency = $product->translateAttribute('fuel_efficiency');
                $wetGrip = $product->translateAttribute('wet_grip');
                $noiseLevel = $product->translateAttribute('noise_level');
            @endphp

            @if($fuelEfficiency || $wetGrip || $noiseLevel)
                <div class="mb-6 bg-white rounded-lg p-6 shadow-sm">
                    <h3 class="text-xl font-bold mb-3 text-gray-900">Performance Labels</h3>
                    <ul class="space-y-2">
                        @if($fuelEfficiency)
                            <li class="flex justify-between py-2 border-b border-gray-100">
                                <span class="font-medium text-gray-700">Fuel Efficiency:</span>
                                <span class="text-gray-600">{{ $fuelEfficiency }}</span>
                            </li>
                        @endif
                        @if($wetGrip)
                            <li class="flex justify-between py-2 border-b border-gray-100">
                                <span class="font-medium text-gray-700">Wet Grip:</span>
                                <span class="text-gray-600">{{ $wetGrip }}</span>
                            </li>
                        @endif
                        @if($noiseLevel)
                            <li class="flex justify-between py-2 border-b border-gray-100">
                                <span class="font-medium text-gray-700">Noise Level:</span>
                                <span class="text-gray-600">{{ $noiseLevel }}</span>
                            </li>
                        @endif
                    </ul>
                </div>
            @endif

            <a href="/" class="text-blue-600 hover:text-blue-800 transition-colors duration-200 inline-flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Tagasi sirvima
            </a>
        </div>
    </div>
</div>
@endsection 