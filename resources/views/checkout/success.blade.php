@extends('layouts.app')

@section('title', 'Payment Successful - Tire Shop')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="text-center">
            <svg class="mx-auto h-12 w-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <h2 class="mt-4 text-3xl font-bold text-gray-900">Payment Successful!</h2>
            <p class="mt-2 text-lg text-gray-600">Thank you for your purchase.</p>
            <div class="mt-6">
                <a href="{{ route("home") }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Continue Shopping
                </a>
            </div>
        </div>
    </div>
@endsection 