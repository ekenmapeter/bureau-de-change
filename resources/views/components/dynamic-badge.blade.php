@props(['type'])

@php
$class = '';
if ($type === 'purchase') {
    $class = 'bg-green-100 text-green-800';
} elseif ($type === 'sale') {
    $class = 'bg-red-100 text-red-800';
} else {
    $class = 'bg-gray-100 text-gray-800';
}
@endphp

<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $class }}">
    {{ ucfirst($type) }}
</span>