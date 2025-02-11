@props(['id' => 'id'.rand(), 'label' => null, 'class' => null, 'variant' => 'primary', 'size' => 'md', 'error' => null])

<div class="flex gap-3 mb-3 item-ce">
    <label class="inline-flex items-center cursor-pointer">
        <input {{ $attributes->withoutTwMergeClasses()->twMerge([
            'sr-only peer',
        ]) }} type="checkbox" />
        <div {{ $attributes->twMergeFor('switch', [
            "relative bg-gray-200 rounded-full dark:bg-gray-700 peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:transition-all dark:border-gray-600 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-offset-2 dark:peer-focus:ring-offset-dark-800",
            $variant == 'primary' ? 'peer-focus:ring-primary dark:peer-focus:ring-primary peer-checked:bg-primary-600' : null,
            $variant == 'success' ? 'peer-focus:ring-success dark:peer-focus:ring-success peer-checked:bg-success-600' : null,
            $variant == 'secondary' ? 'peer-focus:ring-secondary dark:peer-focus:ring-secondary peer-checked:bg-secondary-600' : null,
            $variant == 'danger' ? 'peer-focus:ring-danger dark:peer-focus:ring-danger peer-checked:bg-danger-600' : null,
            $variant == 'warning' ? 'peer-focus:ring-warning dark:peer-focus:ring-warning peer-checked:bg-warning-600' : null,
            $variant == 'dark' ? 'peer-focus:ring-dark dark:peer-focus:ring-dark peer-checked:bg-dark-600' : null,
            $variant == 'info' ? 'peer-focus:ring-info dark:peer-focus:ring-info peer-checked:bg-info-600' : null,
            // size
            $size == 'sm' ? 'w-9 h-5 after:h-4 after:w-4 after:start-[2px]' : null,
            $size == 'md' ? 'w-14 h-7 after:w-6 after:h-6 after:start-[4px]' : null,
            $size == 'lg' ? 'w-9 h-5 after:h-4 after:w-4 after:start-[2px]' : null,
        ]) }} 
         ></div>
        <span class="text-sm font-medium ms-3 {{ $error ? 'text-danger' : null }}">{{ $label }}</span>
    </label>
</div>