@extends('layouts.app')

@section('title', 'Products - Tire Shop')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold mb-8">Our Products</h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach($products as $product)
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <a href="{{ route('products.show', $product) }}" class="block">
                    @if($product->media->count() > 0)
                        <div class="aspect-w-1 aspect-h-1">
                            <img src="{{ $product->media->first()->getUrl() }}" 
                                alt="{{ $product->translateAttribute('name') }}" 
                                class="w-full h-48 object-contain">
                        </div>
                    @else
                        <div class="w-full h-48 bg-gray-100 flex items-center justify-center">
                            <span class="text-gray-400">No image available</span>
                        </div>
                    @endif

                    <div class="p-4">
                        <h2 class="text-lg font-semibold text-gray-900 mb-2">
                            {{ $product->translateAttribute('name') }}
                        </h2>

                        @php
                            $firstVariant = $product->variants->first();
                            $firstPrice = $firstVariant ? $firstVariant->prices->first() : null;
                        @endphp

                        @if($firstPrice)
                            <p class="text-xl font-bold text-gray-900">
                                {{ number_format($firstPrice->price->value / (10 ** ($firstPrice->currency->decimal_places ?? 2)), $firstPrice->currency->decimal_places ?? 2) }} {{ $firstPrice->currency->code ?? '' }}
                            </p>
                        @endif

                        <div class="mt-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                View Details
                            </span>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</div>
@endsection 