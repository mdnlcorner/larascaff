@isset($widgets['statistic'])
    @php
        $count = count($widgets['statistic']);
    @endphp
    <div @class([
        'grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 mb-6',
        $count <= 3 ? 'md:grid-cols-3' : 'lg:grid-cols-4 md:grid-cols-3',
    ])>
        @foreach ($widgets['statistic'] as $stat)
            @if (!isset($stat['color']))
                @php
                    $stat['color'] = 'success';
                @endphp
            @endif
            <div class="p-4 bg-white border rounded-lg dark:bg-dark-900 dark:border-dark-800">
                <div class="mb-3 text-sm text-muted-foreground">{{ $stat['label'] }}</div>
                <div class="overflow-hidden text-3xl font-semibold text-ellipsis">{{ $stat['value'] }}</div>
                @isset($stat['description'])
                <div class="flex items-center gap-x-2">
                    <div @class(["text-sm", $stat['color'] == 'danger' ? 'text-danger' : ($stat['color'] == 'warning' ? 'text-warning' : 'text-success')])>{{ $stat['description'] }}</div>
                    @isset($stat['descriptionIcon'])
                        <i data-feather="{{ $stat['descriptionIcon'] }}" @class(["w-4", $stat['color'] == 'danger' ? 'text-danger' : ($stat['color'] == 'warning' ? 'text-warning' : 'text-success')])></i>
                    @endisset
                </div>
                @endisset
                @isset($stat['chart'])
                    <x-larascaff::chart-stat type="statistic" :color="$stat['color'] ?? 'success'" :data="$stat['chart']['data']" />
                @endif
            </div>
        @endforeach
    </div>
@endisset
@isset($widgets['chart'])
    @php
        $count = count($widgets['chart']);
    @endphp
    <div @class([
        'grid grid-cols-1 md:grid-cols-2 gap-6 mb-6',
    ])>
        @foreach ($widgets['chart'] as $chart)
            @if (!isset($chart['color']))
                @php
                    $chart['color'] = 'success';
                @endphp
            @endif
            <div class="p-4 bg-white border rounded-lg dark:bg-dark-900 dark:border-dark-800">
                <div class="-mx-4 border-b dark:border-dark-800">
                    <div class="px-4 mb-3 font-semibold">{{ $chart['title'] }}</div>
                </div>
                <div class="pt-4">
                    <x-larascaff::chart type="chart" :data-label="$chart['dataLabel'] ?? false" :labels="$chart['labels']" :color="$chart['color'] ?? 'success'" :datasets="$chart['datasets']" />
                </div>
            </div>
        @endforeach
    </div>
@endisset
