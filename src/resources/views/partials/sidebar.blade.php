<aside class="relative sidebar">
    <!-- Sidebar Header Starts -->
    <div class="px-6 py-4">
        <a class="sidebar-brand-logo" style="height: {{ $config->getBrandHeight() }}" href="{{ url($config->getPrefix(). '/dashboard') }}">
            @if (is_callable($config->renderBrand()))
                {{ $config->renderBrand()() }}
            @else
                @if ($config->getBrandName())
                    {{ $config->getBrandName() }}
                @else
                    <img style="height: {{ $config->getBrandHeight() }}" src="{{ $config->renderBrand() }}" class="w-full" alt="brand-logo">
                @endif
            @endif
        </a>
    </div>
    <!-- Sidebar Header Ends -->
    <div class="absolute z-10 hidden w-full h-20 pointer-events-none top-14 shadow-sidebar bg-gradient-to-b from-white to-transparent dark:from-dark-900 dark:to-transparent"></div>
    <!-- Sidebar Menu Starts -->
    <ul class="relative pb-8 sidebar-content">
        @foreach (menus() as $category => $menus)
            @php
                $showCategory = true;
            @endphp
            @foreach ($menus as $mm)
                @can('read '. $mm->url)
                    @if ($showCategory && $category != '')
                        <div class="sidebar-menu-header">{{ $category }}</div>
                        @php
                            $showCategory = false;
                        @endphp
                    @endif
                    <li @class(['active open' => str_contains(request()->path(), $mm->url)])>
                        @if (count($mm->subMenus))
                            <a href="javascript:void(0);" @class([
                                'sidebar-menu',
                                'active' => str_starts_with(request()->path(), $mm->url),
                            ])>
                                <span class="sidebar-menu-icon">
                                    @svg($mm->icon)
                                </span>
                                <span class="sidebar-menu-text">{{ $mm->name }}</span>
                                <span class="sidebar-menu-arrow">
                                    @svg('tabler-chevron-right')
                                </span>
                            </a>
                            <ul class="sidebar-submenu">
                                @foreach ($mm->subMenus as $sm)
                                    @can('read '. $sm->url)
                                        @if (count($sm->subMenus))
                                            <li>
                                                <a href="javascript:void(0);" @class([
                                                    'sidebar-menu',
                                                    'active' => str_starts_with(request()->path(), $sm->url),
                                                ])>
                                                    <span class="sidebar-menu-icon">
                                                        <div class="circle"></div>
                                                    </span>
                                                    <span class="sidebar-menu-text">{{ $sm->name }}</span>
                                                    <span class="sidebar-menu-arrow">
                                                        @svg('tabler-chevron-right')
                                                    </span>
                                                </a>
                                                <ul @class(["sidebar-submenu", "open" => str_starts_with(request()->path(), $sm->url)])>
                                                    @foreach ($sm->subMenus as $ssm)
                                                        @can('read '. $ssm->url)
                                                        <li>
                                                            <a href="{{ url($ssm->url) }}" @class([
                                                                'sidebar-submenu-item',
                                                                'active' => str_starts_with(request()->path(), $ssm->url),
                                                            ])> {{ $ssm->name }} </a>
                                                        </li>
                                                        @endcan
                                                    @endforeach
                                                </ul>
                                            </li>
                                        @else
                                            <li>
                                                <a href="{{ url($sm->url) }}" @class([
                                                    'sidebar-submenu-item',
                                                    'active' => str_starts_with(request()->path(), $sm->url),
                                                ])> {{ $sm->name }} </a>
                                            </li>
                                        @endif
                                    @endcan
                                @endforeach
                            </ul>
                        @else
                            <a href="{{ url($mm->url) }}" @class(['sidebar-menu', 'active' => str_contains(request()->path(), $mm->url)])>
                                <span class="sidebar-menu-icon">
                                    @svg($mm->icon)
                                </span>
                                <span class="sidebar-menu-text">{{ $mm->name }}</span>
                            </a>
                        @endif
                    </li>
                @endcan
            @endforeach
        @endforeach
    </ul>
    <!-- Sidebar Menu Ends -->
</aside>
