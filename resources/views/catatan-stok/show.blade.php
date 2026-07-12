<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <div class="text-sm font-semibold text-black/55 dark:text-white/60">Operasional</div>
                <div class="text-xl font-bold tracking-tight text-brand-navy dark:text-white">Detail Catatan Stok</div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('catatan-stok.edit', $catatanStok) }}" class="btn-ghost">Edit</a>
                <a href="{{ route('catatan-stok.index') }}" class="btn-primary">Kembali</a>
            </div>
        </div>
    </x-slot>

    @php
        $idr = fn (int $value) => 'Rp ' . number_format($value, 0, ',', '.');
    @endphp

    <div class="grid gap-4 lg:grid-cols-2">
        <div class="glass-card">
            <div class="card-header">
                <div>
                    <div class="text-sm font-semibold text-black/55 dark:text-white/60">Informasi</div>
                    <div class="text-lg font-bold tracking-tight text-brand-navy dark:text-white">Detail Catatan</div>
                </div>
            </div>
            <div class="card-body space-y-4">
                <div>
                    <div class="text-xs font-semibold uppercase tracking-[0.24em] text-black/40 dark:text-white/50">Tanggal</div>
                    <div class="mt-1 text-lg font-bold text-brand-navy dark:text-white">{{ $catatanStok->tanggal?->format('d F Y') }}</div>
                </div>
                <div>
                    <div class="text-xs font-semibold uppercase tracking-[0.24em] text-black/40 dark:text-white/50">Item</div>
                    <div class="mt-1 text-lg font-bold text-brand-navy dark:text-white">{{ $catatanStok->nama_item }}</div>
                </div>
                <div>
                    <div class="text-xs font-semibold uppercase tracking-[0.24em] text-black/40 dark:text-white/50">Jenis</div>
                    <div class="mt-1">
                        <span class="badge-gold">{{ $catatanStok->jenis }}</span>
                    </div>
                </div>
                <div>
                    <div class="text-xs font-semibold uppercase tracking-[0.24em] text-black/40 dark:text-white/50">Jumlah</div>
                    <div class="mt-1 text-lg font-bold text-brand-navy dark:text-white">{{ number_format((float) $catatanStok->jumlah, 2, ',', '.') }} {{ $catatanStok->satuan }}</div>
                </div>
                @if ((int) $catatanStok->nominal > 0)
                    <div>
                        <div class="text-xs font-semibold uppercase tracking-[0.24em] text-black/40 dark:text-white/50">Nominal</div>
                        <div class="mt-1 text-2xl font-extrabold text-brand-navy dark:text-white">{{ $idr((int) $catatanStok->nominal) }}</div>
                    </div>
                @endif
                @if ($catatanStok->sumber_dana)
                    <div>
                        <div class="text-xs font-semibold uppercase tracking-[0.24em] text-black/40 dark:text-white/50">Sumber Dana</div>
                        <div class="mt-1 text-lg font-bold text-brand-navy dark:text-white">{{ $sumberDanaList[$catatanStok->sumber_dana] ?? $catatanStok->sumber_dana }}</div>
                    </div>
                @endif
                @if ($catatanStok->utangOperasional)
                    <div>
                        <div class="text-xs font-semibold uppercase tracking-[0.24em] text-black/40 dark:text-white/50">Status Utang</div>
                        <div class="mt-1">
                            <span class="{{ $catatanStok->utangOperasional->status === 'lunas' ? 'inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700' : 'inline-flex items-center rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-700' }}">
                                {{ $catatanStok->utangOperasional->status === 'lunas' ? 'Lunas' : 'Belum Lunas' }}
                            </span>
                        </div>
                    </div>
                @endif
                <div>
                    <div class="text-xs font-semibold uppercase tracking-[0.24em] text-black/40 dark:text-white/50">Catatan</div>
                    <div class="mt-1 text-sm text-black/70 dark:text-white/70">{{ $catatanStok->catatan ?: '-' }}</div>
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
                @if ($catatanStok->bukti_path)
                    <a href="{{ asset('storage/' . $catatanStok->bukti_path) }}" target="_blank" rel="noopener" class="block">
                        <img src="{{ asset('storage/' . $catatanStok->bukti_path) }}" alt="Bukti Catatan Stok" class="w-full rounded-2xl border border-black/5 dark:border-white/10" />
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
