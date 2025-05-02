@props(['label', 'id' => 'id'.rand(), 'type' => 'input', 'error' => null])
<div class="{{ $label ? 'mb-3' : '' }} w-full relative">
    <input type="{{ $type }}" id="{{ $id }}" {{ $attributes->twMerge([
        'w-full px-3 pt-6 pb-2 bg-transparent border text-sm rounded-md placeholder:transition-opacity placeholder:duration-300 peer placeholder:opacity-0 focus-visible:placeholder:opacity-100 disabled:cursor-not-allowed read-only:dark:bg-dark-800 read-only:bg-dark-100 border-border focus-visible:ring-2 focus-visible:outline-none focus-visible:ring-primary focus-visible:ring-offset-white placeholder:text-muted-foreground dark:focus-visible:ring-offset-dark-900 focus-visible:ring-offset-2',
        $error ? 'border-danger focus-visible:ring-danger/60' : null,
    ]) }} />
    <label for="{{ $id }}" class="absolute z-10 inline-block mb-1 peer-placeholder-shown:translate-y-0.5 transition top-4 left-3.5 -translate-y-2 peer-focus-visible:-translate-y-2 text-xs peer-focus-visible:text-xs peer-placeholder-shown:text-sm text-muted-foreground">{{ $label }}</label>
    @if ($error)
        <div class="mt-1 text-xs text-danger">{{ $error }}</div>
    @endif
</div>