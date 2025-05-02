@props([
    'name' => null,
    'description' => null,
    'components' => [],
    'columnSpan' => 2,
    'columns' => 2,
    'collapsible' => false,
    'collapsed' => false,
    'withCalculate' => [],
    'relationship',
    'tableRows' => [],
    'module'
])

@if ($collapsible)
    <div x-ignore x-load x-load-src="{{ asset('larascaff/components/accordion.js') }}" x-data="initAccordion({})" class="border rounded-md md:col-span-{{ $columnSpan }}">
        <div x-ref="accordionWrapper" data-accordion="open">
            @if ($name || $description)
                <div class="flex items-center justify-between p-4 cursor-pointer" aria-expanded="{{ $collapsed ? 'false' : 'true' }}" data-accordion-target="#repeater_accordion_{{ $relationship }}">
                    <div>
                        <span class="font-semibold select-none">{{ $name }}</span>
                        @if ($description)
                            <div class="mt-1.5 select-none text-sm text-muted-foreground">{{ $description }}</div>
                        @endif
                    </div>
                    {{-- rotate-90 --}}
                    <div data-accordion-icon class="text-muted-foreground">@svg('tabler-chevron-right', 'w-5 h-5')</div>
                </div>
            @endif
            <div 
            x-data="{
                init() {
                    this.wrapper = this.$el
                },
                tableRows: @js($tableRows),
                relationship: @js($relationship),
                wrapper: null,
                handleAdd: async function() {
                    this.$el.setAttribute('disabled', true)
                    const label = this.$el.innerHTML
                    this.$el.innerText = 'Loading...'

                    const formData = new FormData()
                    formData.append('module', @js(get_class($module)))
                    
                    const inputs = this.wrapper.querySelectorAll('input')
                    inputs.forEach(item => {
                        formData.append(item.name, item.value)
                    })
                    const selects = this.wrapper.querySelectorAll('select')
                    selects.forEach(item => {
                        formData.append(item.name, item.value)
                    })
                    
                    try {
                        const req = await fetch('{{ url('repeater-items') }}', {
                            method: 'post',
                            headers: {
                                'accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf_token]').getAttribute('content')
                            },
                            body: formData
                        })
                        const res = await req.json()
                        this.$el.removeAttribute('disabled')
                        this.$el.innerHTML = label

                        const wrapper = $(this.wrapper)

                        wrapper.find('.is-invalid').removeClass('is-invalid')
                        wrapper.find('.invalid-feedback').remove()

                        if (!req.ok) {
                            const errors = res?.errors
                            if (errors) {
                                let i = 0;
                                for (let [key, value] of Object.entries(errors)) {
                                    let input = wrapper.find(`[name=${key}]`)

                                    if (!input.length) {
                                        if (key.includes('.')) {
                                            value = value[0].replace(key, key.split('.')[0])
                                            key = key.split('.')[0]
                                        }
                                        input = wrapper.find(`[name=${key}[]]`)
                                    }
                                    if (i == 0) {
                                        input.trigger('focus')
                                    }

                                    input.addClass('is-invalid').parents('.form-wrapper').append(`<div class='invalid-feedback [&.invalid-feedback]:text-sm [&.invalid-feedback]:mt-1 [&.invalid-feedback]:text-danger'>${value}</div>`);
                                    
                                    i++
                                }
                            }
                            return;
                        }

                        let rows = ''
                        let inputs = {}
                        let hiddenInputs = {}
                        for(let name in res) {
                            if (!this.tableRows.includes(name)) {
                                hiddenInputs[name] = res[name]
                                
                            } else {
                                inputs[name] = res[name]
                            }
                        }
                        console.log(inputs, hiddenInputs)
                        
                        this.tableRows.forEach(name => {
                            rows += `<td>${inputs[name]}<input type='hidden' value='${inputs[name]}' name='${this.relationship}[${name}][]' /></td>`
                        })

                        for(let name in hiddenInputs) {
                            rows += `<td hidden><input type='hidden' value='${hiddenInputs[name]}' name='${this.relationship}[${name}][]' /></td>`
                        }

                        const tr = document.createElement('tr')
                        tr.innerHTML = rows

                        {{-- console.log(tr)

                        console.log(rows, this.$refs.rows) --}}
                        this.$refs.rows.prepend(tr)
                        
                        {{-- console.log(res, this.tableRows)   --}}
                    } catch (err) {
                        console.error('error', err)
                    }
                },

            }"
            id="repeater_accordion_{{ $relationship }}" class="p-4 hidden w-full grid-cols-1 gap-x-6 gap-y-4 md:grid-cols-{{ $columns }}">
                @foreach ($components as $component)
                    {!! $component->view() !!}
                @endforeach
                <div class="flex justify-end md:col-span-full">
                    <x-larascaff::button x-ref="btnAddItems" type="button" @click="handleAdd()">Add</x-larascaff::button>
                </div>
                <table class="table mt-2 md:col-span-full">
                    <thead>
                        <tr>
                            @foreach ($tableRows as $item)
                            <th>{{ $item }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody x-ref="rows" id="repeater_{{ $relationship }}"></tbody>
                </table>
            </div>
        </div>
    </div>
@else
<div class="border rounded-md md:col-span-{{ $columnSpan }}">
    @if ($name || $description)
        <div class="p-4 border-b">
            <span class="font-semibold"></span>{{ $name }}
            @if ($description)
                <div class="mt-1.5 text-sm text-muted-foreground">{{ $description }}</div>
            @endif
        </div>
    @endif
    <div class="p-4 grid grid-cols-1 gap-x-6 gap-y-4 md:grid-cols-{{ $columns }}">
        @foreach ($components as $component)
            {!! $component->view() !!}
        @endforeach
    </div>
</div>
@endif
