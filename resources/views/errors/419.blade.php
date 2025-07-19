@extends('layouts.app')

@section('title', __('Page Expired'))
@section('content')
<div class="flex flex-col items-center justify-center min-h-screen text-center">
    <h1 class="text-6xl font-bold text-gray-800">419</h1>
    <h2 class="mt-4 text-2xl font-semibold text-gray-700">Page Expired</h2>
    <p class="mt-2 text-gray-600">Sorry, your session has expired. Please refresh and try again.</p>
    <a href="{{ url()->previous() }}" class="mt-6 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-600 border border-transparent rounded-lg active:bg-blue-600 hover:bg-blue-700 focus:outline-none focus:shadow-outline-blue">
        Go Back
    </a>
</div>
@endsection
