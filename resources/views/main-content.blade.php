<x-larascaff-layout>
    <section>
        <div class="flex flex-col gap-4 mb-3 md:justify-between md:items-center md:flex-row">
            <div class="pb-2">
                @isset($dataTable)<p class="items-center hidden gap-1 text-sm md:flex text-muted-foreground"><span>{{ $pageTitle }}</span> @svg("tabler-chevron-right", 'w-4 h-4') <span>List</span></p>@endisset
                <h4>{{ $pageTitle }}</h4>
            </div>
            <div class="flex items-center gap-2" data-table-actions="{{ json_encode($tableActions ?? []) }}" data-actions="{{ json_encode($actions ?? []) }}">
                @foreach (($actions ?? []) as $item)
                    @if ($item['show']())
                        <x-larascaff::button 
                            data-handler="{{ json_encode($item['handler']) }}" 
                            variant="{{ $item['color'] }}" class="mb-3" 
                            data-method="{{ $item['method'] }}" 
                            data-url="{{ $item['ajax'] ? url('handler') : $item['url'] }}" 
                        >
                            {{ $item['label'] }}
                        </x-larascaff::button>
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
            @if($tabs->count())
                <div class="flex justify-center pb-6">
                    <div class="px-3 py-2.5 rounded-xl bg-card border">
                        <div x-data="{
                            init() {
                                const togglers = $el.querySelectorAll('[data-tabname]')
                                togglers.forEach(toggler => {
                                    toggler.addEventListener('click', function(e) {
                                        e.preventDefault()
                                        
                                        const pageUrl = new URL(window.location.href);
                                        
                                        pageUrl.searchParams.set('activeTab', this.dataset.tabname)
                                        
                                        togglers.forEach(_t => _t.classList.remove('active'))
                                        this.classList.add('active')
                                        
                                        window.history.pushState({}, '', pageUrl)
                                        window.LaravelDataTables[(window.datatableId)].ajax.url(pageUrl).load()
                                    })
                                })
                            }
                        }" class="flex items-center overflow-x-auto text-sm gap-x-4">
                            @foreach ($tabs as $name => $tab)
                                <a href="?activeTab={{ $name }}" data-tabname="{{ $name }}" class="flex items-center gap-x-2 text-muted-foreground hover:text-foreground px-2 py-2 rounded-md [&.active]:dark:bg-dark-800 [&.active]:bg-dark-100 [&.active]:text-primary {{ (request()->get('activeTab') == $name || (!request()->has('activeTab') && $loop->first)) ? 'active' : '' }}">{{ ucfirst(strtolower($name)) }} 
                                    @if ($tab->getBadge())
                                        @php
                                            $getColor = $tab->getBadgeColor();
                                            $badgeColor = ['border-primary/40', 'bg-primary/30', 'text-primary'];
                                            if ($getColor == 'danger') {
                                                $badgeColor = ['border-danger/40', 'bg-danger/30', 'text-danger'];
                                            } elseif($getColor == 'info') {
                                                $badgeColor = ['border-info/40', 'bg-info/30', 'text-info'];
                                            } elseif($getColor == 'warning') {
                                                $badgeColor = ['border-warning/40', 'bg-warning/30', 'text-warning'];
                                            } elseif($getColor == 'success') {
                                                $badgeColor = ['border-success/40', 'bg-success/30', 'text-success'];
                                            } elseif($getColor == 'secondary') {
                                                $badgeColor = ['border-secondary/40', 'bg-secondary/30', 'text-secondary'];
                                            } elseif($getColor == 'dark') {
                                                $badgeColor = ['border-dark/40', 'bg-dark/30', 'text-dark'];
                                            }
                                        @endphp
                                        <div class="py-0.5 min-w-5 text-center px-1 border {{ $badgeColor[0] }} {{ $badgeColor[1] }} {{ $badgeColor[2] }} border text-xs rounded-md"><div class="{{ $tab->getBadgeIcon() ? 'flex items-center gap-x-2' : '' }} {{ $tab->getBadgeIconPosition() == 'after' ? 'flex-row-reverse' : '' }}">@if ($tab->getBadgeIcon()) @svg($tab->getBadgeIcon(), 'w-4 h-4') @endif{{ $tab->getBadge() }}</div></div>
                                    @endif
                                </a>
                            @endforeach
                        </div>

                    </div>
                </div>
            @endif
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