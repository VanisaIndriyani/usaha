<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <div class="text-sm font-semibold text-black/55 dark:text-white/60">Ringkasan</div>
                <div class="text-xl font-bold tracking-tight text-brand-navy dark:text-white">Dashboard</div>
            </div>
            <div class="flex items-center gap-2">
                        <form method="GET" action="{{ route('dashboard') }}" class="flex items-center gap-2">
                            <select name="year" class="input w-32">
                                @for($y = now()->year - 5; $y <= now()->year + 1; $y++)
                                    <option value="{{ $y }}" @selected($y == $year)>{{ $y }}</option>
                                @endfor
                            </select>
                            <button class="btn-ghost" type="submit">Terapkan</button>
                        </form>
                    </div>
        </div>
    </x-slot>

    @php
        $idr = fn (int $v) => 'Rp ' . number_format($v, 0, ',', '.');
    @endphp

    <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
        <div class="glass-card lg:col-span-2">
            <div class="card-header">
                <div>
                    <div class="text-sm font-semibold text-black/55 dark:text-white/60">KPI Utama</div>
                    <div class="text-lg font-bold tracking-tight text-brand-navy dark:text-white">Statistik realtime</div>
                </div>
                <span class="badge-gold">{{ $year }}</span>
            </div>
            <div class="card-body">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3">
                    <div class="glass-card">
                        <div class="card-body">
                            <div class="text-xs font-semibold uppercase tracking-wide text-black/45 dark:text-white/55">Total Modal</div>
                            <div class="mt-1 text-2xl font-extrabold tracking-tight text-brand-navy dark:text-white">{{ $idr($kpi['totalModal']) }}</div>
                        </div>
                    </div>
                    <div class="glass-card">
                        <div class="card-body">
                            <div class="text-xs font-semibold uppercase tracking-wide text-black/45 dark:text-white/55">Pemasukan Kotor</div>
                            <div class="mt-1 text-2xl font-extrabold tracking-tight text-brand-navy dark:text-white">{{ $idr($kpi['totalPemasukan']) }}</div>
                        </div>
                    </div>
                    <div class="glass-card">
                        <div class="card-body">
                            <div class="text-xs font-semibold uppercase tracking-wide text-black/45 dark:text-white/55">Total Pengeluaran</div>
                            <div class="mt-1 text-2xl font-extrabold tracking-tight text-brand-navy dark:text-white">{{ $idr($kpi['totalPengeluaran']) }}</div>
                        </div>
                    </div>
                    <div class="glass-card">
                        <div class="card-body">
                            <div class="text-xs font-semibold uppercase tracking-wide text-black/45 dark:text-white/55">Total Keuntungan</div>
                            <div class="mt-1 text-2xl font-extrabold tracking-tight text-brand-navy dark:text-white">{{ $idr($kpi['totalKeuntungan']) }}</div>
                        </div>
                    </div>
                    <div class="glass-card">
                        <div class="card-body">
                            <div class="text-xs font-semibold uppercase tracking-wide text-black/45 dark:text-white/55">Saldo Akhir</div>
                            <div class="mt-1 text-2xl font-extrabold tracking-tight text-brand-navy dark:text-white">{{ $idr($kpi['saldoAkhir']) }}</div>
                        </div>
                    </div>
                    <div class="glass-card">
                        <div class="card-body">
                            <div class="text-xs font-semibold uppercase tracking-wide text-black/45 dark:text-white/55">Utang Owner</div>
                            <div class="mt-1 text-2xl font-extrabold tracking-tight text-brand-navy dark:text-white">{{ $idr($kpi['utangOwner']) }}</div>
                        </div>
                    </div>
                    <div class="glass-card">
                        <div class="card-body">
                            <div class="text-xs font-semibold uppercase tracking-wide text-black/45 dark:text-white/55">Utang Kasir</div>
                            <div class="mt-1 text-2xl font-extrabold tracking-tight text-brand-navy dark:text-white">{{ $idr($kpi['utangKasir']) }}</div>
                        </div>
                    </div>
                    <div class="glass-card">
                        <div class="card-body">
                            <div class="text-xs font-semibold uppercase tracking-wide text-black/45 dark:text-white/55">Total Utang</div>
                            <div class="mt-1 text-2xl font-extrabold tracking-tight text-amber-600">{{ $idr($kpi['totalUtang']) }}</div>
                        </div>
                    </div>
                    <div class="glass-card">
                        <div class="card-body">
                            <div class="text-xs font-semibold uppercase tracking-wide text-black/45 dark:text-white/55">Saldo Usaha BRI</div>
                            <div class="mt-1 text-2xl font-extrabold tracking-tight text-brand-navy dark:text-white">{{ $idr((int) $kpi['saldoBri']) }}</div>
                            <div class="mt-1 text-xs text-black/45 dark:text-white/55">
                                Otomatis dari modal + pemasukan kotor - pengeluaran.
                            </div>
                        </div>
                    </div>
                    <div class="glass-card">
                        <div class="card-body">
                            <div class="text-xs font-semibold uppercase tracking-wide text-black/45 dark:text-white/55">Jumlah Karyawan</div>
                            <div class="mt-1 text-2xl font-extrabold tracking-tight text-brand-navy dark:text-white">{{ number_format($kpi['jumlahKaryawan'], 0, ',', '.') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="glass-card">
            <div class="card-header">
                <div>
                    <div class="text-sm font-semibold text-black/55 dark:text-white/60">Pengeluaran</div>
                    <div class="text-lg font-bold tracking-tight text-brand-navy dark:text-white">Kategori (Pie)</div>
                </div>
                <span class="badge-gold">Top</span>
            </div>
            <div class="card-body">
                <div id="expensePie" class="h-72"></div>
            </div>
        </div>
    </div>

    <div class="mt-4 grid grid-cols-1 gap-4 lg:grid-cols-3">
        <div class="glass-card lg:col-span-2">
            <div class="card-header">
                <div>
                    <div class="text-sm font-semibold text-black/55 dark:text-white/60">Grafik</div>
                    <div class="text-lg font-bold tracking-tight text-brand-navy dark:text-white">Pemasukan Per Bulan</div>
                </div>
                <span class="badge-gold">Bulanan</span>
            </div>
            <div class="card-body">
                <div id="incomeChart" class="h-80"></div>
            </div>
        </div>

        <div class="glass-card">
            <div class="card-header">
                <div>
                    <div class="text-sm font-semibold text-black/55 dark:text-white/60">Aktivitas</div>
                    <div class="text-lg font-bold tracking-tight text-brand-navy dark:text-white">Terbaru</div>
                </div>
                <span class="badge-gold">{{ $recentActivities->count() }}</span>
            </div>
            <div class="card-body">
                <div class="space-y-3">
                    @forelse ($recentActivities as $log)
                        <div class="rounded-2xl border border-black/5 bg-white/60 p-3 dark:border-white/10 dark:bg-white/5">
                            <div class="flex items-center justify-between gap-2">
                                <div class="text-sm font-semibold text-brand-navy dark:text-white">{{ $log->action }}</div>
                                <div class="text-xs text-black/45 dark:text-white/55">{{ $log->created_at->diffForHumans() }}</div>
                            </div>
                            <div class="mt-1 text-xs text-black/50 dark:text-white/60">
                                {{ $log->user?->name ?? 'System' }}
                            </div>
                        </div>
                    @empty
                        <div class="rounded-2xl border border-black/5 bg-white/60 p-4 text-sm text-black/50 dark:border-white/10 dark:bg-white/5 dark:text-white/60">
                            Belum ada aktivitas.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const months = @json($charts['months']);
            const income = @json($charts['income']);
            const pie = @json($charts['pieExpense']);

            const base = {
                chart: { toolbar: { show: false }, fontFamily: 'Inter, ui-sans-serif, system-ui' },
                grid: { borderColor: 'rgba(0,0,0,0.08)' },
                stroke: { curve: 'smooth', width: 3 },
                dataLabels: { enabled: false },
                theme: { mode: document.documentElement.classList.contains('dark') ? 'dark' : 'light' },
                legend: { 
                    show: true, 
                    fontSize: '14px', 
                    fontWeight: 600,
                    labels: { useSeriesColors: true } 
                },
                xaxis: { labels: { style: { fontSize: '12px', fontWeight: 500 } } },
                yaxis: { labels: { style: { fontSize: '12px', fontWeight: 500 } } },
            };

            const incomeChart = new ApexCharts(document.querySelector('#incomeChart'), {
                ...base,
                chart: { ...base.chart, type: 'bar', height: 380 },
                colors: ['#1E3A8A'],
                series: [{ name: 'Pemasukan', data: income }],
                xaxis: { categories: months },
                plotOptions: { bar: { borderRadius: 10, columnWidth: '55%' } },
                tooltip: { y: { formatter: (v) => 'Rp ' + new Intl.NumberFormat('id-ID').format(v) } },
            });
            incomeChart.render();

            const pieChart = new ApexCharts(document.querySelector('#expensePie'), {
                ...base,
                chart: { ...base.chart, type: 'donut', height: 280 },
                labels: pie.map((i) => i.label),
                series: pie.map((i) => i.value),
                colors: ['#0F172A', '#1E3A8A', '#FACC15', '#334155', '#64748B', '#94A3B8', '#CBD5E1', '#E2E8F0'],
                legend: { position: 'bottom' },
                tooltip: { y: { formatter: (v) => 'Rp ' + new Intl.NumberFormat('id-ID').format(v) } },
            });
            pieChart.render();
        });
    </script>
</x-app-layout>
