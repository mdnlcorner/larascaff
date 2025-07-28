@foreach ($widgets as $widget)
    @if ($widget::getWidgetType() == 'statistic')
        @include('larascaff::widget-stat', ['statWidgets' => $widget])
    @elseif($widget::getWidgetType() == 'chart')
        @include('larascaff::widget-chart', ['widget' => $widget])
    @elseif($widget::getWidgetType() == 'table')
        @include('larascaff::widget-table', ['widget' => $widget])
    @endif
@endforeach
