<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <link rel="stylesheet" href="{{ asset('css/arc-raiders-override.css') }}">
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    </head>
    <body class="font-sans antialiased" style="background: linear-gradient(135deg, #0a0e14 0%, #0f1419 50%, #1a2332 100%); min-height: 100vh;">
        <div class="min-h-screen">
            @include('layouts.navigation')

            <div class="flex">
                <!-- Sidebar conditionnelle (seulement dans les projets) -->
                @if(isset($attributes['project']))
                    @include('layouts.project-sidebar', ['project' => $attributes['project']])
                @endif

                <!-- Contenu principal -->
                <div class="flex-1">
                    <!-- Page Heading -->
                    @isset($header)
                        <header style="background: linear-gradient(135deg, rgba(31, 41, 55, 0.7) 0%, rgba(17, 24, 39, 0.85) 100%); border-bottom: 1px solid rgba(34, 211, 238, 0.2);">
                            <div class="max-w-full mx-auto py-6 px-4 sm:px-6 lg:px-8">
                                {{ $header }}
                            </div>
                        </header>
                    @endisset

                    <!-- Page Content -->
                    <main>
                        {{ $slot }}
                    </main>
                </div>
            </div>
        </div>
    </body>
</html>