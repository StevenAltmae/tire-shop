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
        background: #ffffff;
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
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 bg-[#f8fafc]">
    <div class="mb-12">
        <form action="{{ route('home') }}" method="GET" class="space-y-4">
            <div class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Otsi toodet..." 
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div class="flex gap-4">
                    <select name="tag" class="rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Kõik tooted</option>
                        <option value="uued" {{ request('tag') === 'uued' ? 'selected' : '' }}>Uued</option>
                        <option value="kasutatud" {{ request('tag') === 'kasutatud' ? 'selected' : '' }}>Kasutatud</option>
                    </select>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Otsi
                    </button>
                </div>
            </div>
        </form>
    </div>
    
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
        @foreach($products as $product)
            <div class="bg-white rounded-lg shadow-sm overflow-hidden flex flex-col h-[480px]">
                <a href="{{ route('products.show', $product->id) }}" class="block h-full flex flex-col">
                    <div class="product-image-container h-64">
                        @if($product->thumbnail)
                            <img src="{{ $product->thumbnail->getUrl() }}" 
                                 alt="{{ $product->translateAttribute('name') }}" 
                                 class="product-image">
                        @else
                            <div class="text-gray-400">No Image</div>
                        @endif
                    </div>
                    <div class="p-6 flex-grow flex flex-col">
                        <div class="flex-grow">
                            <h2 class="text-xl font-bold text-gray-900 mb-3 min-h-[3rem]">{{ $product->translateAttribute('name') }}</h2>
                            
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
                        </div>
                        <div class="mt-auto">
                            <span class="inline-block w-full text-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                Vaata täpsemalt
                            </span>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</div>
@endsection
