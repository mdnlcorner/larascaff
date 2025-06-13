@props(['id' => 'id'.rand(), 
'label' => null, 
'columnSpan' => '1', 
'name' => null, 
'variant' => 'primary', 
'size' => 'md', 
'error' => null, 
'disabled' => null,
'attr' => ''
])

<div @class(["w-full form-wrapper flex flex-col justify-center", $columnSpan != '1' ? 'md:col-span-'.$columnSpan : ''])>
    <div class="flex items-center ">
        <input {{ $attr }} data-input-name="{{ $name }}" name="{{ $name }}" 
        type="checkbox" {{ $disabled ? 'disabled' : null }} id="{{ $id }}" {{ $attributes->twMerge([
            'border rounded-sm focus:ring-offset-2 appearance-none focus:ring-2 border-border checked:text-white dark:focus:ring-offset-dark-900 focus:ring-offset-white',
            $disabled ? 'cursor-not-allowed' : null,
            // variant
            $variant == 'primary' ? 'focus:ring-primary checked:bg-primary focus:border-primary' : null,
            $variant == 'success' ? 'focus:ring-success checked:bg-success focus:border-success' : null,
            $variant == 'danger' ? 'focus:ring-danger checked:bg-danger focus:border-danger' : null,
            $variant == 'warning' ? 'focus:ring-warning checked:bg-warning focus:border-warning' : null,
            $variant == 'secondary' ? 'focus:ring-secondary checked:bg-secondary focus:border-secondary' : null,
            $variant == 'info' ? 'focus:ring-info checked:bg-info fous:border-info' : null,
            $variant == 'dark' ? 'focus:ring-dark dark:bg-dark dark:border-dark' : null,
            // size
            $size == 'sm' ? 'w-3.5 h-3.5' : null,
            $size == 'md' ? 'w-4 h-4' : null,
            $size == 'lg' ? 'w-6 h-6' : null,
        ]) }} />
        <label for="{{ $id }}" class="inline-block text-sm ms-2 {{ $disabled ? 'text-muted cursor-not-allowed' : null }} {{ $error ? 'text-danger' : null }}">{{ $label }}</label>
    </div>
    @if ($error)
        <div class="mt-1 text-xs text-danger [&.invalid-feedback]:text-xs [&.invalid-feedback]:mt-1 [&.invalid-feedback]:text-danger">{{ $error }}</div>
    @endif
</div>