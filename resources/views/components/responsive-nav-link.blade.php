@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-primary-500 text-start text-base font-medium text-primary-700 bg-primary-50 focus:outline-none transition duration-300 ease-in-out animate-fade-in'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-secondary-600 hover:text-primary-700 hover:bg-gray-50 hover:border-primary-300 focus:outline-none transition duration-300 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
