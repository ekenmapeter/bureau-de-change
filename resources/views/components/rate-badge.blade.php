@props(['type' => 'buy'])

@php
$map = [
    'buy'  => 'bg-indigo-100 text-indigo-800',
    'sell' => 'bg-green-100 text-green-800',
    'deposit' => 'bg-yellow-100 text-yellow-800',
];
@endphp

<span class="px-2 py-1 rounded-full text-xs font-semibold {{ $map[$type] ?? 'bg-gray-100 text-gray-800' }}">
    {{ ucfirst($type) }}
</span>
