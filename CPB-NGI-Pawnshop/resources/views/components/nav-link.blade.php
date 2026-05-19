@props(['active'])

@php
$classes = ($active ?? false)
            ? 'flex items-center w-full px-4 py-3 text-sm font-medium leading-5 border-l-4 border-indigo-500 dark:border-indigo-400 bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300 focus:outline-none transition duration-150 ease-in-out'
            : 'flex items-center w-full px-4 py-3 text-sm font-medium leading-5 border-l-4 border-transparent text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800 hover:border-gray-300 dark:hover:border-gray-700 focus:outline-none focus:text-gray-800 dark:focus:text-gray-200 focus:bg-gray-50 dark:focus:bg-gray-800 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
