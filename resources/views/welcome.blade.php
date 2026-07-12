<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Usaha Baraya') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-amber-50 font-sans text-slate-900">
        @php
            $idr = fn (int $value) => 'Rp ' . number_format($value, 0, ',', '.');
            $summary = $publicData['summary'];
            $charts = $publicData['charts'];
            $entries = $publicData['latestEntries'];
        @endphp

        <div class="relative overflow-hidden">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(30,58,138,0.12),_transparent_35%),radial-gradient(circle_at_bottom_right,_rgba(250,204,21,0.2),_transparent_30%)]"></div>

            <div class="relative mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
                <header class="flex flex-col gap-4 rounded-[32px] border border-slate-200 bg-white/90 px-6 py-5 shadow-xl shadow-slate-200/40 backdrop-blur-2xl sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <div class="text-xs font-semibold uppercase tracking-[0.32em] text-slate-400">Overview</div>
                        <div class="mt-2 text-3xl font-extrabold tracking-tight text-brand-navy">{{ config('app.name', 'Usaha Baraya') }}</div>
                    </div>
                    <div class="flex flex-wrap items-center gap-3">
                        <form method="GET" action="{{ route('home') }}" class="flex items-center gap-2">
                            <select name="periode" class="input w-64 border-slate-200 bg-white text-brand-navy">
                                <option value="">Pilih Periode</option>
                                @foreach($publicData['periodes'] as $periode)
                                    <option value="{{ $periode->id }}" @selected(optional($publicData['selectedPeriode'])->id == $periode->id)>
                                        {{ $periode->nama }} ({{ $periode->tanggal_mulai->format('d/m/Y') }} - {{ $periode->tanggal_selesai->format('d/m/Y') }})
                                    </option>
                                @endforeach
                            </select>
                            <select name="year" class="input w-32 border-slate-200 bg-white text-brand-navy">
                                @for($y = now()->year - 5; $y <= now()->year + 1; $y++)
                                    <option value="{{ $y }}" @selected($y == $publicData['year'])>{{ $y }}</option>
                                @endfor
                            </select>
                            <button type="submit" class="btn-ghost border-slate-200 bg-white text-brand-navy">Terapkan</button>
                        </form>
                        <a href="{{ route('login') }}" class="btn-primary">Masuk Admin</a>
                    </div>
                </header>

                <section class="grid gap-6 pt-8 lg:grid-cols-[1.35fr_0.65fr]">
                    <div class="rounded-[36px] border border-slate-200 bg-white/95 p-8 shadow-[0_20px_60px_rgba(15,23,42,0.08) backdrop-blur-2xl sm:p-10">
                        <div class="inline-flex rounded-full border border-brand-gold/30 bg-brand-gold/10 px-4 py-1.5 text-xs font-semibold uppercase tracking-[0.28em] text-amber-700">
                            Data usaha dari input admin
                        </div>
                        <h1 class="mt-5 max-w-3xl text-4xl font-extrabold tracking-tight text-brand-navy sm:text-5xl">
                            Ringkasan usaha.
                        </h1>
                        <p class="mt-4 max-w-2xl text-sm leading-7 text-slate-500 sm:text-base">
                            Clean, singkat, dan langsung ke data utama.
                        </p>

                        <div class="mt-8 grid gap-5 md:grid-cols-2 xl:grid-cols-4">
                            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5 transition-all hover:-translate-y-1 hover:shadow-lg">
                                <div class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Modal</div>
                                <div class="mt-3 text-2xl font-extrabold text-brand-navy">{{ $idr($summary['totalModal']) }}</div>
                            </div>
                            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5 transition-all hover:-translate-y-1 hover:shadow-lg">
                                <div class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Masuk</div>
                                <div class="mt-3 text-2xl font-extrabold text-brand-navy">{{ $idr($summary['totalPemasukan']) }}</div>
                                <div class="mt-1 text-xs text-slate-400">Pemasukan kotor</div>
                            </div>
                            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5 transition-all hover:-translate-y-1 hover:shadow-lg">
                                <div class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Keluar</div>
                                <div class="mt-3 text-2xl font-extrabold text-brand-navy">{{ $idr($summary['totalPengeluaran']) }}</div>
                            </div>
                            <div class="rounded-3xl border border-amber-200 bg-gradient-to-br from-amber-50 to-amber-100 p-5 transition-all hover:-translate-y-1 hover:shadow-lg">
                                <div class="text-xs font-semibold uppercase tracking-[0.24em] text-amber-700">Saldo</div>
                                <div class="mt-3 text-2xl font-extrabold text-brand-navy">{{ $idr($summary['saldoAkhir']) }}</div>
                            </div>
                        </div>

                        <div class="mt-6 grid gap-5 md:grid-cols-2 xl:grid-cols-4">
                            <div class="rounded-3xl border border-slate-200 bg-white p-5 transition-all hover:-translate-y-1 hover:shadow-lg">
                                <div class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Utang Owner</div>
                                <div class="mt-3 text-2xl font-extrabold text-brand-navy">{{ $idr($summary['utangOwner']) }}</div>
                            </div>
                            <div class="rounded-3xl border border-slate-200 bg-white p-5 transition-all hover:-translate-y-1 hover:shadow-lg">
                                <div class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Utang Kasir</div>
                                <div class="mt-3 text-2xl font-extrabold text-brand-navy">{{ $idr($summary['utangKasir']) }}</div>
                            </div>
                            <div class="rounded-3xl border border-amber-200 bg-gradient-to-br from-amber-50 to-amber-100 p-5 transition-all hover:-translate-y-1 hover:shadow-lg">
                                <div class="text-xs font-semibold uppercase tracking-[0.24em] text-amber-700">Total Utang</div>
                                <div class="mt-3 text-2xl font-extrabold text-brand-navy">{{ $idr($summary['totalUtang']) }}</div>
                            </div>
                            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5 transition-all hover:-translate-y-1 hover:shadow-lg">
                                <div class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Saldo BRI</div>
                                <div class="mt-3 text-2xl font-extrabold text-brand-navy">{{ $idr((int) $summary['saldoBri']) }}</div>
                                <div class="mt-1 text-xs text-slate-400">
                                    Otomatis dari pemasukan kotor dan pengeluaran.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid gap-5">
                        <div class="rounded-[32px] border border-slate-200 bg-gradient-to-br from-brand-navy to-slate-900 p-7 text-white shadow-xl shadow-brand-navy/30 backdrop-blur-2xl">
                            <div class="text-sm font-semibold text-white/60">Laba {{ $publicData['year'] }}</div>
                            <div class="mt-2 text-3xl font-extrabold text-white">{{ $idr($summary['totalKeuntungan']) }}</div>
                            <div class="mt-2 text-sm text-white/70">Hasil bersih.</div>
                        </div>
                        <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-1 xl:grid-cols-2">
                            <div class="rounded-[32px] border border-slate-200 bg-white p-6 backdrop-blur-xl transition-all hover:-translate-y-1 hover:shadow-lg">
                                <div class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Karyawan</div>
                                <div class="mt-3 text-3xl font-extrabold text-brand-navy">{{ number_format($summary['jumlahKaryawan'], 0, ',', '.') }}</div>
                            </div>
                            <div class="rounded-[32px] border border-slate-200 bg-white p-6 backdrop-blur-xl transition-all hover:-translate-y-1 hover:shadow-lg">
                                <div class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Barang</div>
                                <div class="mt-3 text-3xl font-extrabold text-brand-navy">{{ number_format($summary['jumlahBarang'], 0, ',', '.') }}</div>
                            </div>
                        </div>
                        <div class="rounded-[32px] border border-slate-200 bg-white p-7 backdrop-blur-xl transition-all hover:-translate-y-1 hover:shadow-lg">
                            <div class="flex items-center justify-between gap-3">
                                <div>
                                    <div class="text-sm font-semibold text-brand-navy">Data Terbaru</div>
                                    <div class="text-xs text-slate-400">Input admin</div>
                                </div>
                                <span class="rounded-full border border-amber-200 bg-gradient-to-r from-amber-50 to-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">{{ count($entries) }} data</span>
                            </div>
                            <div class="mt-4 space-y-2">
                                @forelse ($entries as $entry)
                                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-3 transition-all hover:border-slate-300">
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
                                        Belum ada data yang diinput admin.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </section>

                <section class="mt-8 grid gap-6 lg:grid-cols-[1.45fr_0.55fr]">
                    <div class="rounded-[36px] border border-slate-200 bg-white p-8 shadow-xl shadow-slate-200/40 backdrop-blur-2xl">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <div class="text-sm font-semibold text-slate-400">Grafik</div>
                                <div class="mt-1 text-2xl font-bold tracking-tight text-brand-navy">Masuk vs Keluar</div>
                            </div>
                            <span class="rounded-full border border-slate-200 bg-slate-50 px-4 py-1.5 text-xs font-semibold text-slate-500">{{ $publicData['year'] }}</span>
                        </div>
                        <div id="publicIncomeExpenseChart" class="mt-7 h-96"></div>
                    </div>

                    <div class="rounded-[36px] border border-slate-200 bg-white p-8 shadow-xl shadow-slate-200/40 backdrop-blur-2xl">
                        <div class="text-sm font-semibold text-slate-400">Donut</div>
                        <div class="mt-1 text-2xl font-bold tracking-tight text-brand-navy">Kategori</div>
                        <div id="publicExpensePie" class="mt-7 h-96"></div>
                    </div>
                </section>

                <section class="mt-8 rounded-[36px] border border-slate-200 bg-white p-8 shadow-xl shadow-slate-200/40 backdrop-blur-2xl">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <div class="text-sm font-semibold text-slate-400">Grafik</div>
                            <div class="mt-1 text-2xl font-bold tracking-tight text-brand-navy">Laba Rugi</div>
                        </div>
                        <a href="{{ route('login') }}" class="btn-primary">Login untuk Kelola Data</a>
                    </div>
                    <div id="publicProfitChart" class="mt-7 h-96"></div>
                </section>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const months = @json($charts['months']);
                const income = @json($charts['income']);
                const expense = @json($charts['expense']);
                const profit = @json($charts['profit']);
                const pie = @json($charts['pieExpense']);
                const pieLabels = pie.length ? pie.map((item) => item.label) : ['Belum ada data'];
                const pieSeries = pie.length ? pie.map((item) => item.value) : [1];

                const base = {
                chart: { toolbar: { show: false }, fontFamily: 'Inter, ui-sans-serif, system-ui' },
                dataLabels: { enabled: false },
                stroke: { curve: 'smooth', width: 3 },
                grid: { borderColor: 'rgba(15,23,42,0.08)' },
                theme: { mode: 'light' },
                legend: { 
                    show: true, 
                    position: 'top', 
                    fontSize: '14px', 
                    fontWeight: 600,
                    labels: { colors: '#1e293b', useSeriesColors: true } 
                },
                xaxis: { labels: { style: { colors: '#64748b', fontSize: '12px', fontWeight: 500 } } },
                yaxis: { labels: { style: { colors: '#64748b', fontSize: '12px', fontWeight: 500 } } },
            };

                new ApexCharts(document.querySelector('#publicIncomeExpenseChart'), {
                    ...base,
                    chart: { ...base.chart, type: 'area', height: 380 },
                    series: [
                        { name: 'Pemasukan', data: income },
                        { name: 'Pengeluaran', data: expense },
                    ],
                    colors: ['#1E3A8A', '#FACC15'],
                    fill: { type: 'gradient', gradient: { opacityFrom: 0.4, opacityTo: 0.06 } },
                    xaxis: { ...base.xaxis, categories: months },
                    tooltip: { y: { formatter: (value) => 'Rp ' + new Intl.NumberFormat('id-ID').format(value) } },
                }).render();

                new ApexCharts(document.querySelector('#publicExpensePie'), {
                    ...base,
                    chart: { ...base.chart, type: 'donut', height: 380 },
                    labels: pieLabels,
                    series: pieSeries,
                    colors: ['#1E3A8A', '#FACC15', '#334155', '#94A3B8', '#E2E8F0', '#CBD5E1'],
                    legend: { 
                        position: 'bottom', 
                        fontSize: '14px', 
                        fontWeight: 600,
                        labels: { colors: '#1e293b', useSeriesColors: true } 
                    },
                    tooltip: { y: { formatter: (value) => 'Rp ' + new Intl.NumberFormat('id-ID').format(value) } },
                }).render();

                new ApexCharts(document.querySelector('#publicProfitChart'), {
                    ...base,
                    chart: { ...base.chart, type: 'bar', height: 380 },
                    series: [{ name: 'Laba / Rugi', data: profit }],
                    colors: ['#1E3A8A'],
                    xaxis: { ...base.xaxis, categories: months },
                    plotOptions: { bar: { borderRadius: 10, columnWidth: '52%' } },
                    tooltip: { y: { formatter: (value) => 'Rp ' + new Intl.NumberFormat('id-ID').format(value) } },
                }).render();
            });
        </script>
    </body>
</html>
