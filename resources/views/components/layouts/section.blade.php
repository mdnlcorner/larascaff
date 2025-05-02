@props([
    'name' => null,
    'description' => null,
    // 'components' => '',
    'columnSpan' => 2,
    'columns' => 2,
    'collapsible' => false,
    'collapsed' => false,
    'slot' => '',
])
@if ($collapsible)
    @php
        $accordionId = 'accordion'.rand();
    @endphp
    <div x-ignore x-load x-load-src="{{ asset('larascaff/components/accordion.js') }}" x-data="initAccordion({})" class="border rounded-md md:col-span-{{ $columnSpan }}">
        <div x-ref="accordionWrapper" data-accordion="open">
            @if ($name || $description)
                <div class="flex items-center justify-between p-4 cursor-pointer" aria-expanded="{{ $collapsed ? 'false' : 'true' }}" data-accordion-target="#{{ $accordionId }}">
                    <div>
                        <span class="font-semibold select-none">{{ $name }}</span>
                        @if ($description)
                            <div class="mt-1.5 select-none text-sm text-muted-foreground">{{ $description }}</div>
                        @endif
                    </div>
                    {{-- rotate-90 --}}
                    <div data-accordion-icon class="transition text-muted-foreground">@svg('tabler-chevron-right', 'w-5 h-5')</div>
                </div>
            @endif
            <div id="{{ $accordionId }}" class="p-4 hidden grid-cols-1 gap-x-6 gap-y-4 md:grid-cols-{{ $columns }}">
                {{-- @foreach ($components as $component)
                    {!! $component->view() !!}
                @endforeach --}}
                {{ $slot }}
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
        {{-- @foreach ($components as $component)
            {!! $component->view() !!}
        @endforeach --}}
        {{ $slot }}
    </div>
</div>
@endif
