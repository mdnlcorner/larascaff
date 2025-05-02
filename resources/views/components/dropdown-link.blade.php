@props(['href' => '#'])
<div class="px-1 dropdown-item">
    <a href="{{ $href }}"
        {{ $attributes->twMerge('block px-4 py-2 focus:bg-dark-100 rounded-sm dark:focus:bg-dark-800 hover:bg-dark-100 dark:hover:bg-dark-800 dark:hover:text-white') }}>
        {{ $slot }}
    </a>
</div>
