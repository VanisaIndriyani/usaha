<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Login - {{ config('app.name', 'Usaha Baraya') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen overflow-x-hidden bg-gradient-to-br from-white via-slate-50 to-amber-50 font-sans text-slate-900">
        @php
            $idr = fn (int $value) => 'Rp ' . number_format($value, 0, ',', '.');
            $summary = $publicData['summary'];
            $charts = $publicData['charts'];
            $entries = $publicData['latestEntries'];
        @endphp

        <div class="relative min-h-screen">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(30,58,138,0.08),_transparent_30%),radial-gradient(circle_at_bottom_right,_rgba(250,204,21,0.14),_transparent_24%)]"></div>

            <div class="relative mx-auto grid min-h-screen max-w-7xl gap-6 px-4 py-6 sm:px-6 lg:grid-cols-[1.1fr_0.9fr] lg:px-8">
                <section class="flex flex-col rounded-[32px] border border-slate-200 bg-white/95 p-6 shadow-xl shadow-slate-200/70 backdrop-blur-2xl sm:p-8">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <a href="{{ route('home') }}" class="inline-flex items-center gap-3">
                            <span class="inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-brand-navy to-brand-blue text-2xl font-extrabold text-brand-gold shadow-lg shadow-brand-navy/30">U</span>
                            <div>
                                <div class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-400">Admin</div>
                                <div class="mt-1 text-2xl font-extrabold tracking-tight text-brand-navy">{{ config('app.name', 'Usaha Baraya') }}</div>
                            </div>
                        </a>
                        <span class="rounded-full border border-amber-200 bg-amber-50 px-4 py-2 text-xs font-semibold uppercase tracking-[0.24em] text-amber-600">
                            Tahun {{ $publicData['year'] }}
                        </span>
                    </div>

                    <div class="mt-8">
                        <div class="text-sm font-semibold text-slate-400">Login</div>
                        <h1 class="mt-3 max-w-2xl text-4xl font-extrabold tracking-tight text-brand-navy sm:text-5xl">
                            Dashboard usaha.
                        </h1>
                        <p class="mt-4 max-w-xl text-sm leading-7 text-slate-500">
                            Ringkas, bersih, dan fokus ke data.
                        </p>
                    </div>

                    <div class="mt-8 grid gap-4 sm:grid-cols-3">
                        <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5">
                            <div class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Saldo</div>
                            <div class="mt-3 text-2xl font-extrabold text-brand-navy">{{ $idr($summary['saldoAkhir']) }}</div>
                        </div>
                        <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5">
                            <div class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Laba</div>
                            <div class="mt-3 text-2xl font-extrabold text-brand-navy">{{ $idr($summary['totalKeuntungan']) }}</div>
                        </div>
                        <div class="rounded-3xl border border-amber-200 bg-amber-50 p-5">
                            <div class="text-xs font-semibold uppercase tracking-[0.24em] text-amber-600">Karyawan</div>
                            <div class="mt-3 text-2xl font-extrabold text-brand-navy">{{ number_format($summary['jumlahKaryawan'], 0, ',', '.') }}</div>
                        </div>
                    </div>

                    <div class="mt-6 rounded-[28px] border border-slate-200 bg-white p-5">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <div class="text-sm font-semibold text-slate-400">Grafik</div>
                                <div class="mt-1 text-xl font-bold text-brand-navy">Masuk vs Keluar</div>
                            </div>
                            <a href="{{ route('home') }}" class="text-sm font-semibold text-amber-600 transition hover:text-amber-500">Landing</a>
                        </div>
                        <div id="loginOverviewChart" class="mt-5 h-72"></div>
                    </div>

                    <div class="mt-6 rounded-[28px] border border-slate-200 bg-white p-5">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <div class="text-sm font-semibold text-brand-navy">Data Terbaru</div>
                                <div class="text-xs text-slate-400">Update admin</div>
                            </div>
                            <span class="rounded-full border border-amber-200 bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-600">{{ count($entries) }} item</span>
                        </div>
                        <div class="mt-4 space-y-3">
                            @forelse (array_slice($entries, 0, 4) as $entry)
                                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-3">
                                    <div class="flex items-start justify-between gap-3">
                                        <div>
                                            <div class="text-sm font-semibold text-brand-navy">{{ $entry['nama'] }}</div>
                                            <div class="mt-1 text-xs text-slate-400">{{ $entry['tipe'] }} • {{ $entry['tanggal'] }}</div>
                                        </div>
                                        <div class="text-sm font-bold {{ $entry['tipe'] === 'Pemasukan' ? 'text-brand-navy' : 'text-amber-600' }}">
                                            {{ $idr($entry['nominal']) }}
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 p-4 text-sm text-slate-400">
                                    Belum ada data terbaru.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </section>

                <section class="flex items-center justify-center">
                    <div class="w-full max-w-xl rounded-[32px] border border-slate-200 bg-white p-6 shadow-xl shadow-slate-200/70 sm:p-8">
                        <div class="inline-flex rounded-full bg-amber-50 px-4 py-2 text-xs font-semibold uppercase tracking-[0.24em] text-amber-600">
                            Admin Login
                        </div>
                        <h2 class="mt-5 text-3xl font-extrabold tracking-tight text-slate-900">Masuk ke dashboard usaha</h2>
                        <p class="mt-3 text-sm leading-7 text-slate-500">Masuk untuk kelola data.</p>

                        <x-auth-session-status class="mt-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700" :status="session('status')" />

                        <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-5">
                            @csrf

                            <div>
                                <label for="email" class="text-sm font-semibold text-slate-700">Email</label>
                                <input
                                    id="email"
                                    name="email"
                                    type="email"
                                    value="{{ old('email') }}"
                                    required
                                    autofocus
                                    autocomplete="username"
                                    class="mt-2 block w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-brand-blue focus:bg-white focus:ring-4 focus:ring-brand-blue/10"
                                    placeholder="nama@email.com"
                                />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <div>
                                <div class="flex items-center justify-between gap-3">
                                    <label for="password" class="text-sm font-semibold text-slate-700">Password</label>
                                    @if (Route::has('password.request'))
                                        <a href="{{ route('password.request') }}" class="text-sm font-semibold text-brand-blue transition hover:text-brand-navy">
                                            Lupa password?
                                        </a>
                                    @endif
                                </div>
                                <input
                                    id="password"
                                    name="password"
                                    type="password"
                                    required
                                    autocomplete="current-password"
                                    class="mt-2 block w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-brand-blue focus:bg-white focus:ring-4 focus:ring-brand-blue/10"
                                    placeholder="Masukkan password"
                                />
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>

                            <label for="remember_me" class="flex cursor-pointer items-center justify-between gap-3 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                                <div>
                                    <div class="text-sm font-semibold text-slate-800">Ingat saya</div>
                                    <div class="text-xs text-slate-500">Tetap login di perangkat ini.</div>
                                </div>
                                <input id="remember_me" type="checkbox" name="remember" class="h-5 w-5 rounded border-slate-300 text-brand-blue focus:ring-brand-blue" {{ old('remember') ? 'checked' : '' }}>
                            </label>

                            <button type="submit" class="inline-flex w-full items-center justify-center rounded-2xl bg-gradient-to-r from-brand-navy via-brand-blue to-brand-navy px-5 py-4 text-sm font-bold uppercase tracking-[0.24em] text-white shadow-xl shadow-brand-blue/25 transition hover:-translate-y-0.5 hover:shadow-2xl hover:shadow-brand-blue/30">
                                Log in
                            </button>
                        </form>

                        <div class="mt-6 rounded-2xl border border-amber-200 bg-amber-50 p-4">
                            <div class="flex items-center justify-between gap-3">
                                <div>
                                    <div class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Preview Usaha</div>
                                    <div class="mt-1 text-lg font-bold text-slate-900">{{ $idr($summary['totalPemasukan']) }}</div>
                                </div>
                                <div class="text-right">
                                    <div class="text-xs text-slate-400">Total Pengeluaran</div>
                                    <div class="mt-1 text-sm font-semibold text-amber-600">{{ $idr($summary['totalPengeluaran']) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const months = @json($charts['months']);
                const income = @json($charts['income']);
                const expense = @json($charts['expense']);

                new ApexCharts(document.querySelector('#loginOverviewChart'), {
                    chart: {
                        type: 'area',
                        height: 288,
                        toolbar: { show: false },
                        fontFamily: 'Inter, ui-sans-serif, system-ui',
                    },
                    series: [
                        { name: 'Pemasukan', data: income },
                        { name: 'Pengeluaran', data: expense },
                    ],
                    colors: ['#1E3A8A', '#FACC15'],
                    stroke: { curve: 'smooth', width: 3 },
                    fill: { type: 'gradient', gradient: { opacityFrom: 0.38, opacityTo: 0.06 } },
                    dataLabels: { enabled: false },
                    grid: { borderColor: 'rgba(15,23,42,0.08)' },
                    theme: { mode: 'light' },
                    xaxis: {
                        categories: months,
                        labels: { style: { colors: '#64748b' } },
                    },
                    yaxis: {
                        labels: {
                            style: { colors: '#64748b' },
                            formatter: (value) => new Intl.NumberFormat('id-ID', { notation: 'compact', compactDisplay: 'short' }).format(value),
                        },
                    },
                    legend: { labels: { colors: '#1e293b' } },
                    tooltip: { y: { formatter: (value) => 'Rp ' + new Intl.NumberFormat('id-ID').format(value) } },
                }).render();
            });
        </script>
    </body>
</html>
