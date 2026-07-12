<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
        <style>[x-cloak]{display:none !important;}</style>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div
            x-data="{
                sidebarOpen: false,
                sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true',
                toggleSidebar() {
                    this.sidebarCollapsed = !this.sidebarCollapsed;
                    localStorage.setItem('sidebarCollapsed', this.sidebarCollapsed ? 'true' : 'false');
                }
            }"
            class="app-shell"
        >
            @include('layouts.navigation')

            <div :class="sidebarCollapsed ? 'lg:pl-24' : 'lg:pl-72'" class="transition-all duration-300">
                @isset($header)
                    <div class="mx-auto max-w-7xl px-4 pt-6 sm:px-6 lg:px-8">
                        <div class="glass-card">
                            <div class="card-body">
                                {{ $header }}
                            </div>
                        </div>
                    </div>
                @endisset

                <main class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                    {{ $slot }}
                </main>
            </div>
        </div>

        @if (session('toast'))
            <script>
                document.addEventListener('alpine:init', () => {
                    const t = @json(session('toast'));
                    window.Alpine.store('ui').toast(t.message, t.type);
                });
            </script>
        @endif
    </body>
</html>
