<fieldset class="px-4 pt-2 pb-4 m-4 mb-3 border rounded-lg">
    <legend class="px-2 text-sm">Filter</legend>
    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4">
        {!! $filterTable !!}
        {{-- @foreach ($filterTable as $item)
            @if ($item['type'] == 'select')
            <x-larascaff::forms.select label="{{ ucfirst($item['name']) }}" data-filter="user-table" name="{{ $item['name'] }}" >
                <option value="">Choose</option>
                @foreach ($item['options'] as $key => $opt)
                    <option value="{{ $opt }}">{{ $key }}</option>
                @endforeach
            </x-larascaff::forms.select>
            @elseif($item['type'] == 'nullable')
                <div class="">
                    <div class="mb-3">{{ ucfirst($item['label'] ?? $item['name']) }}</div>
                    <x-larascaff::forms.checkbox label="" value="1" data-filter="user-table" name="{{ $item['name'] }}" />
                </div>
            @endif
        @endforeach --}}
    </div>
</fieldset>