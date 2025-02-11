@props([
    'label' => null,
    'value' => null,
    'name',
    'id' => 'id' . rand(),
    'type' => 'input',
    'size' => null,
    'error' => null,
    'columnSpan' => '1',
    'appendIconBtn' => null,
    'appendIcon' => null,
    'prependIconBtn' => null,
    'prependIcon' => null,
    'revealable' => false,
    'mask' => '',
    'numberFormat' => null
])
<div x-data="{
    value: @js(is_null($value) ? ($numberFormat ? number_format(getRecord($name),0, $numberFormat[1], $numberFormat[0]) : getRecord($name)) ?? '' : $value),
    type: '{{ $type }}',
    revealableIcon: 'tabler-eye'
}" @set-{{ $name }}.window = "value = $event.detail" @class([
    'w-full form-wrapper',
    $columnSpan != '1' ? 'md:col-span-' . $columnSpan : '',
])>
    @if ($label)
        <label for="{{ $id }}"
            class="inline-block mb-1 {{ $size == 'sm' ? 'text-xs' : 'text-sm' }}">{{ $label }}</label>
    @endif
    <div class="relative">
        @if ($prependIconBtn)
        <button id="btn_{{ $id }}" type="button" class="absolute hover:opacity-70 inset-y-0 z-10 flex items-center px-3 rounded-s-md start-0 {{ $error ? 'text-danger border-danger bg-danger/30' : 'text-muted-foreground border-input dark:bg-dark/20 bg-dark-300/30' }}">
            @svg($prependIconBtn, 'w-5 h-5')
        </button>
        @elseif($prependIcon)
        <div type="button" class="absolute inset-y-0 z-10 flex items-center px-3 start-0 {{ $error ? 'text-danger' : 'text-muted-foreground' }}">
            @svg($prependIcon, 'w-5 h-5')
        </div>
        @endif
        <input x-model="value" @if ($numberFormat) onkeyup="this.value = numberFormat(this.value,'{{ $numberFormat[0] ?? '.' }}','{{ $numberFormat[1] ?? ',' }}')" @endif  @if ($mask) x-mask:dynamic="{{ $mask }}" @endif name="{{ $name }}" :type="type" id="{{ $id }}" {{ $attributes->twMerge([
                'disabled:cursor-not-allowed [&.is-invalid]:border-danger [&.is-invalid]:focus-visible:ring-danger/60 read-only:dark:bg-dark-800 px-3 py-2 text-sm read-only:bg-dark-100 w-full bg-transparent border rounded-md border-border focus-visible:ring-2 focus-visible:outline-none focus-visible:ring-primary focus-visible:ring-offset-white placeholder:text-muted-foreground dark:focus-visible:ring-offset-dark-900 focus-visible:ring-offset-2',
                $error ? 'border-danger focus-visible:ring-danger/60' : null,
                $prependIcon ? 'ps-10' : ($prependIconBtn ? 'ps-12' : null),
                $appendIcon ? 'pe-10' : null,
                $appendIconBtn ? 'pe-12' : null,
            ]) }} />
        @if ($appendIconBtn || $revealable)
            <button @if ($revealable) @click="type = (type == 'password' ? 'text' : 'password')" @endif  
            id="btn_{{ $id }}"
                class="absolute inset-y-0 hover:opacity-70 end-0 flex items-center px-3 rounded-e-md {{ $error ? 'text-danger border-danger bg-danger/30' : 'text-muted-foreground border-input dark:bg-dark/20 bg-dark-300/30' }}" type="button">
                <div x-show="type=='password'">@svg($revealable ? 'tabler-eye' : $appendIconBtn, 'w-5 h-5')</div>
                <div x-show="type=='text'">@svg($revealable ? 'tabler-eye-off' : $appendIconBtn, 'w-5 h-5')</div>
            </button>
        @elseif($appendIcon)
            <div class="absolute inset-y-0 end-0 flex items-center px-3 rounded-e-md {{ $error ? 'text-danger' : 'text-muted-foreground ' }}">
                {{ $appendIconBtn }}
            </div>
        @endif
    </div>
    @if ($error)
        <div
            class="mt-1 text-sm text-danger [&.invalid-feedback]:text-sm [&.invalid-feedback]:mt-1 [&.invalid-feedback]:text-danger">
            {{ $error }}</div>
    @endif
</div>
