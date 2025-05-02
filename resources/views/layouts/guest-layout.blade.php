@props(['title' => config('app.name')])
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ __($title) }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;1,200;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet"
        />
    <script>
        // On page load or when changing themes, best to add inline in `head` to avoid FOUC
        if (localStorage.getItem('color-theme') === 'dark' || (localStorage.getItem('color-theme') === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else if (localStorage.getItem('color-theme') === 'light') {
            document.documentElement.classList.remove('dark')
        }
    </script>

    @larascaffStyles
    @vite(['resources/css/app.css'])
</head>
<body class="font-sans antialiased">
    <div class="flex flex-col items-center justify-center w-full min-h-screen p-4 lg:p-6 bg-dark-50 dark:bg-dark-950">
        <div class="flex flex-col w-full max-w-md px-6 py-6 bg-white rounded-md shadow-md dark:bg-dark-900 lg:px-8">
            {{ $slot }}
        </div>
    </div>
</body>
</html>