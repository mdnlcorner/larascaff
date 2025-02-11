@props(['name', 'value' => null, 'label' => null, 'inline' => false, 'color' => null, 'icon', 'columnSpan' => 1])

<?php
$classMap = twMerge([$color == 'primary' ? 'text-primary' : ($color == 'warning' ? 'text-warning' : ($color == 'danger' ? 'text-danger' : ($color == 'info' ? 'text-info' : ($color == 'success' ? 'text-success' : ''))))]);
$record = $value ?? getRecord($name);
?>
<div @class(['text-sm', $inline ? 'flex gap-x-4' : '', $columnSpan != '1' ? 'md:col-span-' . $columnSpan : ''])>
    @if ($label)
    <div class="mb-2">{{ ucwords(str_replace('_', ' ', $label)) }}</div>
    @endif
    @if (is_array($record))
        @foreach ($record as $item)
            <div class="{{ $classMap }} mb-2">{{ $item }}</div>
        @endforeach
    @else
        @if (is_bool($record))
            @if ($record === true)
                @svg($icon ?? 'tabler-circle-check', twMerge(['text-success', $classMap]))
            @else
                @svg($icon ?? 'tabler-xbox-x', twMerge(['text-danger',$classMap]))
            @endif
        @else
            @svg($icon ?? '', $classMap)
        @endif
    @endif
</div>