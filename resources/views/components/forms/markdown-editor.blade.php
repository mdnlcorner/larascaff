@props([
    'name', 
    'disabled', 
    'imagePath', 
    'label', 
    'value', 
    'columnSpan' => '1', 
    'toolbar',
    'imageMaxSize'
])

<div 
    x-ignore 
    x-load 
    x-load-src="{{ asset('larascaff/components/markdown-editor.js?' . \Composer\InstalledVersions::getVersion('mulaidarinull/larascaff')) }}" 
    x-load-css="['{{ asset('larascaff/components/markdown-editor.css?' . \Composer\InstalledVersions::getVersion('mulaidarinull/larascaff')) }}']"
    x-data="initMarkdown({
        toolbars: @js($toolbar),
        url: @js(url()->temporarySignedRoute('uploader', now()->addMinutes(60))),
        path: @js($imagePath),
        maxSize: @js($imageMaxSize),
    })"
    @class([
        'w-full form-wrapper',
        $columnSpan != '1' ? 'md:col-span-' . $columnSpan : '',
    ])
>
    <div>
        @if ($label)
            <label for="{{ $name }}" class="inline-block mb-1 text-sm">{{ $label }}</label>
        @endif
        <textarea class="hidden" data-input-name="{{ $name }}" name="{{ $name }}" x-ref="markdown">{{ getRecord($name) }}</textarea>
    </div>
</div>