@php
    $isOwner = auth()->user()?->role === 'owner';
    $nav = [
        ['label' => 'Dashboard', 'route' => 'dashboard', 'icon' => 'home'],
        ['label' => 'Modal Usaha', 'route' => 'modal-usaha.index', 'icon' => 'wallet'],
        ['label' => 'Barang Usaha', 'route' => 'barang-usaha.index', 'icon' => 'box'],
        ['label' => 'Catatan Stok', 'route' => 'catatan-stok.index', 'icon' => 'note'],
        ['label' => 'Pemasukan', 'route' => 'pemasukan.index', 'icon' => 'trending-up'],
        ['label' => 'Pengeluaran', 'route' => 'pengeluaran.index', 'icon' => 'trending-down'],
        ['label' => 'Karyawan', 'route' => 'karyawan.index', 'icon' => 'users'],
        ['label' => 'Gaji', 'route' => 'gaji.index', 'icon' => 'receipt'],
        ['label' => 'Profit Sharing', 'route' => 'profit-sharing.index', 'icon' => 'pie'],
        ['label' => 'Laporan', 'route' => 'laporan.index', 'icon' => 'chart'],
        ['label' => 'Aktivitas', 'route' => 'activity-logs.index', 'icon' => 'activity'],
    ];

    $items = $nav;

    $icon = fn (string $name) => match ($name) {
        'home' => '<path d="M3 10.5 12 3l9 7.5V21a.75.75 0 0 1-.75.75H14.25A.75.75 0 0 1 13.5 21v-5.25a.75.75 0 0 0-.75-.75h-1.5a.75.75 0 0 0-.75.75V21a.75.75 0 0 1-.75.75H3.75A.75.75 0 0 1 3 21V10.5Z" />',
        'wallet' => '<path d="M2.25 7.5A3.75 3.75 0 0 1 6 3.75h12A1.5 1.5 0 0 1 19.5 5.25V6h-12A2.25 2.25 0 0 0 5.25 8.25v7.5A2.25 2.25 0 0 0 7.5 18h12v.75A1.5 1.5 0 0 1 18 20.25H6A3.75 3.75 0 0 1 2.25 16.5v-9Z" /><path d="M5.25 8.25A.75.75 0 0 1 6 7.5h13.5a.75.75 0 0 1 .75.75v7.5a.75.75 0 0 1-.75.75H6a.75.75 0 0 1-.75-.75v-7.5ZM16.5 13.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Z" />',
        'box' => '<path d="M2.25 6.75 12 2.25l9.75 4.5L12 11.25 2.25 6.75Z" /><path d="M21.75 7.5v9.75A2.25 2.25 0 0 1 20.25 19.4l-7.5 2.85v-9.9l9-4.85Z" /><path d="M2.25 7.5l9 4.85v9.9l-7.5-2.85A2.25 2.25 0 0 1 2.25 17.25V7.5Z" />',
        'trending-up' => '<path d="M3.75 15.75a.75.75 0 0 1 .75-.75h3.19l2.72-3.63a.75.75 0 0 1 1.2.02l2 2.86 3.26-4.88h-1.62a.75.75 0 0 1 0-1.5h3.75a.75.75 0 0 1 .75.75v3.75a.75.75 0 0 1-1.5 0V9.44l-3.98 5.96a.75.75 0 0 1-1.22.02l-2.03-2.9-2.2 2.93a.75.75 0 0 1-.6.3H4.5a.75.75 0 0 1-.75-.75Z" />',
        'trending-down' => '<path d="M3.75 8.25A.75.75 0 0 1 4.5 9h3.19l2.72 3.63a.75.75 0 0 0 1.2-.02l2-2.86 3.26 4.88h-1.62a.75.75 0 0 0 0 1.5h3.75a.75.75 0 0 0 .75-.75v-3.75a.75.75 0 0 0-1.5 0v1.93l-3.98-5.96a.75.75 0 0 0-1.22-.02l-2.03 2.9-2.2-2.93a.75.75 0 0 0-.6-.3H4.5a.75.75 0 0 0-.75.75Z" />',
        'users' => '<path d="M16.5 7.5a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0Z" /><path d="M3 20.25a8.25 8.25 0 0 1 16.5 0 .75.75 0 0 1-.75.75H3.75a.75.75 0 0 1-.75-.75Z" />',
        'receipt' => '<path d="M5.25 2.25h13.5A1.5 1.5 0 0 1 20.25 3.75V21l-2.25-1.5L15.75 21l-2.25-1.5L11.25 21 9 19.5 6.75 21 4.5 19.5 2.25 21V3.75A1.5 1.5 0 0 1 3.75 2.25h1.5Z" /><path d="M6.75 6.75a.75.75 0 0 1 .75-.75h9a.75.75 0 0 1 0 1.5h-9a.75.75 0 0 1-.75-.75ZM6.75 10.5a.75.75 0 0 1 .75-.75h9a.75.75 0 0 1 0 1.5h-9a.75.75 0 0 1-.75-.75ZM6.75 14.25a.75.75 0 0 1 .75-.75h6a.75.75 0 0 1 0 1.5h-6a.75.75 0 0 1-.75-.75Z" />',
        'note' => '<path d="M6.75 2.25h7.5L18.75 6.75v14.25a1.5 1.5 0 0 1-1.5 1.5H6.75a1.5 1.5 0 0 1-1.5-1.5V3.75a1.5 1.5 0 0 1 1.5-1.5Z" /><path d="M14.25 2.25V6a.75.75 0 0 0 .75.75h3.75" /><path d="M7.5 10.5h9M7.5 14.25h9M7.5 18h6" />',
        'pie' => '<path d="M11.25 2.25a.75.75 0 0 1 .75.75v8.25H20.25a.75.75 0 0 1 .75.75 9.75 9.75 0 1 1-9.75-9.75Z" /><path d="M13.5 3.06A9.76 9.76 0 0 1 20.94 10.5H13.5V3.06Z" />',
        'chart' => '<path d="M3 3.75A.75.75 0 0 1 3.75 3h16.5a.75.75 0 0 1 0 1.5H4.5v15.75a.75.75 0 0 1-1.5 0V3.75Z" /><path d="M7.5 18a.75.75 0 0 1-.75-.75V12a.75.75 0 0 1 1.5 0v5.25A.75.75 0 0 1 7.5 18ZM12 18a.75.75 0 0 1-.75-.75V9.75a.75.75 0 0 1 1.5 0v7.5A.75.75 0 0 1 12 18ZM16.5 18a.75.75 0 0 1-.75-.75V6.75a.75.75 0 0 1 1.5 0v10.5A.75.75 0 0 1 16.5 18Z" />',
        'activity' => '<path d="M3.75 12a.75.75 0 0 1 .75-.75h2.25l1.8-5.4a.75.75 0 0 1 1.44.03l2.4 8 1.41-3.52a.75.75 0 0 1 .7-.46h2.7a.75.75 0 0 1 0 1.5h-2.19l-2.01 5a.75.75 0 0 1-1.4-.05l-2.36-7.86-1.17 3.51a.75.75 0 0 1-.71.52H4.5a.75.75 0 0 1-.75-.75Z" />',
        default => '<path d="M4.5 6.75h15M4.5 12h15M4.5 17.25h15" />',
    };
@endphp

<div class="relative z-40 lg:hidden" x-show="sidebarOpen" x-cloak>
    <div class="fixed inset-0 bg-black/30 backdrop-blur-sm" @click="sidebarOpen=false"></div>
    <div class="fixed inset-y-0 left-0 w-80 max-w-[85%] p-4">
        <div class="glass-card h-full overflow-hidden">
            <div class="card-header">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-gradient-to-br from-brand-blue to-brand-navy text-white shadow-lg shadow-brand-blue/25">
                        <span class="text-base font-extrabold">U</span>
                    </span>
                    <div class="leading-tight">
                        <div class="text-sm font-bold text-brand-navy dark:text-white">{{ config('app.name', 'Usaha Manager') }}</div>
                        <div class="text-xs text-black/45 dark:text-white/55">Premium Business Suite</div>
                    </div>
                </a>
                <button type="button" class="btn-ghost px-3 py-2" @click="sidebarOpen=false">Tutup</button>
            </div>
            <div class="card-body">
                <div class="space-y-1.5">
                    @foreach ($items as $item)
                        <a href="{{ route($item['route']) }}"
                           class="group flex items-center gap-3 rounded-xl px-3 py-2 text-sm font-semibold transition {{ request()->routeIs($item['route']) ? 'bg-brand-blue text-white shadow-lg shadow-brand-blue/20' : 'text-brand-navy hover:bg-white/80 dark:text-white dark:hover:bg-white/10' }}">
                            <svg class="h-5 w-5 {{ request()->routeIs($item['route']) ? 'text-white' : 'text-brand-blue/80 group-hover:text-brand-blue dark:text-white/70' }}" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">{!! $icon($item['icon']) !!}</svg>
                            <span>{{ $item['label'] }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<aside class="hidden lg:fixed lg:inset-y-0 lg:left-0 lg:z-30 lg:flex lg:w-72 lg:flex-col lg:p-5">
    <div class="glass-card flex h-full flex-col overflow-hidden">
        <div class="card-header">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-gradient-to-br from-brand-blue to-brand-navy text-white shadow-lg shadow-brand-blue/25">
                    <span class="text-base font-extrabold">U</span>
                </span>
                <div class="leading-tight">
                    <div class="text-sm font-bold text-brand-navy dark:text-white">{{ config('app.name', 'Usaha Manager') }}</div>
                    <div class="text-xs text-black/45 dark:text-white/55">Premium Business Suite</div>
                </div>
            </a>
        </div>

        <div class="card-body flex-1">
            <div class="space-y-1.5">
                @foreach ($items as $item)
                    <a href="{{ route($item['route']) }}"
                       class="group flex items-center gap-3 rounded-xl px-3 py-2 text-sm font-semibold transition {{ request()->routeIs($item['route']) ? 'bg-brand-blue text-white shadow-lg shadow-brand-blue/20' : 'text-brand-navy hover:bg-white/80 dark:text-white dark:hover:bg-white/10' }}">
                        <svg class="h-5 w-5 {{ request()->routeIs($item['route']) ? 'text-white' : 'text-brand-blue/80 group-hover:text-brand-blue dark:text-white/70' }}" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">{!! $icon($item['icon']) !!}</svg>
                        <span>{{ $item['label'] }}</span>
                    </a>
                @endforeach
            </div>
        </div>

        <div class="border-t border-black/5 p-4 dark:border-white/10">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 shrink-0 overflow-hidden rounded-2xl bg-brand-gray/60 ring-1 ring-black/5 dark:bg-white/10 dark:ring-white/10">
                    @if (auth()->user()?->photo_path)
                        <img src="{{ asset('storage/' . auth()->user()->photo_path) }}" alt="{{ auth()->user()->name }}" class="h-full w-full object-cover">
                    @endif
                </div>
                <div class="min-w-0 flex-1">
                    <div class="truncate text-sm font-bold text-brand-navy dark:text-white">{{ auth()->user()->name }}</div>
                    <div class="mt-0.5 flex items-center gap-2">
                        <span class="truncate text-xs text-black/45 dark:text-white/55">{{ auth()->user()->email }}</span>
                        <span class="badge-gold">{{ $isOwner ? 'Owner' : 'Karyawan' }}</span>
                    </div>
                </div>
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="btn-ghost px-3 py-2">Menu</button>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            Profile
                        </x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                Log out
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
        </div>
    </div>
</aside>

<header class="sticky top-0 z-20 lg:pl-72">
    <div class="mx-auto max-w-7xl px-4 pt-4 sm:px-6 lg:px-8">
        <div class="glass-card">
            <div class="card-body flex items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <button type="button" class="btn-ghost px-3 py-2 lg:hidden" @click="sidebarOpen=true">Menu</button>
                    <div>
                        <div class="text-sm font-semibold text-black/55 dark:text-white/60">Selamat datang</div>
                        <div class="text-lg font-bold tracking-tight text-brand-navy dark:text-white">
                            {{ $isOwner ? 'Owner Console' : 'Karyawan View' }}
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <a href="{{ route('profile.edit') }}" class="btn-ghost">Profile</a>
                </div>
            </div>
        </div>
    </div>
</header>
