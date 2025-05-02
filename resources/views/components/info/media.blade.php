@props([
    'name',
    'value' => null,
    'label' => null,
    'color' => null,
    'rounded' => false,
    'isMedia' => true,
    'rounded' => false,
    'columnSpan' => null
])
<div @class(["w-full text-sm", $columnSpan != '1' ? 'md:col-span-' . $columnSpan : ''])>
    <div class="mb-2">{{ ucwords(str_replace('_', ' ', $label ?? '')) }}</div>
    <div class="flex flex-wrap items-center gap-6">
        @foreach (getRecord('media') as $media)
            @if (in_array($media->extension, ['png', 'jpg', 'jpeg']))
                <img onclick="window.open('{{ asset('storage/'. $media->path . '/'.$media->filename) }}', '_blank')" class="{{ twMerge(['w-full md:w-48 cursor-pointer', $rounded ? 'rounded-full' : '']) }}"
                    src="{{ asset('storage/'.$media->path . "/{$media->filename}") }}" alt="{{ $media->filename }}">
            @else
                <x-larascaff::button onclick="window.open('{{ asset($media->path . '/'.$media->filename) }}', '_blank')">@svg('tabler-file') {{ $media->filename }}</x-larascaff::button>
            @endif
        @endforeach
    </div>
</div>
