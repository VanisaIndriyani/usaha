<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-50">
        <div class="relative min-h-screen flex flex-col justify-center items-center py-12 sm:px-6 lg:px-8">
            <div class="absolute inset-0 flex items-center justify-center overflow-hidden z-0 pointer-events-none">
                <div class="w-[800px] h-[800px] bg-gradient-to-br from-brand-blue/10 to-brand-gold/10 rounded-full blur-3xl mix-blend-multiply"></div>
                <div class="w-[600px] h-[600px] bg-gradient-to-tr from-brand-navy/10 to-transparent rounded-full blur-3xl mix-blend-multiply -ml-[200px]"></div>
            </div>

            <div class="relative z-10 w-full max-w-md">
                <div class="mb-8 text-center">
                    <a href="/" class="inline-flex flex-col items-center justify-center gap-4 group">
                        <span class="inline-flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-brand-navy to-brand-blue text-white shadow-xl shadow-brand-navy/30 ring-4 ring-white transition-transform duration-300 group-hover:scale-105">
                            <span class="text-3xl font-extrabold text-brand-gold">U</span>
                        </span>
                        <div>
                            <span class="text-3xl font-extrabold tracking-tight text-brand-navy">{{ config('app.name', 'Usaha Baraya') }}</span>
                            <div class="mt-2 text-sm font-medium text-black/50">Premium Business Management</div>
                        </div>
                    </a>
                </div>

                <div class="bg-white/80 backdrop-blur-xl border border-white shadow-2xl sm:rounded-3xl p-8 sm:p-10 relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-brand-navy via-brand-blue to-brand-gold"></div>
                    {{ $slot }}
                </div>

                <div class="mt-8 text-center text-sm text-black/45">
                    &copy; {{ date('Y') }} {{ config('app.name', 'Usaha Baraya') }}. All rights reserved.
                </div>
            </div>
        </div>
    </body>
</html>
