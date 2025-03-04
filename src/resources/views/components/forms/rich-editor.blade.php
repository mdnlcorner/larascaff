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

@if ($disabled)
    @if ($label)
        <label for="id_{{ $name }}" class="inline-block mb-1 text-sm">{{ $label }}</label>
    @endif
    <div 
    @class([
        'w-full form-wrapper',
        $columnSpan != '1' ? 'md:col-span-' . $columnSpan : '',
    ])>
        <div class="relative w-full prose min-h-40 max-w-none px-3 py-1.5 dark:prose-invert focus-visible:outline-none  sm:text-sm sm:leading-6 border rounded-md">
            {!! $value !!}
        </div>
    </div>
@else
<div 
    x-ignore 
    x-load 
    x-load-src="{{ asset('larascaff/components/richeditor.js' . \Composer\InstalledVersions::getVersion('mulaidarinull/larascaff')) }}"
    x-data="initRichEditor({
        url: @js(url()->temporarySignedRoute('uploader', now()->addMinutes(60))),
        path: @js($imagePath),
        maxSize: @js($imageMaxSize),
    })" 
    @if (!in_array('upload-image', $toolbar))
        x-on:trix-file-accept="$event.preventDefault()"
    @endif
    @class([
        'w-full form-wrapper',
        $columnSpan != '1' ? 'md:col-span-' . $columnSpan : '',
    ])
>
    @if ($label)
        <label for="id_{{ $name }}" class="inline-block mb-1 text-sm">{{ $label }}</label>
    @endif
    <input type="hidden" class="peer" value="{{ $value }}" id="rich_editor_value_{{ $name }}" name="{{ $name }}" />
    <div class="relative has-[:focus-visible]:ring-2 has-[:focus-visible]:outline-none has-[:focus-visible]:ring-primary has-[:focus-visible]:ring-offset-white dark:has-[:focus-visible]:ring-offset-dark-900 has-[:focus-visible]:ring-offset-2 w-full border group rounded-md peer-[.is-invalid]:border-danger peer-[.is-invalid]:has-[:focus-visible]:ring-danger/60">
        <trix-toolbar class="py-2 overflow-x-auto border-b" id="my_toolbar_{{ $name }}">
            <div class="flex items-center gap-3 px-2">
                <div class="flex items-center gap-0.5">
                    @if(in_array('bold', $toolbar))
                    <button type="button" class="p-1.5 hover:bg-primary/30 hover:text-primary rounded-md [&.trix-active]:text-primary [&.trix-active]:bg-primary/30 " data-trix-attribute="bold" data-trix-key="b">@svg('tabler-bold', 'w-5 h-5')</button>
                    @endif
                    @if(in_array('italic', $toolbar))
                    <button type="button" class="p-1.5 hover:bg-primary/30 hover:text-primary rounded-md [&.trix-active]:text-primary [&.trix-active]:bg-primary/30 " data-trix-attribute="italic" data-trix-key="i">@svg('tabler-italic', 'w-5 h-5')</button>
                    @endif
                    @if(in_array('underline', $toolbar))
                    <button type="button" class="p-1.5 hover:bg-primary/30 hover:text-primary rounded-md [&.trix-active]:text-primary [&.trix-active]:bg-primary/30 " data-trix-attribute="underline" data-trix-key="u">@svg('tabler-underline', 'w-5 h-5')</button>
                    @endif
                    @if(in_array('strike', $toolbar))
                    <button type="button" class="p-1.5 hover:bg-primary/30 hover:text-primary rounded-md [&.trix-active]:text-primary [&.trix-active]:bg-primary/30 " data-trix-attribute="strike" data-trix-key="s">@svg('tabler-strikethrough', 'w-5 h-5')</button>
                    @endif
                    @if(in_array('link', $toolbar))
                    <button type="button" class="p-1.5 hover:bg-primary/30 hover:text-primary rounded-md [&.trix-active]:text-primary [&.trix-active]:bg-primary/30 " data-trix-attribute="href" data-trix-action="link" data-trix-key="l">@svg('tabler-link', 'w-5 h-5')</button>
                    @endif
                </div>
                <div class="flex items-center gap-0.5">
                    @if (in_array('h1', $toolbar))
                    <button type="button" class="p-1.5 hover:bg-primary/30 hover:text-primary rounded-md [&.trix-active]:text-primary [&.trix-active]:bg-primary/30 " data-trix-attribute="h1">@svg('tabler-h-1', 'w-5 h-5')</button>
                    @endif
                    @if (in_array('h2', $toolbar))
                    <button type="button" class="p-1.5 hover:bg-primary/30 hover:text-primary rounded-md [&.trix-active]:text-primary [&.trix-active]:bg-primary/30 " data-trix-attribute="h2">@svg('tabler-h-2', 'w-5 h-5')</button>
                    @endif
                    @if (in_array('h3', $toolbar))
                    <button type="button" class="p-1.5 hover:bg-primary/30 hover:text-primary rounded-md [&.trix-active]:text-primary [&.trix-active]:bg-primary/30 " data-trix-attribute="h3">@svg('tabler-h-3', 'w-5 h-5')</button>
                    @endif
                    @if (in_array('quote', $toolbar))
                    <button type="button" class="p-1.5 hover:bg-primary/30 hover:text-primary rounded-md [&.trix-active]:text-primary [&.trix-active]:bg-primary/30 " data-trix-attribute="quote">@svg('tabler-quote', 'w-5 h-5')</button>
                    @endif
                    @if (in_array('bullet', $toolbar))
                    <button type="button" class="p-1.5 hover:bg-primary/30 hover:text-primary rounded-md [&.trix-active]:text-primary [&.trix-active]:bg-primary/30 " data-trix-attribute="bullet">@svg('tabler-list', 'w-5 h-5')</button>
                    @endif
                    @if (in_array('number', $toolbar))
                    <button type="button" class="p-1.5 hover:bg-primary/30 hover:text-primary rounded-md [&.trix-active]:text-primary [&.trix-active]:bg-primary/30 " data-trix-attribute="number">@svg('tabler-list-numbers', 'w-5 h-5')</button>
                    @endif
                </div>
                <div class="flex items-center gap-0.5">
                    @if (in_array('code', $toolbar))
                    <button type="button" class="p-1.5 hover:bg-primary/30 hover:text-primary rounded-md [&.trix-active]:text-primary [&.trix-active]:bg-primary/30 " data-trix-attribute="code">@svg('tabler-code', 'w-5 h-5')</button>
                    @endif
                    @if (in_array('upload-image', $toolbar))
                    <button type="button" class="p-1.5 hover:bg-primary/30 hover:text-primary rounded-md [&.trix-active]:text-primary [&.trix-active]:bg-primary/30 " data-trix-action="attachFiles">@svg('tabler-photo', 'w-5 h-5')</button>
                    @endif
                </div>
                <div class="flex items-center gap-0.5">
                    @if (in_array('undo', $toolbar))
                    <button type="button" class="p-1.5 hover:bg-primary/30 hover:text-primary rounded-md [&.trix-active]:text-primary [&.trix-active]:bg-primary/30 " data-trix-action="undo">@svg('tabler-arrow-back-up', 'w-5 h-5')</button>
                    @endif
                    @if (in_array('redo', $toolbar))
                    <button type="button" class="p-1.5 hover:bg-primary/30 hover:text-primary rounded-md [&.trix-active]:text-primary [&.trix-active]:bg-primary/30 " data-trix-action="redo">@svg('tabler-arrow-forward-up', 'w-5 h-5')</button>
                    @endif
                </div>
            </div>
            <div data-trix-dialogs class="absolute w-full px-2 trix-dialogs top-14">
                <div
                    data-trix-dialog="href"
                    data-trix-dialog-attribute="href"
                    class="p-2 rounded-md bg-dark-50 dark:bg-dark-800"
                >
                    <div>
                        <input
                            aria-label="label"
                            data-trix-input
                            disabled
                            name="href"
                            placeholder="Enter a URL"
                            required
                            type="text"
                            inputmode="url"
                            class="w-full px-3 py-2 text-sm bg-transparent border rounded-md trix-input trix-input--dialog read-only:bg-dark-100 border-border focus-visible:ring-2 focus-visible:outline-none focus-visible:ring-primary focus-visible:ring-offset-white placeholder:text-muted-foreground dark:focus-visible:ring-offset-dark-800 focus-visible:ring-offset-2"
                        />

                        <div class="mt-3 text-xs text-white">
                            <input
                                data-trix-method="setAttribute"
                                type="button"
                                value="Link"
                                class="px-2 py-1 rounded-sm cursor-pointer trix-button trix-button--dialog bg-primary"
                            />
                            <input
                                data-trix-method="removeAttribute"
                                type="button"
                                value="unlink"
                                class="px-2 py-1 rounded-sm cursor-pointer trix-button trix-button--dialog bg-danger"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </trix-toolbar>

        <trix-editor toolbar="my_toolbar_{{ $name }}" id="id_{{ $name }}" input="rich_editor_value_{{ $name }}"
            class="prose min-h-40 max-w-none !border-none px-3 py-1.5 dark:prose-invert focus-visible:outline-none  sm:text-sm sm:leading-6"></trix-editor>
    </div>
</div>
@endif
