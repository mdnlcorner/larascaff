@props(['label' => null, 'id' => 'id'.rand(), 'type' => 'input' ,'size' => null, 'error' => null])
<label for="{{ $id }}" class="inline-block mb-1 text-sm">{{ $label }}</label>
<div id="{{ $id }}" class="flex items-center justify-center w-full border-2 !border-dashed rounded-lg dropzone dropzone-basic min-h-10 !border-border hover:border-primary ">
    <div class="flex flex-col items-center justify-center px-6 py-12 dz-message">
        <i data-feather="upload-cloud"></i>
        <div class="mt-2">
            <b>Click to upload</b> or drag and drop your file
        </div>
        @if ($error)
        <div class="mt-1 text-xs text-danger">{{ $error }}</div>
        @endif
    </div>
    <div class="fallback">
        <input hidden type="file" id="{{ $id }}"  />
    </div>
</div>
