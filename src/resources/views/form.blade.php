<x-larascaff::modal-content 
    size="{{ $size ?? 'xl' }}" 
    title="{{ $title ?? 'Modal Title' }}" 
    action="{{ $action ?? null }}"
    actionLabel="{{ $actionLabel ?? 'Save' }}" size="{{ $size ?? 'md' }}" center="{{ $center ?? false }}"
    method="{{ $method ?? 'POST' }}"
>
    {!! $slot !!}
</x-larascaff::modal-content>
