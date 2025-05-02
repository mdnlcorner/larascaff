@props(['label' => null, 'id' => 'id'.rand(), 'type' => 'input' ,'size' => null, 'error' => null, 'prependIcon' => null, 'prependBtn' => null, 'appendIcon' => null, 'appendBtn' => null])
<div class="w-full form-wrapper">
    @if ($label)
    <label for="{{ $id }}" class="inline-block mb-1 text-muted-foreground {{ $size == 'sm' ? 'text-xs' : 'text-sm' }}">{{ $label }}</label>
    @endif
    <div class="relative">
        @if ($prependBtn)
        <button id="btn_{{ $id }}" type="button" class="absolute hover:opacity-70 inset-y-0 z-10 flex items-center px-3 border-r rounded-s-md start-0 {{ $error ? 'text-danger border-danger bg-danger/30' : 'text-muted-foreground border-input dark:bg-dark/20 bg-dark-300/30' }}">
            @svg('tabler-'.$prependBtn, 'w-5 h-5')
        </button>
        @endif
        @if ($prependIcon)
        <div type="button" class="absolute inset-y-0 z-10 flex items-center px-3 start-0 {{ $error ? 'text-danger' : 'text-muted-foreground' }}">
            @svg('tabler-'.$prependIcon, 'w-5 h-5')
        </div>
        @endif
        <input type="{{ $type }}" id="{{ $id }}" {{ $attributes->twMerge([
            'disabled:cursor-not-allowed [&.is-invalid]:border-danger [&.is-invalid]:focus-visible:ring-danger/60 read-only:dark:bg-dark-800 px-3 py-2 text-sm read-only:bg-dark-100 w-full bg-transparent border rounded-md border-border focus-visible:ring-2 focus-visible:outline-none focus-visible:ring-primary focus-visible:ring-offset-white placeholder:text-muted-foreground dark:focus-visible:ring-offset-dark-900 focus-visible:ring-offset-2',
            $prependIcon ? 'ps-10' : ($prependBtn ? 'ps-12' : null),
            // size
            $size == 'sm' ? 'py-1.5 px-2.5 text-xs' : null,
            $size == 'lg' ? 'px-3.5 py-3 text-default' : null,
            
            $error ? 'border-danger focus-visible:ring-danger/60' : null,
            $appendIcon ? 'pe-10' : null,
            $appendBtn ? 'pe-12' : null
        ]) }} />
        @if ($appendIcon)
        <div class="absolute inset-y-0 end-0 flex items-center px-3 rounded-e-md {{ $error ? 'text-danger' : 'text-muted-foreground ' }}">
            @svg('tabler-'.$appendIcon, 'w-5 h-5')
        </div>
        @endif
        @if ($appendBtn)
        <button id="btn_{{ $id }}" class="absolute inset-y-0 hover:opacity-70 end-0 flex items-center px-3 border-l rounded-e-md {{ $error ? 'text-danger border-danger bg-danger/30' : 'text-muted-foreground border-input dark:bg-dark/20 bg-dark-300/30' }}">
            @svg('tabler-'.$appendBtn, 'w-5 h-5')
        </button>
        @endif
    </div>
    @if ($error)
        <div class="mt-1 text-xs text-danger [&.invalid-feedback]:text-xs [&.invalid-feedback]:mt-1 [&.invalid-feedback]:text-danger">{{ $error }}</div>
    @endif
</div>