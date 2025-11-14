@props([
    'label' => null,
    'id' => 'id' . rand(),
    'size' => 'md',
    'error' => null,
    'icon' => false,
    'config' => [],
    'name',
    'value' => null,
    'columnSpan' => '1',
    'attr' => '',
])
<div 
    x-ignore 
    x-load 
    x-load-src="{{ asset('larascaff/components/datepicker.js?v=' . \Composer\InstalledVersions::getVersion('mulaidarinull/larascaff')) }}"
    x-load-css="['{{ asset('larascaff/components/datepicker.css?v=' . \Composer\InstalledVersions::getVersion('mulaidarinull/larascaff')) }}']" 
    x-data="initDatepicker({
        ...@js($config)
    })"
    @class(["w-full form-wrapper", $columnSpan != '1' ? 'md:col-span-'.$columnSpan : ''])
>
    @if ($label)
        <label for="{{ $id }}" class="inline-block mb-1 {{ $size == 'sm' ? 'text-xs' : 'text-sm' }}">{{ $label }}</label>
    @endif
    <div class="relative">
        @if ($icon)
            <div type="button"
                class="absolute pointer-events-none inset-y-0 flex items-center px-3 start-0 {{ $error ? 'text-danger' : 'text-muted-foreground' }}">
                @svg('tabler-calendar', 'w-5 h-5')
            </div>
        @endif
        <input x-ref="input" {{ $attr }} data-input-name="{{ $name }}" name="{{ $name }}" type="input" readonly id="{{ $id }}" value="{{ $value ?? getRecord($name) }}"
            {{ $attributes->twMerge([
                'disabled:cursor-not-allowed [&.is-invalid]:border-danger [&.is-invalid]:focus-visible:ring-danger/60 border-border w-full bg-transparent border rounded-md focus-visible:ring-2 focus-visible:outline-none focus-visible:ring-primary focus-visible:ring-offset-white placeholder:text-muted-foreground dark:focus-visible:ring-offset-dark-900 focus-visible:ring-offset-2',
                $icon ? 'ps-10' : null,
                // size
                $size == 'sm' ? 'py-1.5 px-2.5 text-xs' : null,
                $size == 'md' ? 'px-3 py-2 text-sm' : null,
                $size == 'lg' ? 'px-3.5 py-3 text-default' : null,
            
                $error ? 'border-danger focus-visible:ring-danger/60' : null,
            ]) }} />
    </div>
    @if ($error)
        <div class="mt-1 text-xs text-danger">{{ $error }}</div>
    @endif
</div>
