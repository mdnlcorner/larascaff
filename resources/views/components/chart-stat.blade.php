<div x-ignore x-load x-load-src="{{ asset('larascaff/components/chart.js') }}" x-data="initChart({
    data: @js($data),
    color: @js($color ?? 'success'),
    type: 'statistic'
})">
    <canvas class="h-6" x-ref="canvas"></canvas>
</div>
