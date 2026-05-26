<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <div class="text-sm font-semibold text-black/55 dark:text-white/60">Keuangan</div>
                <div class="text-xl font-bold tracking-tight text-brand-navy dark:text-white">Pengeluaran</div>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <form method="GET" action="{{ route('pengeluaran.index') }}" class="flex flex-wrap items-center gap-2">
                    <input name="q" value="{{ $q }}" placeholder="Cari pengeluaran..." class="input w-56" />
                    <select name="kategori" class="input w-52">
                        <option value="">Semua kategori</option>
                        @foreach ($kategoriList as $k)
                            <option value="{{ $k }}" @selected($kategori === $k)>{{ $k }}</option>
                        @endforeach
                    </select>
                    <input name="start" value="{{ $start }}" type="date" class="input w-44" />
                    <input name="end" value="{{ $end }}" type="date" class="input w-44" />
                    <input name="year" value="{{ $chart['year'] }}" class="input w-28" />
                    <button class="btn-ghost" type="submit">Terapkan</button>
                </form>
                <a href="{{ route('pengeluaran.create') }}" class="btn-primary">Tambah</a>
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
                    <div class="text-sm font-semibold text-black/55 dark:text-white/60">Grafik</div>
                    <div class="text-lg font-bold tracking-tight text-brand-navy dark:text-white">Pengeluaran Bulanan</div>
                </div>
                <span class="badge-gold">{{ $chart['year'] }}</span>
            </div>
            <div class="card-body">
                <div id="expenseChart" class="h-80"></div>
            </div>
        </div>

        <div class="glass-card">
            <div class="card-header">
                <div>
                    <div class="text-sm font-semibold text-black/55 dark:text-white/60">Total</div>
                    <div class="text-lg font-bold tracking-tight text-brand-navy dark:text-white">Filter Saat Ini</div>
                </div>
                <span class="badge-gold">{{ $idr($total) }}</span>
            </div>
            <div class="card-body">
                <div id="expensePie" class="h-72"></div>
            </div>
        </div>
    </div>

    <div class="mt-4 glass-card">
        <div class="card-header">
            <div>
                <div class="text-sm font-semibold text-black/55 dark:text-white/60">Data</div>
                <div class="text-lg font-bold tracking-tight text-brand-navy dark:text-white">Transaksi Pengeluaran</div>
            </div>
            <span class="badge-gold">{{ $pengeluaran->total() }}</span>
        </div>
        <div class="card-body">
            <div class="table-modern">
                <table class="w-full">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Nama</th>
                            <th>Kategori</th>
                            <th>Akun</th>
                            <th>Nominal</th>
                            <th>Catatan</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pengeluaran as $row)
                            <tr>
                                <td>{{ $row->tanggal?->format('d M Y') }}</td>
                                <td class="font-bold">{{ $row->nama_pengeluaran }}</td>
                                <td><span class="badge-gold">{{ $row->kategori }}</span></td>
                                <td><span class="badge-gold">{{ $row->akun ?? 'BRI' }}</span></td>
                                <td class="font-bold text-brand-blue dark:text-brand-gold">{{ $idr((int) $row->nominal) }}</td>
                                <td class="text-black/55 dark:text-white/60">{{ $row->catatan }}</td>
                                <td class="text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('pengeluaran.edit', $row) }}" class="btn-ghost px-3 py-2">Edit</a>
                                        <form method="POST" action="{{ route('pengeluaran.destroy', $row) }}" x-data @submit.prevent="$store.ui.confirm({title:'Hapus pengeluaran?', text:'Data pengeluaran akan dihapus.'}).then(r=>{ if(r.isConfirmed) $el.submit() })">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-danger px-3 py-2">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-10 text-center text-sm text-black/50 dark:text-white/60">
                                    Belum ada data pengeluaran.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $pengeluaran->links() }}
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const labels = @json($chart['labels']);
            const series = @json($chart['series']);
            const pie = @json($pie);

            const expenseChart = new ApexCharts(document.querySelector('#expenseChart'), {
                chart: { type: 'area', height: 320, toolbar: { show: false }, fontFamily: 'Inter, ui-sans-serif, system-ui' },
                theme: { mode: document.documentElement.classList.contains('dark') ? 'dark' : 'light' },
                colors: ['#FACC15'],
                series: [{ name: 'Pengeluaran', data: series }],
                xaxis: { categories: labels },
                dataLabels: { enabled: false },
                stroke: { curve: 'smooth', width: 3 },
                fill: { type: 'gradient', gradient: { opacityFrom: 0.35, opacityTo: 0.06 } },
                grid: { borderColor: 'rgba(0,0,0,0.05)' },
                tooltip: { y: { formatter: (v) => 'Rp ' + new Intl.NumberFormat('id-ID').format(v) } },
            });
            expenseChart.render();

            const expensePie = new ApexCharts(document.querySelector('#expensePie'), {
                chart: { type: 'donut', height: 280, toolbar: { show: false }, fontFamily: 'Inter, ui-sans-serif, system-ui' },
                theme: { mode: document.documentElement.classList.contains('dark') ? 'dark' : 'light' },
                labels: pie.map((i) => i.label),
                series: pie.map((i) => i.value),
                colors: ['#0F172A', '#1E3A8A', '#FACC15', '#334155', '#64748B', '#94A3B8', '#CBD5E1', '#E2E8F0'],
                legend: { position: 'bottom' },
                tooltip: { y: { formatter: (v) => 'Rp ' + new Intl.NumberFormat('id-ID').format(v) } },
            });
            expensePie.render();
        });
    </script>
</x-app-layout>
