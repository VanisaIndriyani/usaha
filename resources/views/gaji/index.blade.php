<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <div class="text-sm font-semibold text-black/55 dark:text-white/60">Keuangan</div>
                <div class="text-xl font-bold tracking-tight text-brand-navy dark:text-white">Sistem Gaji</div>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <form method="GET" action="{{ route('gaji.index') }}" class="flex flex-wrap items-center gap-2">
                    <input name="q" value="{{ $q }}" placeholder="Cari karyawan..." class="input w-56" />
                    <input name="bulan" value="{{ $bulan }}" class="input w-24" />
                    <input name="tahun" value="{{ $tahun }}" class="input w-28" />
                    <select name="status" class="input w-48">
                        <option value="">Semua status</option>
                        <option value="belum_dibayar" @selected($status === 'belum_dibayar')>belum_dibayar</option>
                        <option value="dibayar" @selected($status === 'dibayar')>dibayar</option>
                    </select>
                    <button class="btn-ghost" type="submit">Terapkan</button>
                </form>
                <form method="POST" action="{{ route('gaji.generate') }}" class="flex items-center gap-2">
                    @csrf
                    <input type="hidden" name="bulan" value="{{ $bulan }}">
                    <input type="hidden" name="tahun" value="{{ $tahun }}">
                    <button class="btn-ghost" type="submit">Generate</button>
                </form>
                <a href="{{ route('gaji.create', ['bulan' => $bulan, 'tahun' => $tahun]) }}" class="btn-primary">Tambah</a>
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
                    <div class="text-sm font-semibold text-black/55 dark:text-white/60">Periode</div>
                    <div class="text-lg font-bold tracking-tight text-brand-navy dark:text-white">{{ str_pad((string) $bulan, 2, '0', STR_PAD_LEFT) }}/{{ $tahun }}</div>
                </div>
                <span class="badge-gold">{{ $gaji->total() }}</span>
            </div>
            <div class="card-body">
                <div class="table-modern">
                    <table class="w-full">
                        <thead>
                            <tr>
                                <th>Karyawan</th>
                                <th>Nominal</th>
                                <th>Status</th>
                                <th>Tanggal Bayar</th>
                                <th class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($gaji as $row)
                                <tr>
                                    <td>
                                        <div class="font-bold">{{ $row->karyawan?->nama }}</div>
                                        <div class="mt-0.5 text-xs text-black/45 dark:text-white/55">{{ $row->karyawan?->jabatan }}</div>
                                    </td>
                                    <td class="font-bold text-brand-blue dark:text-brand-gold">{{ $idr((int) $row->nominal) }}</td>
                                    <td><span class="badge-gold">{{ $row->status }}</span></td>
                                    <td class="text-black/55 dark:text-white/60">{{ $row->tanggal_bayar?->format('d M Y') }}</td>
                                    <td class="text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('gaji.slip', $row) }}" class="btn-ghost px-3 py-2">Slip PDF</a>
                                            <a href="{{ route('gaji.edit', $row) }}" class="btn-ghost px-3 py-2">Edit</a>
                                            @if ($row->status !== 'dibayar')
                                                <form method="POST" action="{{ route('gaji.pay', $row) }}" class="flex items-center gap-2">
                                                    @csrf
                                                    <input type="date" name="tanggal_bayar" class="input w-44" value="{{ now()->toDateString() }}">
                                                    <button type="submit" class="btn-primary px-3 py-2">Bayar</button>
                                                </form>
                                            @endif
                                            <form method="POST" action="{{ route('gaji.destroy', $row) }}" x-data @submit.prevent="$store.ui.confirm({title:'Hapus gaji?', text:'Data gaji akan dihapus.'}).then(r=>{ if(r.isConfirmed) $el.submit() })">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-danger px-3 py-2">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-10 text-center text-sm text-black/50 dark:text-white/60">
                                        Belum ada data gaji. Klik Generate untuk membuat otomatis.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $gaji->links() }}
                </div>
            </div>
        </div>

        <div class="glass-card">
            <div class="card-header">
                <div>
                    <div class="text-sm font-semibold text-black/55 dark:text-white/60">Ringkasan</div>
                    <div class="text-lg font-bold tracking-tight text-brand-navy dark:text-white">Status Pembayaran</div>
                </div>
                <span class="badge-gold">IDR</span>
            </div>
            <div class="card-body space-y-3 text-sm">
                <div class="flex items-center justify-between gap-3">
                    <div class="text-black/55 dark:text-white/60">Dibayar</div>
                    <div class="font-bold text-brand-navy dark:text-white">{{ $idr($stats['dibayar']) }}</div>
                </div>
                <div class="flex items-center justify-between gap-3">
                    <div class="text-black/55 dark:text-white/60">Belum dibayar</div>
                    <div class="font-bold text-brand-navy dark:text-white">{{ $idr($stats['belum']) }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

