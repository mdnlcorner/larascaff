
<img 
    style="{{ $imageSize ? "width:{$imageSize}px; height:{$imageSize}px;" : ($imageWidth ? "width:{$imageWidth}px;" : ($imageHeight ? "height:{$imageHeight}px;" : "")) }}" 
    src="{{ $source }}" 
    class="{{ twMerge('w-12', $circle ? 'rounded-full' : 'rounded') }}" 
    alt="{{ $source }}"
/>
