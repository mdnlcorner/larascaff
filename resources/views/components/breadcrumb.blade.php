@props(['breadcrumbs' => []])
@if (count($breadcrumbs))
<nav {{ $attributes->twMerge('flex') }} aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
    @foreach ($breadcrumbs as $breadcrumb)
        <li class="last:text-muted">
            <a href="{{ $breadcrumb['link'] }}"
                class="inline-flex items-center gap-2 text-sm font-medium transition-colors hover:text-primary ">
                {{ $breadcrumb['label'] }}
            </a>
        </li>
    @endforeach
    </ol>
</nav>
@endif
