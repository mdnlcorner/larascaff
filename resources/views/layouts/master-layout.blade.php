<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    @php
        $config = app(\Mulaidarinull\Larascaff\LarascaffConfig::class);
    @endphp
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
        <meta name="csrf_token" content="{{ csrf_token() }}">
        <title>{{ ucwords(last(str_replace('-', ' ', request()->segments())) ?: 'Dashboard') . ' - ' . config('app.name', 'Laravel') }}</title>
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;1,200;1,300;1,400;1,500;1,600;1,700&display=swap" />
        @if ($config->getFavicon())
            <link rel="icon" href="{{ $config->getFavicon() }} ">
        @endif
        <!-- Scripts -->
        <script>
            // On page load or when changing themes, best to add inline in `head` to avoid FOUC
            if (localStorage.getItem('color-theme') === 'dark' || (localStorage.getItem('color-theme') === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else if (localStorage.getItem('color-theme') === 'light') {
                document.documentElement.classList.remove('dark')
            }
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
                if (localStorage.getItem('color-theme') === 'system' || !localStorage.getItem('color-theme')) {
                    if (e.matches) {
                        document.documentElement.classList.add('dark')
                        document.querySelector('#darkMode').classList.remove('hidden')
                        document.querySelector('#lightMode').classList.add('hidden')
                    } else {
                        document.documentElement.classList.remove('dark')
                        document.querySelector('#darkMode').classList.add('hidden')
                        document.querySelector('#lightMode').classList.remove('hidden')
                    }
                }
            })
        </script>
        <script type="module" src="{{ asset('larascaff/vendor/alpine.js') }}"></script>
        <script type="module" src="{{ asset('larascaff/js/bootstrap.js') }}" ></script>
        
        @larascaffStyles
        
        @vite(['resources/css/app.css','resources/js/app.js'])
        @stack('jsModule')
        @stack('css')
    </head>
    <body class="font-sans antialiased">
        <div id="app">
            <div class="fixed top-0 z-10 hidden w-full h-20 pointer-events-none shadow-header bg-gradient-to-b from-white to-transparent dark:from-dark-900 dark:to-transparent"></div>
            @include('larascaff::partials.sidebar')
            <div class="wrapper">
                <div class="px-3 md:px-4 bg-slate-50 dark:bg-background min-h-[calc(100vh-60px)]">
                    @include('larascaff::partials.header')
                    <div class="py-6 main-content">
                        {!! $slot !!}
                    </div>
                </div>
                <footer class="w-full bg-card">
                    <div class="px-4 py-5 text-sm bottom-1">
                        {{ is_callable($config->getFooter()) ? $config->getFooter()() : $config->getFooter() }}
                    </div>
                </footer>
            </div>
        </div>
        @stack('js')
    </body>
</html>
