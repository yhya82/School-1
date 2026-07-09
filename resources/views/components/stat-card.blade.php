@props(['label', 'value', 'href' => null])

@php $inner = 'text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider'; @endphp

@if ($href)
    <a href="{{ $href }}" wire:navigate class="block bg-white dark:bg-gray-800 overflow-hidden shadow-sm hover:shadow-md transition-shadow rounded-lg p-5 border-l-4 border-navy-600">
        <p class="{{ $inner }}">{{ $label }}</p>
        <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $value }}</p>
    </a>
@else
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-5 border-l-4 border-navy-600">
        <p class="{{ $inner }}">{{ $label }}</p>
        <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $value }}</p>
    </div>
@endif
