<div class="px-6 py-4 mb-6 bg-white border rounded-lg dark:bg-dark-900 dark:border-dark-800">
    @if ($widget::getHeading())
        <div class="pb-4 mb-4 -mx-6 border-b dark:border-dark-800">
            <div class="px-6">
                <div class="font-semibold">{{ $widget::getHeading() }}</div>
                <div class="text-sm text-muted">{{ $widget::getDescription() }}</div>
            </div>
        </div>
    @endif
    <div class="border rounded-md dark:border-dark-800 border-dark-100">
        {!! $resolveTableWidget($widget)->table() !!}
    </div>
</div>

@if (! isset($tableLoaded))
    @push('js')
        <script type="module" src="{{ asset('larascaff/components/datatable.js') }}"></script>
    @endpush
    @push('css')
        <link rel="stylesheet" href="{{ asset('larascaff/components/datatable.css') }}">
    @endpush
    @php
        $tableLoaded = true;
    @endphp
@endif
@push('js')
    {!! $resolveTableWidget($widget)->scripts(attributes: ['type' => 'module']) !!}
@endpush