
@foreach ($widgets as $widget)
    @if ($widget::getWidgetType() == 'statistic')
        @include('larascaff::widget-stat', ['statWidgets' => $widget])
    @elseif($widget::getWidgetType() == 'chart')
        <div @class([
            'grid grid-cols-1 md:grid-cols-2 gap-6 mb-6',
        ])>
            @php
                $chart = $widget::getData();
            @endphp
            <div class="p-4 bg-white border rounded-lg dark:bg-dark-900 dark:border-dark-800">
                @if ($widget::getHeading())
                    <div class="pb-4 -mx-4 border-b dark:border-dark-800">
                        <div class="px-4">
                            <div class="font-semibold">{{ $widget::getHeading() }}</div>
                            <div class="text-sm text-muted">{{ $widget::getDescription() }}</div>
                        </div>
                    </div>
                @endif
                <div class="pt-4">
                    <x-larascaff::chart 
                        :widget-type="$widget::getWidgetType()" 
                        :type="$widget::getType()" 
                        :data-label="$chart['dataLabel'] ?? false" 
                        :labels="$chart['labels']" 
                        :color="$widget::getColor()"
                        :datasets="$chart['datasets']" />
                </div>
            </div>
        </div>
    @endif
@endforeach
