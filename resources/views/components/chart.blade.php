<div x-ignore x-load x-load-src="{{ asset('larascaff/components/chart.js') }}" x-data="initChart({
    datasets: @js($datasets),
    color: @js($color ?? 'success'),
    type: @js($type),
    widgetType: @js($widgetType),
    labels: @js($labels),
    dataLabel: @js($dataLabel ?? false)
})">
    <canvas x-ref="canvas"></canvas>
</div>
