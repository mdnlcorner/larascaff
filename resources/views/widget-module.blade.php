@isset($widgets)
    @php
        $count = count($widgets);
    @endphp
    <div @class([
        'grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 mb-6',
        $count <= 3 ? 'md:grid-cols-3' : 'lg:grid-cols-4 md:grid-cols-3',
    ])>
        @foreach ($widgets as $stat)
            <div class="p-4 bg-white border rounded-lg dark:bg-dark-900 dark:border-dark-800">
                <div class="mb-3 text-sm text-muted-foreground">{{ $stat['label'] }}</div>
                <div class="mb-3 overflow-hidden text-3xl font-semibold text-ellipsis">{{ $stat['value'] }}</div>
                @isset($stat['description'])
                <div class="flex">
                    {{-- text-danger text-info text-warning text-success text-primary text-secondary --}}
                    <div class="flex {{ $stat['descriptionIcon']['position']->value == 'before' ? 'flex-row-reverse' : '' }} items-center gap-x-2">
                        <div @class(["text-sm", "text-" . $stat['color']->value])>{{ $stat['description'] }}</div>
                        @isset($stat['descriptionIcon'])
                            @svg($stat['descriptionIcon']['icon'], implode(' ', ["w-4", "text-". $stat['color']->value]))
                        @endisset
                    </div>
                </div>
                @endisset
                @isset($stat['chart'])
                    <x-larascaff::chart-stat type="statistic" :color="$stat['color']->value" :data="$stat['chart']" />
                @endif
            </div>
        @endforeach
    </div>
@endisset
