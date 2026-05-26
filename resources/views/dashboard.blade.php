<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <div class="text-sm font-semibold text-black/55 dark:text-white/60">Ringkasan</div>
                <div class="text-xl font-bold tracking-tight text-brand-navy dark:text-white">Dashboard</div>
            </div>
            <div class="flex items-center gap-2">
                <form method="GET" action="{{ route('dashboard') }}" class="flex items-center gap-2">
                    <input name="year" value="{{ $year }}" class="input w-28" />
                    <input name="saldo_bri" value="{{ request('saldo_bri') }}" placeholder="Saldo BRI..." class="input w-44" />
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
                            <div class="text-xs font-semibold uppercase tracking-wide text-black/45 dark:text-white/55">Total Pemasukan</div>
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
                    @if (!is_null($kpi['saldoBri']))
                        <div class="glass-card">
                            <div class="card-body">
                                <div class="text-xs font-semibold uppercase tracking-wide text-black/45 dark:text-white/55">Selisih vs BRI</div>
                                <div class="mt-1 text-2xl font-extrabold tracking-tight text-brand-navy dark:text-white">{{ $idr((int) $kpi['selisihSaldo']) }}</div>
                                <div class="mt-1 text-xs text-black/45 dark:text-white/55">Saldo BRI: {{ $idr((int) $kpi['saldoBri']) }}</div>
                            </div>
                        </div>
                    @endif
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
                    <div class="text-lg font-bold tracking-tight text-brand-navy dark:text-white">Pemasukan vs Pengeluaran</div>
                </div>
                <span class="badge-gold">Bulanan</span>
            </div>
            <div class="card-body">
                <div id="incomeExpenseChart" class="h-80"></div>
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

    <div class="mt-4 grid grid-cols-1 gap-4">
        <div class="glass-card">
            <div class="card-header">
                <div>
                    <div class="text-sm font-semibold text-black/55 dark:text-white/60">Grafik</div>
                    <div class="text-lg font-bold tracking-tight text-brand-navy dark:text-white">Laba Rugi</div>
                </div>
                <span class="badge-gold">Bulanan</span>
            </div>
            <div class="card-body">
                <div id="profitChart" class="h-80"></div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const months = @json($charts['months']);
            const income = @json($charts['income']);
            const expense = @json($charts['expense']);
            const profit = @json($charts['profit']);
            const pie = @json($charts['pieExpense']);

            const base = {
                chart: { toolbar: { show: false }, fontFamily: 'Inter, ui-sans-serif, system-ui' },
                grid: { borderColor: 'rgba(0,0,0,0.05)' },
                stroke: { curve: 'smooth', width: 3 },
                dataLabels: { enabled: false },
                theme: { mode: document.documentElement.classList.contains('dark') ? 'dark' : 'light' },
            };

            const incomeExpense = new ApexCharts(document.querySelector('#incomeExpenseChart'), {
                ...base,
                chart: { ...base.chart, type: 'area', height: 320 },
                colors: ['#1E3A8A', '#FACC15'],
                series: [
                    { name: 'Pemasukan', data: income },
                    { name: 'Pengeluaran', data: expense },
                ],
                xaxis: { categories: months },
                fill: { type: 'gradient', gradient: { opacityFrom: 0.35, opacityTo: 0.05 } },
                tooltip: { y: { formatter: (v) => 'Rp ' + new Intl.NumberFormat('id-ID').format(v) } },
            });
            incomeExpense.render();

            const profitChart = new ApexCharts(document.querySelector('#profitChart'), {
                ...base,
                chart: { ...base.chart, type: 'bar', height: 320 },
                colors: ['#1E3A8A'],
                series: [{ name: 'Laba/Rugi', data: profit }],
                xaxis: { categories: months },
                plotOptions: { bar: { borderRadius: 10, columnWidth: '55%' } },
                tooltip: { y: { formatter: (v) => 'Rp ' + new Intl.NumberFormat('id-ID').format(v) } },
            });
            profitChart.render();

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
