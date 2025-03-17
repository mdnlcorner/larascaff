@if (count($actions) > 2)
    <x-larascaff::button size="xs" data-dropdown-toggle data-dropdown-placement="bottom-end" type="button">
        <span>Action</span>
        @svg('tabler-dots-vertical', 'w-4 h-4')
    </x-larascaff::button>
    <x-larascaff::dropdown class="w-40">
        @foreach ($actions as $permission => $item)
            @if ($item['ajax'])
                <x-larascaff::dropdown-link data-method="{{ $item['method'] }}" data-action="{{ $item['url'] }}">
                    <button type="buttton" @class([
                        'flex items-center w-full gap-x-1 text-left',
                        'text-' . $item['color'] => $item['color'],
                    ])>
                        @if ($item['icon'])
                            <div>@svg($item['icon'], 'w-4 h-4')</div>
                        @endif
                        <span>{{ $item['label'] }}</span>
                    </button>
                </x-larascaff::dropdown-link>
            @else
                <x-larascaff::dropdown-link href="{{ $item['url'] }}" target="{{ $item['blank'] }}">
                    <button type="buttton" @class([
                        'flex items-center w-full gap-x-1 text-left',
                        'text-' . $item['color'] => $item['color'],
                    ])>
                        @if ($item['icon'])
                            <div>@svg($item['icon'], 'w-4 h-4')</div>
                        @endif
                        <span>{{ $item['label'] }}</span>
                    </button>
                </x-larascaff::dropdown-link>
            @endif
        @endforeach
    </x-larascaff::dropdown>
@else
    <div class="flex items-center gap-x-2">
        @foreach ($actions as $permission => $item)
            @if ($item['ajax'])
                <button type="buttton" data-method="{{ $item['method'] }}" data-action="{{ $item['url'] }}"
                @class([
                    'flex items-center w-full gap-x-1 text-left hover:underline',
                    'text-' . $item['color'] => $item['color'],
                ])>
                    @if ($item['icon'])
                        <div>@svg($item['icon'], 'w-4 h-4')</div>
                    @endif
                    <span>{{ $item['label'] }}</span>
                </button>
            @else
                <a href="{{ $item['url'] }}" target="{{ $item['blank'] }}"
                    @class(["flex items-center w-full gap-x-1 group", 'text-' . $item['color'] => $item['color'],])>
                    @if ($item['icon'])
                        <div>@svg($item['icon'], 'w-4 h-4')</div>
                    @endif
                    <span class="group-hover:underline">{{ $item['label'] }}</span>
                </a>
            @endif
        @endforeach
    </div>
@endif
