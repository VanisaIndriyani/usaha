<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <div class="text-sm font-semibold text-black/55 dark:text-white/60">Keuangan</div>
                <div class="text-xl font-bold tracking-tight text-brand-navy dark:text-white">Detail Pengeluaran</div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('pengeluaran.edit', $pengeluaran) }}" class="btn-ghost">Edit</a>
                <a href="{{ route('pengeluaran.index') }}" class="btn-primary">Kembali</a>
            </div>
        </div>
    </x-slot>

    @php
        $idr = fn (int $v) => 'Rp ' . number_format($v, 0, ',', '.');
    @endphp

    <div class="grid gap-4 lg:grid-cols-2">
        <div class="glass-card">
            <div class="card-header">
                <div>
                    <div class="text-sm font-semibold text-black/55 dark:text-white/60">Informasi</div>
                    <div class="text-lg font-bold tracking-tight text-brand-navy dark:text-white">Detail Pengeluaran</div>
                </div>
            </div>
            <div class="card-body space-y-4">
                <div>
                    <div class="text-xs font-semibold uppercase tracking-[0.24em] text-black/40 dark:text-white/50">Tanggal</div>
                    <div class="mt-1 text-lg font-bold text-brand-navy dark:text-white">{{ $pengeluaran->tanggal?->format('d F Y') }}</div>
                </div>
                <div>
                    <div class="text-xs font-semibold uppercase tracking-[0.24em] text-black/40 dark:text-white/50">Nama Pengeluaran</div>
                    <div class="mt-1 text-lg font-bold text-brand-navy dark:text-white">{{ $pengeluaran->nama_pengeluaran }}</div>
                </div>
                <div>
                    <div class="text-xs font-semibold uppercase tracking-[0.24em] text-black/40 dark:text-white/50">Kategori</div>
                    <div class="mt-1 text-lg font-bold text-brand-navy dark:text-white">{{ $pengeluaran->kategori }}</div>
                </div>
                <div>
                    <div class="text-xs font-semibold uppercase tracking-[0.24em] text-black/40 dark:text-white/50">Nominal</div>
                    <div class="mt-1 text-2xl font-extrabold text-brand-navy dark:text-white">{{ $idr((int) $pengeluaran->nominal) }}</div>
                </div>
                <div>
                    <div class="text-xs font-semibold uppercase tracking-[0.24em] text-black/40 dark:text-white/50">Catatan</div>
                    <div class="mt-1 text-sm text-black/70 dark:text-white/70">{{ $pengeluaran->catatan ?: '-' }}</div>
                </div>
            </div>
        </div>

        <div class="glass-card">
            <div class="card-header">
                <div>
                    <div class="text-sm font-semibold text-black/55 dark:text-white/60">Bukti</div>
                    <div class="text-lg font-bold tracking-tight text-brand-navy dark:text-white">Bukti Transaksi</div>
                </div>
            </div>
            <div class="card-body">
                @if ($pengeluaran->bukti_path)
                    <a href="{{ asset('storage/' . $pengeluaran->bukti_path) }}" target="_blank" rel="noopener" class="block">
                        <img src="{{ asset('storage/' . $pengeluaran->bukti_path) }}" alt="Bukti Pengeluaran" class="w-full rounded-2xl border border-black/5 dark:border-white/10" />
                    </a>
                @else
                    <div class="flex h-64 flex-col items-center justify-center rounded-2xl border border-dashed border-black/10 bg-slate-50 text-center text-sm text-black/40 dark:border-white/10 dark:bg-white/5 dark:text-white/40">
                        Belum ada bukti transaksi yang diunggah.
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
