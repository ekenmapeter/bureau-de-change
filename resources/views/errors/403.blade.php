@extends('layouts.app')

@section('title', 'Unauthorized')

@section('content')
<div class="flex flex-col items-center justify-center min-h-[50vh] text-center">
    <h1 class="text-6xl font-bold text-indigo-600">403</h1>
    <h2 class="mt-4 text-2xl font-semibold text-gray-800">This action is unauthorized.</h2>
    <p class="mt-2 text-gray-600">You do not have the necessary permissions to view this page.</p>

    <div class="mt-8">
        <p class="text-gray-500">
            You will be automatically logged out in <span id="countdown" class="font-bold">5</span> seconds.
        </p>
    </div>
</div>

<!-- Hidden logout form -->
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

@push('scripts')
<script>
    (function() {
        let seconds = 5;
        const countdownElement = document.getElementById('countdown');
        const logoutForm = document.getElementById('logout-form');

        const interval = setInterval(() => {
            seconds--;
            countdownElement.textContent = seconds;

            if (seconds <= 0) {
                clearInterval(interval);
                logoutForm.submit();
            }
        }, 1000);
    })();
</script>
@endpush
@endsection
