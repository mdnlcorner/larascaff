@props(['label' => null, 'id' => 'id'.rand(), 'type' => 'input' ,'size' => null, 'error' => null])
<div class="{{ $label ? 'mb-3' : null }}">
    @if ($label)
        <label for="{{ $id }}" class="inline-block mb-1 text-sm bg-red">{{ $label }}</label>
    @endif
    <input type="file" id="{{ $id }}" {{ $attributes->twMerge([
        'cursor-pointer w-full border-input px-3 py-2 border rounded-md file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-dark-800 file:text-white hover:file:bg-primary file:cursor-pointer',
        $error ? 'border-danger' : null
    ]) }}  />
    @if ($error)
    <div class="mt-1 text-xs text-danger">{{ $error }}</div>
    @endif
</div>
