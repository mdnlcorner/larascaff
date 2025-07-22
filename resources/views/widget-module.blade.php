@foreach ($widgets as $statWidgets)
    @include('larascaff::widget-stat', ['statWidgets' => $statWidgets])
@endforeach
