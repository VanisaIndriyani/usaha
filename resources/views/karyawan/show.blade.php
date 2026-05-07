<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3">
            <div>
                <div class="text-sm font-semibold text-black/55 dark:text-white/60">SDM</div>
                <div class="text-xl font-bold tracking-tight text-brand-navy dark:text-white">Detail Karyawan</div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('karyawan.edit', $karyawan) }}" class="btn-primary">Edit</a>
                <a href="{{ route('karyawan.index') }}" class="btn-ghost">Kembali</a>
            </div>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
        <div class="glass-card lg:col-span-1">
            <div class="card-body">
                <div class="flex items-center gap-4">
                    <div class="h-16 w-16 overflow-hidden rounded-2xl bg-brand-gray/60 ring-1 ring-black/5 dark:bg-white/10 dark:ring-white/10">
                        @if ($karyawan->foto_path)
                            <img src="{{ asset('storage/' . $karyawan->foto_path) }}" alt="{{ $karyawan->nama }}" class="h-full w-full object-cover">
                        @endif
                    </div>
                    <div class="min-w-0">
                        <div class="truncate text-lg font-extrabold tracking-tight text-brand-navy dark:text-white">{{ $karyawan->nama }}</div>
                        <div class="mt-1 flex items-center gap-2">
                            <span class="badge-gold">{{ $karyawan->jabatan }}</span>
                            <span class="badge-gold">{{ $karyawan->status_kerja }}</span>
                        </div>
                    </div>
                </div>

                <div class="mt-5 space-y-3 text-sm">
                    <div class="flex items-center justify-between gap-3">
                        <div class="text-black/55 dark:text-white/60">No HP</div>
                        <div class="font-semibold text-brand-navy dark:text-white">{{ $karyawan->no_hp }}</div>
                    </div>
                    <div class="flex items-center justify-between gap-3">
                        <div class="text-black/55 dark:text-white/60">Tanggal Masuk</div>
                        <div class="font-semibold text-brand-navy dark:text-white">{{ $karyawan->tanggal_masuk?->format('d M Y') }}</div>
                    </div>
                    <div class="flex items-center justify-between gap-3">
                        <div class="text-black/55 dark:text-white/60">Gaji Harian</div>
                        <div class="font-bold text-brand-blue dark:text-brand-gold">Rp {{ number_format((int) $karyawan->gaji_harian, 0, ',', '.') }}</div>
                    </div>
                </div>

                <div class="mt-4 text-sm text-black/55 dark:text-white/60">
                    {{ $karyawan->alamat }}
                </div>
            </div>
        </div>

        <div class="glass-card lg:col-span-2">
            <div class="card-header">
                <div>
                    <div class="text-sm font-semibold text-black/55 dark:text-white/60">Riwayat</div>
                    <div class="text-lg font-bold tracking-tight text-brand-navy dark:text-white">Gaji</div>
                </div>
                <span class="badge-gold">{{ $karyawan->gaji->count() }}</span>
            </div>
            <div class="card-body">
                <div class="table-modern">
                    <table class="w-full">
                        <thead>
                            <tr>
                                <th>Periode</th>
                                <th>Nominal</th>
                                <th>Status</th>
                                <th>Tanggal Bayar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($karyawan->gaji->sortByDesc(fn($g) => $g->tahun * 100 + $g->bulan) as $g)
                                <tr>
                                    <td class="font-semibold">{{ str_pad((string) $g->bulan, 2, '0', STR_PAD_LEFT) }}/{{ $g->tahun }}</td>
                                    <td class="font-bold text-brand-blue dark:text-brand-gold">Rp {{ number_format((int) $g->nominal, 0, ',', '.') }}</td>
                                    <td><span class="badge-gold">{{ $g->status }}</span></td>
                                    <td class="text-black/55 dark:text-white/60">{{ $g->tanggal_bayar?->format('d M Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-10 text-center text-sm text-black/50 dark:text-white/60">
                                        Belum ada data gaji.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

