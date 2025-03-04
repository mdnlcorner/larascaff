@props([
    'label' => null,
    'id' => 'id' . rand(),
    'type' => 'input',
    'size' => null,
    'error' => null,
    'name1' => 'name1',
    'name2' => 'name2',
    'value1' => '',
    'value2' => '',
    'placeholder1' => '',
    'placeholder2' => '',
    'config' => [],
    'columnSpan' => '1'
])
<div 
    x-ignore 
    x-load 
    x-load-src="{{ asset('larascaff/components/datepicker-range.js?' . \Composer\InstalledVersions::getVersion('mulaidarinull/larascaff')) }}"
    x-load-css="['{{ asset('larascaff/components/datepicker-range.css?' . \Composer\InstalledVersions::getVersion('mulaidarinull/larascaff')) }}']" 
    x-data="initDatepickerRange({
        ...@js($config)
    })" 
    @class(['w-full', $columnSpan != '1' ? 'md:col-span-'.$columnSpan : ''])
>
    @if ($label)
        <label for="{{ $id }}"
            class="inline-block mb-1 {{ $size == 'sm' ? 'text-xs' : 'text-sm' }}">{{ $label }}</label>
    @endif
    <div x-ref="datepicker" class="flex">
        <div class="w-full form-wrapper">
            <input placeholder="{{ $placeholder1 }}" value="{{ $value1 }}" readonly name="{{ $name1 }}"
                type="{{ $type }}" id="name1{{ $id }}"
                {{ $attributes->twMergeFor('input1', [
                    'disabled:cursor-not-allowed px-3 py-2 [&.is-invalid]:border-danger [&.is-invalid]:focus-visible:ring-danger/60 px-3 py-2 text-sm w-full bg-transparent border rounded-l-md border-border focus-visible:ring-2 focus-visible:outline-none focus-visible:ring-primary focus-visible:ring-offset-white placeholder:text-muted-foreground dark:focus-visible:ring-offset-dark-900 focus-visible:ring-offset-2',
                    $size == 'sm' ? 'py-1.5 px-2.5 text-xs' : null,
                    $size == 'lg' ? 'px-3.5 py-3 text-default' : null,
                    $error ? 'border-danger focus-visible:ring-danger/60' : null,
                ]) }} />
        </div>
        <div
            class="min-w-8 text-muted-foreground flex items-center h-[2.39rem] py-1 border-t border-b px-1.5 overflow-hidden bg-dark-100 text-center dark:bg-dark-800 text-sm">
            @svg('tabler-calendar', 'w-5 h-5')
        </div>
        <div class="w-full form-wrapper">
            <input placeholder="{{ $placeholder2 }}" readonly value="{{ $value2 }}" name="{{ $name2 }}"
                type="{{ $type }}" id="name2{{ $id }}"
                {{ $attributes->twMergeFor('input2', [
                    'disabled:cursor-not-allowed px-3 py-2 [&.is-invalid]:border-danger [&.is-invalid]:focus-visible:ring-danger/60 px-3 py-2 text-sm w-full bg-transparent border rounded-r-md border-border focus-visible:ring-2 focus-visible:outline-none focus-visible:ring-primary focus-visible:ring-offset-white placeholder:text-muted-foreground dark:focus-visible:ring-offset-dark-900 focus-visible:ring-offset-2',
                    $size == 'sm' ? 'py-1.5 px-2.5 text-xs' : null,
                    $size == 'lg' ? 'px-3.5 py-3 text-default' : null,
                    $error ? 'border-danger focus-visible:ring-danger/60' : null,
                ]) }} />
        </div>
    </div>
    @if ($error)
        <div
            class="mt-1 text-xs text-danger [&.invalid-feedback]:text-xs [&.invalid-feedback]:mt-1 [&.invalid-feedback]:text-danger">
            {{ $error }}</div>
    @endif
</div>
