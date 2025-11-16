@props([
    'label' => null,
    'name',
    'accept' => 'image/png, image/jpeg, image/jpg, image/svg',
    'path' => 'storage',
    'config' => ['imageEditor' => true],
    'files' => [],
    'multiple' => false,
    'columnSpan' => '1',
    'disk' => 'public',
    'cropperOptions' => [],
])
<div 
    x-ignore 
    x-load 
    x-load-src="{{ asset('larascaff/components/file-upload.js?v=' . \Composer\InstalledVersions::getVersion('mulaidarinull/larascaff')) }}" 
    x-load-css="['{{ asset('larascaff/components/file-upload.css?v=' . \Composer\InstalledVersions::getVersion('mulaidarinull/larascaff')) }}']" 
    x-data="initUploader({
        tempUploadUrl: '{{ url()->temporarySignedRoute('temp-upload', now()->addMinutes(180)) }}',
        files: @js($files),
        path: @js($path),
        cropperOptions: @js($cropperOptions),
        ...@js($config),
        name: @js($name),
    })" 
    @class([
        'w-full form-wrapper',
        $columnSpan != '1' ? 'md:col-span-' . $columnSpan : '',
    ])
>
    @if ($label)
        <label for="" class="inline-block mb-1 text-sm">{{ $label }}</label>
    @endif
    <input name="{{ $multiple ? $name. '[]' : $name }}" {{ $multiple ? 'multiple' : '' }} x-ref="input" {{ $attributes->merge() }} accept="{{ $accept }}" type="file" />
</div>
{{-- cropper --}}
@if (isset($config['imageEditor']) && $config['imageEditor'])
<div class="fixed inset-0 z-[9999] hidden w-full h-screen p-2 sm:p-10 md:p-20 cropper-wrapper-coppier dark:bg-dark-900/30">
    <div class="flex items-center justify-center w-full h-full isolate dark:bg-dark-800">
        <div
            class="flex flex-col w-full h-full gap-4 mx-auto overflow-hidden bg-white rounded-xl ring-1 ring-gray-900/10 dark:bg-gray-800 dark:ring-gray-50/10 lg:flex-row">
            <div class="flex-1 w-full p-4 overflow-auto lg:h-full">
                <div class="w-full h-full">
                    <img src="" class="absolute hidden w-full editor"></img>
                </div>
            </div>
            <div
                class="shadow-top relative z-[1] flex h-96 p-4 w-full flex-col overflow-auto bg-gray-50 dark:bg-gray-900/30 lg:h-full lg:max-w-xs lg:shadow-none">
                <div class="grid grid-cols-1 gap-2">
                    <div class="grid grid-cols-5">
                        <button onclick="window.cropper.setDragMode('move')" type="button"
                            class="inline-flex items-center justify-center gap-2 px-3 py-2 text-sm text-white transition-colors border-0 rounded-none focus:outline-none disabled:cursor-not-allowed disabled:opacity-60 focus:bg-primary-600 dark:bg-primary bg-primary dark:hover:bg-primary-600 hover:bg-primary-600 dark:focus:text-primary-200 first:rounded-l-md last:rounded-r-md dark:first:rounded-l-md dark:last:rounded-r-md">
                            @svg('tabler-arrows-move')
                            <span class="sr-only">Move</span>
                        </button>
                        <button onclick="window.cropper.setDragMode('crop')" type="button"
                            class="inline-flex items-center justify-center gap-2 px-3 py-2 text-sm text-white transition-colors border-0 rounded-none focus:outline-none disabled:cursor-not-allowed disabled:opacity-60 focus:bg-primary-600 dark:bg-primary bg-primary dark:hover:bg-primary-600 hover:bg-primary-600 dark:focus:text-primary-200 first:rounded-l-md last:rounded-r-md dark:first:rounded-l-md dark:last:rounded-r-md">
                            @svg('tabler-crop')
                            <span class="sr-only">Crop</span>
                        </button>
                        <button onclick="window.cropper.zoomTo(1)" type="button"
                            class="inline-flex items-center justify-center gap-2 px-3 py-2 text-sm text-white transition-colors border-0 rounded-none focus:outline-none disabled:cursor-not-allowed disabled:opacity-60 focus:bg-primary-600 dark:bg-primary bg-primary dark:hover:bg-primary-600 hover:bg-primary-600 dark:focus:text-primary-200 first:rounded-l-md last:rounded-r-md dark:first:rounded-l-md dark:last:rounded-r-md">
                            @svg('tabler-arrows-maximize')
                            <span class="sr-only">Maximize</span>
                        </button>
                        <button onclick="window.cropper.zoom(0.1)" type="button"
                            class="inline-flex items-center justify-center gap-2 px-3 py-2 text-sm text-white transition-colors border-0 rounded-none focus:outline-none disabled:cursor-not-allowed disabled:opacity-60 focus:bg-primary-600 dark:bg-primary bg-primary dark:hover:bg-primary-600 hover:bg-primary-600 dark:focus:text-primary-200 first:rounded-l-md last:rounded-r-md dark:first:rounded-l-md dark:last:rounded-r-md">
                            @svg('tabler-zoom-in')
                            <span class="sr-only">Zoom In</span>
                        </button>
                        <button onclick="window.cropper.zoom(-0.1)" type="button"
                            class="inline-flex items-center justify-center gap-2 px-3 py-2 text-sm text-white transition-colors border-0 rounded-none focus:outline-none disabled:cursor-not-allowed disabled:opacity-60 focus:bg-primary-600 dark:bg-primary bg-primary dark:hover:bg-primary-600 hover:bg-primary-600 dark:focus:text-primary-200 first:rounded-l-md last:rounded-r-md dark:first:rounded-l-md dark:last:rounded-r-md">
                            @svg('tabler-zoom-out')
                            <span class="sr-only">Zoom Out</span>
                        </button>
                    </div>
                    <div class="grid grid-cols-4">
                        <button onclick="window.cropper.rotate(-90)" type="button"
                            class="inline-flex items-center justify-center gap-2 px-3 py-2 text-sm text-white transition-colors border-0 rounded-none focus:outline-none disabled:cursor-not-allowed disabled:opacity-60 focus:bg-primary-600 dark:bg-primary bg-primary dark:hover:bg-primary-600 hover:bg-primary-600 dark:focus:text-primary-200 first:rounded-l-md last:rounded-r-md dark:first:rounded-l-md dark:last:rounded-r-md">
                            @svg('tabler-arrow-back-up')
                            <span class="sr-only">Rotate image to left</span>
                        </button>
                        <button onclick="window.cropper.rotate(90)" type="button"
                            class="inline-flex items-center justify-center gap-2 px-3 py-2 text-sm text-white transition-colors border-0 rounded-none focus:outline-none disabled:cursor-not-allowed disabled:opacity-60 focus:bg-primary-600 dark:bg-primary bg-primary dark:hover:bg-primary-600 hover:bg-primary-600 dark:focus:text-primary-200 first:rounded-l-md last:rounded-r-md dark:first:rounded-l-md dark:last:rounded-r-md">
                            @svg('tabler-arrow-forward-up')
                            <span class="sr-only">Rotate image to right</span>
                        </button>
                        <button onclick="window.cropper.scaleX(-window.cropper.getData().scaleX || -1)" type="button"
                            class="inline-flex items-center justify-center gap-2 px-3 py-2 text-sm text-white transition-colors border-0 rounded-none focus:outline-none disabled:cursor-not-allowed disabled:opacity-60 focus:bg-primary-600 dark:bg-primary bg-primary dark:hover:bg-primary-600 hover:bg-primary-600 dark:focus:text-primary-200 first:rounded-l-md last:rounded-r-md dark:first:rounded-l-md dark:last:rounded-r-md">
                            @svg('tabler-flip-vertical')
                            <span class="sr-only">Flip vertical</span>
                        </button>
                        <button onclick="window.cropper.scaleY(-window.cropper.getData().scaleY || -1)" type="button"
                            class="inline-flex items-center justify-center gap-2 px-3 py-2 text-sm text-white transition-colors border-0 rounded-none focus:outline-none disabled:cursor-not-allowed disabled:opacity-60 focus:bg-primary-600 dark:bg-primary bg-primary dark:hover:bg-primary-600 hover:bg-primary-600 dark:focus:text-primary-200 first:rounded-l-md last:rounded-r-md dark:first:rounded-l-md dark:last:rounded-r-md">
                            @svg('tabler-flip-horizontal')
                            <span class="sr-only">Flip horizontal</span>
                        </button>
                    </div>
                </div>
                <div class="flex justify-end w-full gap-2 mt-2">
                    <x-larascaff::button type="button" onclick="window.cropImage()">Crop</x-larascaff::button>
                    <x-larascaff::button type="button" variant="secondary"
                        onclick="window.closeCropper()">Close</x-larascaff::button>
                </div>
            </div>
        </div>
    </div>
</div>
@endif