@props(['active' => false])

@php
$classes = ($active ?? false)
            ? 'flex items-center px-3 py-2 rounded-md text-sm font-semibold bg-navy-700 text-white'
            : 'flex items-center px-3 py-2 rounded-md text-sm font-medium text-gray-300 hover:bg-navy-800 hover:text-white transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
