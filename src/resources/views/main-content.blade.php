<x-larascaff-layout>
    <section>
        <div class="flex flex-col gap-4 mb-3 md:justify-between md:items-center md:flex-row">
            <div>
                @isset($dataTable)<p class="items-center hidden gap-1 text-sm md:flex text-muted-foreground"><span>{{ $pageTitle }}</span> @svg("tabler-chevron-right", 'w-4 h-4') <span>List</span></p>@endisset
                <h4>{{ $pageTitle }}</h4>
            </div>
            <div class="flex items-center gap-2" data-table-actions="{{ json_encode($tableActions?? []) }}" data-actions="{{ json_encode($actions ?? []) }}">
                @foreach (($actions ?? []) as $item)
                    @if ($item['show']())
                        <x-larascaff::button class="mb-3" data-method="{{ $item['method'] }}" data-action="{{ $item['action'] }}">{{ $item['label'] }}</x-larascaff::button>
                    @endif
                @endforeach
            </div>
        </div>
        @isset($widgets)
            {!! $widgets !!}
        @endisset
        @isset($view)
            {!! $view !!}
        @endisset
        @isset($dataTable)
            <div class="card">
                <div class="border rounded-md dark:border-dark-800 border-dark-100">
                    @isset($filterTable)
                        {!! $filterTable !!}
                    @endisset

                    {!! $dataTable->table(['style' => 'width: 100%;']) !!}
                </div>
            </div>
            @push('js')
            <script type="module" src="{{ asset('larascaff/components/datatable.js') }}"></script>
            {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
            @endpush
            @push('css')
                <link rel="stylesheet" href="{{ asset('larascaff/components/datatable.css') }}">
            @endpush
        @endisset
    </section>
    @isset($url)
        @php
            $prefix = getPrefix();
            if ($prefix && substr($url, 0 , strlen($prefix)) == $prefix) {
                $url = substr($url, strlen($prefix) + 1);
            }
        @endphp
    
        @push('jsModule')
            @if (file_exists(resource_path('js/pages/'.$url.'.ts')))
                @vite(['resources/js/pages/'.$url. '.ts'])
            @endif
            @if (file_exists(resource_path('js/pages/'.$url.'.js')))
                @vite(['resources/js/pages/'.$url. '.js'])
            @endif
        @endpush
    @endisset
    <x-larascaff::modal data-modal-backdrop="static" id="modalAction"></x-larascaff::modal>
    <x-larascaff::modal id="modalSelectTable"></x-larascaff::modal>
</x-larascaff-layout>