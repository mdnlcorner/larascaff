@props(['id' => 'id'.rand(), 'name', 'value' => null, 'label' => null, 'options' => [], 'variant' => 'primary', 'disabled' => false, 'error' => null, 'columnSpan' => '1'])

<div @class(["w-full form-wrapper", $columnSpan != '1' ? 'md:col-span-'.$columnSpan : '', $label ? 'mb-3' : ''])>
    <label class="inline-block mb-3 text-xs">{{ $label }}</label>
    <div class="flex items-center gap-x-3">
        @foreach ($options as $key => $val)
            <div class="flex items-center gap-x-2 ">
                <input name="{{ $name }}" {{ $disabled ? 'disabled' : null }} @checked($val == (is_null($value) ? (getRecord($name) ?? '') : $value)) value="{{ $val }}" type="radio" id="{{ $key }}" {{ $attributes->twMerge([
                    'w-4 h-4 border cursor-pointer border-border [&.is-invalid]:border-danger rounded-full appearance-none dark:bg-dark-800 bg-dark-100 focus:ring-2 checked:border-none  focus:ring-offset-2 focus:ring-offset-white dark:focus:ring-offset-dark-800',
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
                <label for="{{ $key }}" class="inline-block text-sm cursor-pointer {{ $error ? 'text-danger' : null }} {{ $disabled ? 'cursor-not-allowed' : null }}">{{ $key }}</label>
            </div>
        @endforeach
    </div>
</div>