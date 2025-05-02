@props([
    'name',
    'value' => null,
    'label' => null,
    'inline' => false,
    'color' => null,
    'badge' => false,
    'appendIcon',
    'prependIcon',
    'columnSpan' => 1,
    'numberFormat',
    'html'
])

<?php
$colorMap = $color == 'primary' ? 'text-primary' : ($color == 'warning' ? 'text-warning' : ($color == 'danger' ? 'text-danger' : ($color == 'info' ? 'text-info' : ($color == 'success' ? 'text-success' : ''))));
$badgeMap = $badge ? 'rounded-md px-2 py-0.5 inline-block ' . ($color == 'primary' ? 'bg-primary/30' : ($color == 'warning' ? 'bg-warning/30' : ($color == 'danger' ? 'bg-danger/30' : ($color == 'info' ? 'bg-info/30' : ($color == 'success' ? 'bg-success/30' : 'bg-primary/30 text-primary'))))) : '';
?>
<div @class(['text-sm mb-3', $inline ? 'flex gap-x-4' : '', $columnSpan != '1' ? 'md:col-span-' . $columnSpan : ''])>
    <div class="mb-2">{{ ucwords(str_replace('_', ' ', $label ?? $name)) }}</div>
    @if (is_null($value) && is_array(getRecord($name)))
        @foreach (getRecord($name) as $item)
            <div class="{{ twMerge([$colorMap, $badgeMap]) }} mb-2">{{ $numberFormat ? number_format($item,0, $numberFormat[1], $numberFormat[0]) : $item}}</div>
        @endforeach
    @else
        @if ($html)
            <div class="relative w-full prose min-h-40 max-w-none px-3 py-1.5 dark:prose-invert focus-visible:outline-none  sm:text-sm sm:leading-6 border rounded-md">
                {!! getRecord($name) !!}
            </div>
        @else
            <div class="{{ twMerge($colorMap, $badgeMap, [($appendIcon || $prependIcon) ? 'inline-flex items-center gap-x-2' : '']) }}">
                @if ($prependIcon)
                    @svg($prependIcon, 'w-5 h-5')
                @endif
                {{ $value ?? ($numberFormat ? number_format(getRecord($name),0, $numberFormat[1], $numberFormat[0]) : getRecord($name))  }}
                @if ($appendIcon)
                    @svg($appendIcon, 'w-5 h-5')
                @endif
            </div>
        @endif
    @endif
</div>
