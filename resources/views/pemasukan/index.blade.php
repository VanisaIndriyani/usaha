<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <div class="text-sm font-semibold text-black/55 dark:text-white/60">Keuangan</div>
                <div class="text-xl font-bold tracking-tight text-brand-navy dark:text-white">Pemasukan Kotor Harian</div>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <form method="GET" action="{{ route('pemasukan.index') }}" class="flex flex-wrap items-center gap-2">
                    <input name="q" value="{{ $q }}" placeholder="Cari pemasukan..." class="input w-56" />
                    <input name="start" value="{{ $start }}" type="date" class="input w-44" />
                    <input name="end" value="{{ $end }}" type="date" class="input w-44" />
                    <input name="year" value="{{ $chart['year'] }}" class="input w-28" />
                    <button class="btn-ghost" type="submit">Terapkan</button>
                </form>
                <a href="{{ route('pemasukan.create') }}" class="btn-primary">Tambah</a>
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
                    <div class="text-lg font-bold tracking-tight text-brand-navy dark:text-white">Pemasukan Kotor Bulanan</div>
                </div>
                <span class="badge-gold">{{ $chart['year'] }}</span>
            </div>
            <div class="card-body">
                <div id="incomeChart" class="h-80"></div>
            </div>
        </div>

        <div class="glass-card">
            <div class="card-header">
                <div>
                    <div class="text-sm font-semibold text-black/55 dark:text-white/60">Total</div>
                    <div class="text-lg font-bold tracking-tight text-brand-navy dark:text-white">Ringkasan</div>
                </div>
                <span class="badge-gold">IDR</span>
            </div>
            <div class="card-body space-y-3 text-sm">
                <div class="flex items-center justify-between gap-3">
                    <div class="text-black/55 dark:text-white/60">Harian</div>
                    <div class="font-bold text-brand-navy dark:text-white">{{ $idr($stats['harian']) }}</div>
                </div>
                <div class="flex items-center justify-between gap-3">
                    <div class="text-black/55 dark:text-white/60">Mingguan</div>
                    <div class="font-bold text-brand-navy dark:text-white">{{ $idr($stats['mingguan']) }}</div>
                </div>
                <div class="flex items-center justify-between gap-3">
                    <div class="text-black/55 dark:text-white/60">Bulanan</div>
                    <div class="font-bold text-brand-navy dark:text-white">{{ $idr($stats['bulanan']) }}</div>
                </div>
                <div class="flex items-center justify-between gap-3">
                    <div class="text-black/55 dark:text-white/60">Tahunan</div>
                    <div class="font-bold text-brand-navy dark:text-white">{{ $idr($stats['tahunan']) }}</div>
                </div>
                <div class="rounded-2xl bg-slate-50 px-3 py-2 text-xs text-slate-500">
                    Saldo usaha otomatis dihitung dari pemasukan kotor dikurangi pengeluaran.
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4 glass-card">
        <div class="card-header">
            <div>
                <div class="text-sm font-semibold text-black/55 dark:text-white/60">Data</div>
                <div class="text-lg font-bold tracking-tight text-brand-navy dark:text-white">Transaksi Pemasukan Kotor</div>
            </div>
            <span class="badge-gold">{{ $pemasukan->total() }}</span>
        </div>
        <div class="card-body">
            <div class="table-modern">
                <table class="w-full">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Nama</th>
                            <th>Nominal</th>
                            <th>Bukti</th>
                            <th>Catatan</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pemasukan as $row)
                            <tr>
                                <td>{{ $row->tanggal?->format('d M Y') }}</td>
                                <td class="font-bold">{{ $row->nama_pemasukan }}</td>
                                <td class="font-bold text-brand-blue dark:text-brand-gold">{{ $idr((int) $row->nominal) }}</td>
                                <td>
                                    @if ($row->bukti_path)
                                        <a href="{{ asset('storage/' . $row->bukti_path) }}" target="_blank" rel="noopener">
                                            <div class="h-10 w-10 overflow-hidden rounded-xl bg-brand-gray/60 ring-1 ring-black/5 dark:bg-white/10 dark:ring-white/10">
                                                <img src="{{ asset('storage/' . $row->bukti_path) }}" alt="Bukti" class="h-full w-full object-cover">
                                            </div>
                                        </a>
                                    @else
                                        <span class="text-xs text-black/40 dark:text-white/40">-</span>
                                    @endif
                                </td>
                                <td class="text-black/55 dark:text-white/60">{{ $row->catatan }}</td>
                                <td class="text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('pemasukan.show', $row) }}" class="btn-ghost px-3 py-2">Detail</a>
                                        <a href="{{ route('pemasukan.edit', $row) }}" class="btn-ghost px-3 py-2">Edit</a>
                                        <form method="POST" action="{{ route('pemasukan.destroy', $row) }}" x-data @submit.prevent="$store.ui.confirm({title:'Hapus pemasukan?', text:'Data pemasukan akan dihapus.'}).then(r=>{ if(r.isConfirmed) $el.submit() })">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-danger px-3 py-2">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-10 text-center text-sm text-black/50 dark:text-white/60">
                                    Belum ada data pemasukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $pemasukan->links() }}
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const labels = @json($chart['labels']);
            const series = @json($chart['series']);

            const chart = new ApexCharts(document.querySelector('#incomeChart'), {
                chart: { type: 'area', height: 320, toolbar: { show: false }, fontFamily: 'Inter, ui-sans-serif, system-ui' },
                theme: { mode: document.documentElement.classList.contains('dark') ? 'dark' : 'light' },
                colors: ['#1E3A8A'],
                series: [{ name: 'Pemasukan Kotor', data: series }],
                xaxis: { categories: labels },
                dataLabels: { enabled: false },
                stroke: { curve: 'smooth', width: 3 },
                fill: { type: 'gradient', gradient: { opacityFrom: 0.35, opacityTo: 0.06 } },
                grid: { borderColor: 'rgba(0,0,0,0.05)' },
                tooltip: { y: { formatter: (v) => 'Rp ' + new Intl.NumberFormat('id-ID').format(v) } },
            });
            chart.render();
        });
    </script>
</x-app-layout>
