@isset($dataTable)
    <div class="card">
        <div class="border rounded-md dark:border-dark-800 border-dark-100">
            {!! $dataTable->table() !!}
        </div>
    </div>

    @push('js')
        <script type="module" src="{{ asset('larascaff/components/datatable.js') }}"></script>
        {!! $dataTable->scripts(attributes: ['type' => 'module']) !!}
    @endpush
    @push('css')
        <link rel="stylesheet" href="{{ asset('larascaff/components/datatable.css') }}">
    @endpush
@endisset