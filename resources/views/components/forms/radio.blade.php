@props(['id' => 'id'.rand(), 'label' => null, 'variant' => 'primary', 'disabled' => false, 'error' => null])

<div class="flex items-center gap-1 form-wrapper">
    <input {{ $disabled ? 'disabled' : null }} type="radio" id="{{ $id }}" {{ $attributes->twMerge([
        'w-4 h-4 border cursor-pointer border-border rounded-full appearance-none dark:bg-dark-800 bg-dark-100 focus:ring-2 checked:border-none  focus:ring-offset-2 focus:ring-offset-white dark:focus:ring-offset-dark-800',
        $disabled ? 'cursor-not-allowed' : null,
        // variant
        $variant == 'primary' ? 'checked:bg-primary dark:checked:bg-primary  focus:ring-primary focus:ring-offset-white' : null,
        $variant == 'success' ? 'checked:bg-success dark:checked:bg-success  focus:ring-success focus:ring-offset-white' : null,
        $variant == 'secondary' ? 'checked:bg-secondary dark:checked:bg-secondary  focus:ring-secondary focus:ring-offset-white' : null,
        $variant == 'warning' ? 'checked:bg-warning dark:checked:bg-warning  focus:ring-warning focus:ring-offset-white' : null,
        $variant == 'danger' ? 'checked:bg-danger dark:checked:bg-danger  focus:ring-danger focus:ring-offset-white' : null,
        $variant == 'info' ? 'checked:bg-info dark:checked:bg-info  focus:ring-info focus:ring-offset-white' : null,
        $variant == 'dark' ? 'checked:bg-dark dark:checked:bg-dark  focus:ring-dark focus:ring-offset-white' : null,
    ]) }} />
    <label for="{{ $id }}" class="inline-block text-sm cursor-pointer {{ $error ? 'text-danger' : null }} {{ $disabled ? 'cursor-not-allowed' : null }}">{{ $label }}</label>
</div>