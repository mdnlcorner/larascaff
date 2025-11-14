@props([
    'error' => null,
    'size' => 'md',
    'name' => null,
    'label' => null,
    'multiple' => false,
    'id' => 'id' . rand(),
    'searchable' => false,
    'options' => [],
    'placeholder' => null,
    'value' => null,
    'model' => false,
    'config' => [],
    'depend' => false,
    'dependTo' => [],
    'dependValue' => null,
    'dependColumn' => null,
    'columnValue' => null,
    'columnLabel' => null,
    'columnSpan' => '1',
    'limit' => 20,
    'attr' => '',
    'module' => $module,
])
@if ($searchable)
    <div 
        x-ignore 
        x-load 
        x-load-src="{{ asset('larascaff/components/choices.js?v=' . \Composer\InstalledVersions::getVersion('mulaidarinull/larascaff')) }}"
        x-load-css="['{{ asset('larascaff/components/choices.css?v=' . \Composer\InstalledVersions::getVersion('mulaidarinull/larascaff')) }}']" x-data="initSelect({
            options: @js($options),
            value: @js($value),
            model: @js($model),
            depend: @js($depend),
            dependTo: @js($dependTo),
            dependValue: @js($dependValue),
            dependColumn: @js($dependColumn),
            placeholder: @js($placeholder),
            columnLabel: @js($columnLabel),
            columnValue: @js($columnValue),
            limit: @js($limit),
            module: @js($module),
        })"
        @class(["w-full form-wrapper", $columnSpan != '1' ? 'md:col-span-'.$columnSpan : ''])
    >
        <label for="{{ $id }}" class="inline-block mb-1 text-sm">{{ $label }}</label>
        <select {{ $attr }} data-input-name="{{ $name }}" id="{{ $id }}" x-ref="input" {{ $attributes->merge() }} data-placeholder="{{ $placeholder }}"
            name="{{ $multiple ? $name. '[]' : $name }}" {{ $multiple ? 'multiple' : '' }}></select>
    </div>
@else
    <div class="w-full form-wrapper">
        @if ($label)
            <label for="{{ $id }}"
                class="inline-block mb-1 {{ $size == 'sm' ? 'text-xs' : 'text-sm' }}">{{ $label }}</label>
        @endif
        <select {{ $attr }} id="{{ $id }}" data-input-name="{{ $name }}" name="{{ $multiple ? $name. '[]' : $name }}" {{ $multiple ? 'multiple' : '' }}
            {{ $attributes->twMerge([
                'relative w-full [&.is-invalid]:border-danger [&.is-invalid]:focus-visible:ring-danger/60 text-sm bg-transparent border rounded-md appearance-none focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary focus:ring-offset-white dark:focus:ring-offset-dark-800 border-border',
                // size
                $size == 'sm' ? 'py-1.5 px-2.5 text-xs' : null,
                $size == 'md' ? 'px-3 py-2 text-sm' : null,
                $size == 'lg' ? 'px-3.5 py-3 text-default' : null,
                $error ? 'border-danger focus-visible:ring-danger/60' : null,
            ]) }}>
            @if ($placeholder)
                <option value="">{{ $placeholder }}</option>
            @endif
            @foreach ($options as $key => $item)
                <option value="{{ $item }}" @selected((is_null($value) ? getRecord($name) : $value) == $item)>{{ $key }}</option>
            @endforeach
            {{ $slot }}
        </select>
        @if ($error)
            <div class="mt-1 text-xs text-danger">{{ $error }}</div>
        @endif
    </div>
@endif
