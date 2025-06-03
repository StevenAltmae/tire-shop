@extends('layouts.app')

@section('title', 'Tire Shop')

@push('styles')
<style>
    .product-card {
        transition: all 0.3s ease;
        background: white;
        border-radius: 1rem;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
    .product-image-container {
        position: relative;
        width: 100%;
        height: 250px;
        background: #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .product-image {
        max-width: 100%;
        max-height: 100%;
        width: auto;
        height: auto;
        object-fit: contain;
    }
    .tag {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        background: #e5e7eb;
        color: #374151;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 500;
        margin: 0.25rem;
    }
    .price {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1f2937;
    }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-4xl font-bold text-center mb-12 text-gray-900">REHVI MÜÜK</h1>
    
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
        @foreach($products as $product)
            <div class="product-card">
                <a href="{{ route('products.show', $product->id) }}" class="block">
                    <div class="product-image-container">
                        @if($product->thumbnail)
                            <img src="{{ $product->thumbnail->getUrl() }}" 
                                 alt="{{ $product->translateAttribute('name') }}" 
                                 class="product-image">
                        @else
                            <div class="text-gray-400">No Image</div>
                        @endif
                    </div>
                    <div class="p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-3">{{ $product->translateAttribute('name') }}</h2>
                        
                        @php
                            $firstVariant = $product->variants->first();
                            $firstPrice = $firstVariant ? $firstVariant->prices->first() : null;
                        @endphp

                        @if($firstPrice)
                            <p class="price mb-4">
                                {{ number_format($firstPrice->price->value / (10 ** ($firstPrice->currency->decimal_places ?? 2)), $firstPrice->currency->decimal_places ?? 2) }} {{ $firstPrice->currency->code ?? '' }}
                            </p>
                        @endif

                        @if($product->tags->count() > 0)
                            <div class="flex flex-wrap gap-2 mb-4">
                                @foreach($product->tags as $tag)
                                    <span class="tag">{{ $tag->value }}</span>
                                @endforeach
                            </div>
                        @endif

                        <div class="mt-4">
                            <span class="inline-flex items-center px-4 py-2 rounded-md text-sm font-medium bg-blue-600 text-white hover:bg-blue-700 transition-colors duration-200">
                                View Details
                                <svg class="ml-2 -mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </span>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</div>
@endsection
