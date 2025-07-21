@props([
    'name',
    'value' => null,
    'label' => null,
    'color' => null,
    'rounded' => false,
    'isMedia' => true,
    'rounded' => false,
    'columnSpan' => null,
    'disk' => 'public',
    'path' => null
])
<div @class(["w-full text-sm", $columnSpan != '1' ? 'md:col-span-' . $columnSpan : ''])>
    <div class="mb-2">{{ ucwords(str_replace('_', ' ', $label ?? '')) }}</div>
    <div class="flex flex-wrap items-center gap-6">
        @foreach (getRecord()->getMedia($name) as $filename)
            @php
                $url = Storage::disk($disk)->url(str($path)->start('/')->finish('/')->toString() . $filename);
            @endphp
            @if (in_array(str($filename)->afterLast('.')->toString(), ['png', 'jpg', 'jpeg']))
                <img onclick="window.open('{{ $url }}', '_blank')" class="{{ twMerge(['w-full md:w-48 cursor-pointer', $rounded ? 'rounded-full' : '']) }}"
                    src="{{ $url }}" alt="{{ $filename }}">
            @else
                <x-larascaff::button onclick="window.open('{{ $url }}', '_blank')">@svg('tabler-file') {{ $filename }}</x-larascaff::button>
            @endif
        @endforeach
    </div>
</div>
