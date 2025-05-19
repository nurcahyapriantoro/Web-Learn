@props(['active'])

@php
$classes = ($active ?? false)
            ? 'nav-link active inline-flex items-center px-1 pt-1 text-sm font-medium leading-5 text-primary-600 focus:outline-none transition duration-300 ease-in-out'
            : 'nav-link inline-flex items-center px-1 pt-1 text-sm font-medium leading-5 text-secondary-600 hover:text-primary-600 focus:outline-none transition duration-300 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
