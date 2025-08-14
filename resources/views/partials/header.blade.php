<!-- Header Starts -->
<header
    class="relative z-50 flex items-center w-full h-16 mt-2 overflow-visible rounded-md shadow-sm drop-shadow-sm bg-card">
    <div class="flex items-center justify-between container-fluid">
        <!-- Sidebar Toggle & Search Starts -->
        <div class="flex items-center space-x-6">
            <button class="sidebar-toggle">
                <span class="flex space-x-4">
                    <svg stroke="currentColor" fill="none" stroke-width="0" viewBox="0 0 24 24" height="22"
                        width="22" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h8m-8 6h16"></path>
                    </svg>
                </span>
            </button>

            <!-- Mobile Search Starts -->
            <div class="sm:hidden">
                <button type="button"
                    class="flex items-center transition-colors duration-150 rounded-full search-modal text-dark-500 hover:text-primary-500 focus:text-primary-500 dark:text-dark-400 dark:hover:text-dark-300">
                    @svg('tabler-search')
                </button>
            </div>
            <!-- Mobile Search Ends -->

            <!-- Searchbar Start -->
            <button type="button"
                class="items-center justify-between hidden h-10 px-3 overflow-hidden shadow-sm search-modal group w-72 rounded-primary bg-dark-100 dark:border-transparent dark:bg-dark-800 sm:flex">
                <div class="flex items-center">
                    @svg('tabler-search', 'w-5 h-5 text-dark-400')
                    <span class="flex ml-2 text-sm text-dark-400">{{ __('larascaff::layout.search') }} <span></span></span>
                </div>
                <div class="text-xs text-dark-400"><span class="border rounded-md px-1 py-0.5">Ctrl</span> + <span
                        class="border inline-flex w-6 justify-center rounded-md py-0.5">K</span></div>
            </button>
            <!-- Searchbar Ends -->
        </div>
        <!-- Sidebar Toggle & Search Ends -->

        <!-- Header Options Starts -->
        <div class="flex items-center">
            <!-- Dark Mode Toggle Starts -->
            <button data-dropdown-toggle="dropdown_theme" data-dropdown-placement="bottom-end"
                class="mx-3 dropdown-toggle text-muted-foreground hover:text-primary" type="button">
                @svg('tabler-moon', 'hidden dark:block w-5 h-5')
                @svg('tabler-sun', 'block dark:hidden w-5 h-5')
            </button>
            <x-larascaff::dropdown id="dropdown_theme">
                <x-larascaff::dropdown-link data-theme-mode="light">
                    <button type="buttton" class="flex items-center w-full gap-x-2">
                        @svg('tabler-sun', 'w-5 h-5')
                        <span>Light</span>
                    </button>
                </x-larascaff::dropdown-link>
                <x-larascaff::dropdown-link data-theme-mode="dark">
                    <button type="buttton" class="flex items-center w-full gap-x-2">
                        @svg('tabler-moon', 'w-5 h-5')
                        <span>Dark</span>
                    </button>
                </x-larascaff::dropdown-link>
                <x-larascaff::dropdown-link data-theme-mode="system">
                    <button type="buttton" class="flex items-center w-full gap-x-2">
                        @svg('tabler-device-desktop', 'w-5 h-5')
                        <span>System</span>
                    </button>
                </x-larascaff::dropdown-link>
            </x-larascaff::dropdown>
            <!-- Dark Mode Toggle Ends -->

            <!-- Notification Dropdown Starts -->
            @php
                $notifications = user()->notifications
            @endphp
            <button data-dropdown-toggle data-dropdown-placement="bottom-end"
                class="relative flex items-center justify-center mt-1 transition-colors duration-150 rounded-full focus:outline-none text-muted-foreground hover:text-primary">
                @svg('tabler-bell', 'w-5 h-5')
                <span
                    class="absolute -right-1 -top-1.5 flex h-4 w-4 text-white items-center justify-center rounded-full bg-danger-500 text-[11px]">{{ $notifications->count() }}</span>
            </button>
            <x-larascaff::dropdown
                class="z-50 hidden w-full border divide-y rounded-lg shadow-lg border-border bg-card dark:bg-dark-950 md:w-80">
                <div class="flex items-center justify-between px-4 py-4 -mt-2 border-b dark:border-b-dark-800/70">
                    <h6 class="">{{ __('larascaff::notification.title') }}</h6>
                    <button class="text-xs font-medium hover:text-primary-500 focus:text-primary">
                        {{ __('larascaff::notification.clear') }}
                    </button>
                </div>
                <div class="w-full h-80" data-simplebar>
                    <ul class="divide-y dark:divide-dark-800/70 last:border-b dark:border-dark-800/70">
                        @foreach ($notifications as $notif)
                            <li class="dropdown-item">
                                <a href="{{ url('notifications/'.$notif->id) }}" class="flex w-full gap-4 px-2 py-3 transition-colors duration-150 cursor-pointer hover:bg-dark-50 dark:hover:bg-dark-900/80">
                                    <div class="flex items-center justify-center flex-shrink-0 w-8 h-8 rounded-full text-warning-600 bg-warning/30">
                                        @svg('tabler-alert-circle', 'w-5 h-5')
                                    </div>
                                    <div class="w-full">
                                        <h6 class="text-sm font-normal">{{ $notif->data['title'] }}</h6>
                                        <p class="mt-1 text-xs text-dark-400 line-clamp-2">{{ $notif->data['message'] }}</p>
                                        <div class="flex justify-end w-full gap-1 mt-1 text-dark-400">
                                            <div>
                                                <div class="flex items-center text-xs italic gap-x-2">
                                                    @svg('tabler-clock', 'w-4 h-4')
                                                    <span>{{ $notif['created_at']->diffForHumans() }}</span>
                                                </div>
                                                @if (isset($notif->data['sender']))
                                                    <div class="flex items-center justify-end text-xs text-foreground">{{ __('larascaff::notification.from') }} {{ $notif->data['sender'] }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="flex px-4 py-2 text-xs border-t dark:border-t-dark-800/70">
                    <button class="flex items-center justify-center w-full gap-2 mt-2 hover:text-primary btn btn-primary-plain btn-sm"
                        type="button">
                        <span>{{ __('larascaff::notification.more') }}</span>
                        @svg('tabler-arrow-right', 'w-4 h-4')
                    </button>
                </div>
            </x-larascaff::dropdown>
            <!-- Notification Dropdown Ends -->

            @php
                $hasAvatar = user() instanceof \Mulaidarinull\Larascaff\Models\Contracts\HasAvatar;
                $config = larascaffConfig();
                $profileUrl = $config->hasProfile() ? url(getPrefix() . '/profile') : null;
            @endphp
            <!-- Profile Dropdown Starts -->
            <button data-dropdown-toggle="dropdown_user" data-dropdown-placement="bottom-end"
                class="group ml-3 relative flex items-center gap-x-1.5" type="button">
                <div class="avatar avatar-circle avatar-indicator avatar-indicator-online">
                    <img class="w-8 h-8 rounded-full" src="{{ $hasAvatar ? user()->getAvatar() : 'https://ui-avatars.com/api/?name='.user('name') }}" alt="Avatar 1" />
                </div>
            </button>
            <x-larascaff::dropdown id="dropdown_user" class="w-56 ">
                <div class="divide-y">
                    <div class="px-4 py-3">
                        <p class="text-sm">{{ __('larascaff::layout.sign_as')  }}</p>
                        <p class="text-sm font-medium truncate">{{ auth()->user()->email }}</p>
                    </div>
                    <div class="py-2">
                        <x-larascaff::dropdown-link :href=$profileUrl >
                            <div class="flex items-center gap-x-2">
                                @svg('tabler-user', 'w-5 h-5 text-dark-500')
                                <span>{{ __('larascaff::auth/edit-profile.label') }}</span>
                            </div>
                        </x-larascaff::dropdown-link>
                        {{-- <x-larascaff::dropdown-link>
                            <div class="flex items-center gap-x-2">
                                @svg('tabler-settings', 'w-5 h-5 text-dark-500')
                                <span>Settings</span>
                            </div>
                        </x-larascaff::dropdown-link>
                        <x-larascaff::dropdown-link>
                            <div class="flex items-center gap-x-2">
                                    @svg('tabler-help-circle', 'w-5 h-5 text-dark-500')
                                <span>Support</span>
                            </div>
                        </x-larascaff::dropdown-link> --}}
                    </div>
                    <div class="pt-2">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-larascaff::dropdown-link>
                                <button type="submit" class="flex items-center w-full gap-x-2">
                                        @svg('tabler-logout', 'w-5 h-5 text-dark-500')
                                    <span>{{ __('larascaff::layout.actions.logout.label') }}</span>
                                </button>
                            </x-larascaff::dropdown-link>
                        </form>
                    </div>
                </div>

            </x-larascaff::dropdown>

            <!-- Profile Dropdown Ends -->
        </div>
        <!-- Header Options Ends -->
    </div>
    <!-- SearchBox Trigger -->

</header>
<!-- Header Ends -->

<x-larascaff::modal id="header-search-modal" >
    <div data-modal-content
        class="relative ml-[50%] max-w-2xl -translate-x-1/2 my-6 transition-all duration-500 scale-90 bg-white rounded-lg shadow opacity-0 dark:bg-dark-900 w-full">
        <!-- Modal body -->
        <div class="relative p-4">
            @svg('tabler-search',"absolute -translate-y-1/2 text-dark-400 left-6 top-1/2 ")
            <input placeholder="Search" id="searching"
                class="w-full py-3 pl-10 pr-16 text-sm bg-transparent border rounded-md disabled:cursor-not-allowed read-only:dark:bg-dark-800 read-only:bg-dark-100 border-border focus-visible:ring-2 focus-visible:outline-none focus-visible:ring-primary focus-visible:ring-offset-white placeholder:text-muted-foreground dark:focus-visible:ring-offset-dark-900 focus-visible:ring-offset-2" />
            <button data-modal-hide
                class="absolute -translate-y-1/2 border rounded-full px-2 py-0.5 text-xs right-8 top-1/2">Esc</button>
        </div>
        <div id="list-search" class="px-4 pb-4 text-sm">
            <p class="mt-1 mb-3 text-muted-foreground">List of Content</p>
            <ul class="flex flex-col gap-y-1">
            @foreach (menus() as $category => $menus)
                @php
                    $showCategory = true;
                @endphp
                @foreach ($menus as $mm)
                    @can('read '. $mm->url)
                        @if ($showCategory && $category != '')
                            <div class="py-2 font-semibold">{{ $category }}</div>
                            @php
                                $showCategory = false;
                            @endphp
                        @endif
                        @if (count($mm->subMenus))
                            <li>
                                <a href="javascript:void(0);" class="flex items-center data-[active=true]:dark:bg-dark-800 data-[active=true]:bg-dark-50 px-3 py-2 transition rounded-md outline-none gap-x-2 hover:bg-dark-50 hover:dark:bg-dark-800">
                                    @svg($mm->icon, 'w-5 h-5')
                                    {{ $mm->name }}
                                </a>
                            </li>
                            @foreach ($mm->subMenus as $sm)
                                <li class="ml-7">
                                    <a href="{{ count($sm->subMenus) ? 'javascript:void(0);' : url($sm->url) }}" class="flex items-center data-[active=true]:dark:bg-dark-800 data-[active=true]:bg-dark-50 px-3 py-2 transition rounded-md outline-none gap-x-2 hover:bg-dark-50 hover:dark:bg-dark-800">
                                        @if ($sm->icon)
                                            @svg($sm->icon, 'w-5 h-5')
                                        @endif
                                        {{ $sm->name }}
                                    </a>
                                </li>
                                @foreach ($sm->subMenus as $ssm)
                                <li class="ml-10">
                                    <a href="{{ count($ssm->subMenus) ? 'javascript:void(0);' : url($ssm->url) }}" class="flex items-center data-[active=true]:dark:bg-dark-800 data-[active=true]:bg-dark-50 px-3 py-2 transition rounded-md outline-none gap-x-2 hover:bg-dark-50 hover:dark:bg-dark-800">
                                        @if ($ssm->icon)
                                            @svg($ssm->icon, 'w-5 h-5')
                                        @endif
                                        {{ $ssm->name }}
                                    </a>
                                </li>
                                @endforeach
                            @endforeach
                        @else
                        <li>
                            <a href="{{ count($mm->subMenus) ? 'javascript:void(0);' : url($mm->url) }}" class="flex items-center data-[active=true]:dark:bg-dark-800 data-[active=true]:bg-dark-50 px-3 py-2 transition rounded-md outline-none gap-x-2 hover:bg-dark-50 hover:dark:bg-dark-800">
                                @svg($mm->icon, 'w-5 h-5')
                                {{ $mm->name }}
                            </a>
                        </li>
                        @endif
                    @endcan
                @endforeach
            @endforeach
            </ul>
        </div>
    </div>
</x-larascaff::modal>
