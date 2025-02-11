@props(['name' => null, 
// 'components' => [], 
'columnSpan' => 2, 'columns' => 2])
<fieldset class="p-4 border rounded-md md:col-span-{{ $columnSpan }}">
    @if ($name)
    <legend class="px-2 text-sm">{{ $name }}</legend>
    @endif
    <div class="grid grid-cols-1 gap-x-6 gap-y-4 md:grid-cols-{{ $columns }}">
        {{-- @foreach ($components as $component)
            {!! $component->view() !!}
        @endforeach --}}
        {{ $slot }}
    </div>
</fieldset>