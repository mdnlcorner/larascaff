
<div class="flex items-center gap-1">
@foreach ($sources as $source)
    <div style="{{ $imageSize ? "width:{$imageSize}px; height:{$imageSize}px;" : ($imageWidth ? "width:{$imageWidth}px;" : ($imageHeight ? "height:{$imageHeight}px;" : "")) }}"  class="w-12">
        <img 
            style="{{ $imageSize ? "width:{$imageSize}px; height:{$imageSize}px;" : ($imageWidth ? "width:{$imageWidth}px;" : ($imageHeight ? "height:{$imageHeight}px;" : "")) }}" 
            src="{{ $source }}" 
            class="{{ twMerge('w-12', $circle ? 'rounded-full' : 'rounded') }}" 
            alt="{{ $source }}"
        />
    </div>
@endforeach
</div>