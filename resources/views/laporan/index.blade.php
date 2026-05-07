<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <div class="text-sm font-semibold text-black/55 dark:text-white/60">Keuangan</div>
                <div class="text-xl font-bold tracking-tight text-brand-navy dark:text-white">Laporan Keuangan</div>
            </div>
            <form method="GET" action="{{ route('laporan.index') }}" class="flex flex-wrap items-center gap-2">
                <input name="start" value="{{ $start }}" type="date" class="input w-44" />
                <input name="end" value="{{ $end }}" type="date" class="input w-44" />
                <button class="btn-ghost" type="submit">Terapkan</button>
            </form>
        </div>
    </x-slot>

    @php
        $idr = fn (int $v) => 'Rp ' . number_format($v, 0, ',', '.');
        $q = ['start' => $start, 'end' => $end];
    @endphp

    <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
        <div class="glass-card lg:col-span-2">
            <div class="card-header">
                <div>
                    <div class="text-sm font-semibold text-black/55 dark:text-white/60">Ringkasan</div>
                    <div class="text-lg font-bold tracking-tight text-brand-navy dark:text-white">{{ $start }} – {{ $end }}</div>
                </div>
                <span class="badge-gold">IDR</span>
            </div>
            <div class="card-body">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3">
                    <div class="glass-card"><div class="card-body"><div class="text-xs font-semibold uppercase tracking-wide text-black/45 dark:text-white/55">Pemasukan</div><div class="mt-1 text-xl font-extrabold tracking-tight text-brand-navy dark:text-white">{{ $idr($summary['income']) }}</div></div></div>
                    <div class="glass-card"><div class="card-body"><div class="text-xs font-semibold uppercase tracking-wide text-black/45 dark:text-white/55">Pengeluaran (manual)</div><div class="mt-1 text-xl font-extrabold tracking-tight text-brand-navy dark:text-white">{{ $idr($summary['expenseManual']) }}</div></div></div>
                    <div class="glass-card"><div class="card-body"><div class="text-xs font-semibold uppercase tracking-wide text-black/45 dark:text-white/55">Pengeluaran Barang</div><div class="mt-1 text-xl font-extrabold tracking-tight text-brand-navy dark:text-white">{{ $idr($summary['expenseBarang']) }}</div></div></div>
                    <div class="glass-card"><div class="card-body"><div class="text-xs font-semibold uppercase tracking-wide text-black/45 dark:text-white/55">Gaji Dibayar</div><div class="mt-1 text-xl font-extrabold tracking-tight text-brand-navy dark:text-white">{{ $idr($summary['expenseGaji']) }}</div></div></div>
                    <div class="glass-card"><div class="card-body"><div class="text-xs font-semibold uppercase tracking-wide text-black/45 dark:text-white/55">Total Pengeluaran</div><div class="mt-1 text-xl font-extrabold tracking-tight text-brand-navy dark:text-white">{{ $idr($summary['totalExpense']) }}</div></div></div>
                    <div class="glass-card"><div class="card-body"><div class="text-xs font-semibold uppercase tracking-wide text-black/45 dark:text-white/55">Laba/Rugi</div><div class="mt-1 text-xl font-extrabold tracking-tight text-brand-navy dark:text-white">{{ $idr($summary['profit']) }}</div></div></div>
                </div>
            </div>
        </div>

        <div class="glass-card">
            <div class="card-header">
                <div>
                    <div class="text-sm font-semibold text-black/55 dark:text-white/60">Export</div>
                    <div class="text-lg font-bold tracking-tight text-brand-navy dark:text-white">PDF & Excel</div>
                </div>
                <span class="badge-gold">Ready</span>
            </div>
            <div class="card-body space-y-3">
                <div class="grid grid-cols-2 gap-2">
                    <a class="btn-ghost justify-center" href="{{ route('export.pemasukan.pdf', $q) }}">Pemasukan PDF</a>
                    <a class="btn-ghost justify-center" href="{{ route('export.pemasukan.excel', $q) }}">Pemasukan Excel</a>
                    <a class="btn-ghost justify-center" href="{{ route('export.pengeluaran.pdf', $q) }}">Pengeluaran PDF</a>
                    <a class="btn-ghost justify-center" href="{{ route('export.pengeluaran.excel', $q) }}">Pengeluaran Excel</a>
                    <a class="btn-ghost justify-center" href="{{ route('export.laba-rugi.pdf', $q) }}">Laba Rugi PDF</a>
                    <a class="btn-ghost justify-center" href="{{ route('export.laba-rugi.excel', $q) }}">Laba Rugi Excel</a>
                    <a class="btn-ghost justify-center" href="{{ route('export.modal.pdf', $q) }}">Modal PDF</a>
                    <a class="btn-ghost justify-center" href="{{ route('export.modal.excel', $q) }}">Modal Excel</a>
                    <a class="btn-ghost justify-center" href="{{ route('export.gaji.pdf', $q) }}">Gaji PDF</a>
                    <a class="btn-ghost justify-center" href="{{ route('export.gaji.excel', $q) }}">Gaji Excel</a>
                    <a class="btn-ghost justify-center" href="{{ route('export.profit-sharing.pdf', $q) }}">Profit PDF</a>
                    <a class="btn-ghost justify-center" href="{{ route('export.profit-sharing.excel', $q) }}">Profit Excel</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

