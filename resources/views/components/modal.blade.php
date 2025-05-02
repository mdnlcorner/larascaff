<div data-modal-wrapper tabindex="-1" aria-hidden="true" {{ $attributes->twMerge(["hidden opacity-0 dark:bg-dark-800/70 min-h-full bg-dark-900/20 transition-all duration-500 overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-[60] justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full"]) }} >
    {{ $slot }}
</div>