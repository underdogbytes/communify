<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

       <title>@yield('title', config('app.name', 'Communify'))</title>

        <meta name="description" content="@yield('description', 'A plataforma para criadores de conteúdo e comunidades.')">
        
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:title" content="@yield('og:title', config('app.name'))">
        <meta property="og:description" content="@yield('og:description', 'Crie, conecte e monetize sua comunidade.')">
        <meta property="og:image" content="@yield('og:image', asset('images/default-share.jpg'))">

        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="@yield('og:title', config('app.name'))">
        <meta name="twitter:description" content="@yield('og:description')">
        <meta name="twitter:image" content="@yield('og:image', asset('images/default-share.jpg'))">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <script src="https://cdn.tailwindcss.com"></script>
        <script src="//unpkg.com/alpinejs" defer></script>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>