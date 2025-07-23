<div class="absolute inset-x-0 bottom-0 h-6 overflow-hidden" 
x-ignore x-load x-load-src="{{ asset('larascaff/components/chart.js') }}" x-data="initChart({
    data: @js($data),
    color: @js($color ?? 'success'),
    widgetType: @js($widgetType),
})">
    <canvas class="h-6" x-ref="canvas"></canvas>
</div>
