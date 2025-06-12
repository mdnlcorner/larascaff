@props(['id' => 'id' . rand(), 'label' => null, 'name', 'error' => null, 'rows' => '3', 'value' => null, 'columnSpan' => '1'])
<div @class([
    'w-full form-wrapper',
    $columnSpan != '1' ? 'md:col-span-' . $columnSpan : '',
])>
    @if ($label)
        <label for="{{ $id }}" class="inline-block mb-1 text-sm">{{ $label }}</label>
    @endif
    <textarea data-input-name="{{ $name }}" name="{{ $name }}" id="{{ $id }}" rows="{{ $rows }}"
        {{ $attributes->twMerge([
            'w-full px-3 py-2 text-sm [&.is-invalid]:border-danger [&.is-invalid]:focus-visible:ring-danger/60 bg-transparent border rounded-md border-border focus-visible:outline-none focus-visible:ring-2 dark:focus-visible:ring-offset-dark-900 focus-visible:placeholder:text-muted-foreground focus-visible:ring-primary focus-visible:ring-offset-2',
            $error ? 'border-danger focus-visible:ring-danger/60' : null,
        ]) }}>{{ $value ?? getRecord($name) }}</textarea>
    @if ($error)
        <div class="mt-1 text-xs text-danger">{{ $error }}</div>
    @endif
</div>
