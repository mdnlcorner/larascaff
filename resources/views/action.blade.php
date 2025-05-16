@if (count($actions) > 2)
    <x-larascaff::button size="xs" data-dropdown-toggle data-dropdown-placement="bottom-end" type="button">
        <span>Action</span>
        @svg('tabler-dots-vertical', 'w-4 h-4')
    </x-larascaff::button>
    <x-larascaff::dropdown class="w-40">
        @foreach ($actions as $action)
            @if ($action['ajax'])
                <x-larascaff::dropdown-link data-method="{{ $action['method'] }}" data-action="{{ $action['url'] }}">
                    <button type="buttton" @class([
                        'flex items-center w-full gap-x-1 text-left',
                        'text-' . $action['color'] => $action['color'],
                    ])>
                        @if ($action['icon'])
                            <div>@svg($action['icon'], 'w-4 h-4')</div>
                        @endif
                        <span>{{ $action['label'] }}</span>
                    </button>
                </x-larascaff::dropdown-link>
            @else
                <x-larascaff::dropdown-link href="{{ $action['url'] }}" target="{{ $action['blank'] }}">
                    <button type="buttton" @class([
                        'flex items-center w-full gap-x-1 text-left',
                        'text-' . $action['color'] => $action['color'],
                    ])>
                        @if ($action['icon'])
                            <div>@svg($action['icon'], 'w-4 h-4')</div>
                        @endif
                        <span>{{ $action['label'] }}</span>
                    </button>
                </x-larascaff::dropdown-link>
            @endif
        @endforeach
    </x-larascaff::dropdown>
@else
    <div class="flex items-center gap-x-2">
        @foreach ($actions as $action)
            @if ($action['ajax'])
                <button type="buttton" data-method="{{ $action['method'] }}" data-action="{{ $action['url'] }}"
                @class([
                    'flex items-center w-full gap-x-1 text-left hover:underline',
                    'text-' . $action['color'] => $action['color'],
                ])>
                    @if ($action['icon'])
                        <div>@svg($action['icon'], 'w-4 h-4')</div>
                    @endif
                    <span>{{ $action['label'] }}</span>
                </button>
            @else
                <a href="{{ $action['url'] }}" target="{{ $action['blank'] }}"
                    @class(["flex items-center w-full gap-x-1 group", 'text-' . $action['color'] => $action['color'],])>
                    @if ($action['icon'])
                        <div>@svg($action['icon'], 'w-4 h-4')</div>
                    @endif
                    <span class="group-hover:underline">{{ $action['label'] }}</span>
                </a>
            @endif
        @endforeach
    </div>
@endif
