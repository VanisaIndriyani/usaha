<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <div class="text-sm font-semibold text-black/55 dark:text-white/60">Keuangan</div>
                <div class="text-xl font-bold tracking-tight text-brand-navy dark:text-white">Modal Usaha</div>
            </div>
            <div class="flex items-center gap-2">
                <form method="GET" action="{{ route('modal-usaha.index') }}" class="flex items-center gap-2">
                    <input name="q" value="{{ $q }}" placeholder="Cari owner / catatan..." class="input w-56" />
                    <button class="btn-ghost" type="submit">Cari</button>
                </form>
                <a href="{{ route('modal-usaha.create') }}" class="btn-primary">Tambah</a>
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
                    <div class="text-sm font-semibold text-black/55 dark:text-white/60">Ringkasan</div>
                    <div class="text-lg font-bold tracking-tight text-brand-navy dark:text-white">Total Modal</div>
                </div>
                <span class="badge-gold">{{ $idr($totalAll) }}</span>
            </div>
            <div class="card-body">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    @foreach ($owners as $owner)
                        @php
                            $v = (int) ($totals[$owner->id] ?? 0);
                            $p = (float) ($percent[$owner->id] ?? 0);
                        @endphp
                        <div class="glass-card">
                            <div class="card-body">
                                <div class="flex items-center justify-between gap-3">
                                    <div class="min-w-0">
                                        <div class="truncate text-sm font-semibold text-black/55 dark:text-white/60">{{ $owner->name }}</div>
                                        <div class="mt-1 text-2xl font-extrabold tracking-tight text-brand-navy dark:text-white">{{ $idr($v) }}</div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-xs font-semibold uppercase tracking-wide text-black/45 dark:text-white/55">Kepemilikan</div>
                                        <div class="mt-1 text-lg font-bold text-brand-blue dark:text-brand-gold">{{ number_format($p, 2, ',', '.') }}%</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="glass-card">
            <div class="card-header">
                <div>
                    <div class="text-sm font-semibold text-black/55 dark:text-white/60">Tips</div>
                    <div class="text-lg font-bold tracking-tight text-brand-navy dark:text-white">Otomatis</div>
                </div>
                <span class="badge-gold">%</span>
            </div>
            <div class="card-body text-sm text-black/55 dark:text-white/60">
                Persentase kepemilikan dihitung otomatis dari total modal masing-masing owner.
            </div>
        </div>
    </div>

    <div class="mt-4 glass-card">
        <div class="card-header">
            <div>
                <div class="text-sm font-semibold text-black/55 dark:text-white/60">Riwayat</div>
                <div class="text-lg font-bold tracking-tight text-brand-navy dark:text-white">Transaksi Modal</div>
            </div>
            <span class="badge-gold">{{ $modal->total() }}</span>
        </div>
        <div class="card-body">
            <div class="table-modern">
                <table class="w-full">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Owner</th>
                            <th>Nominal</th>
                            <th>Catatan</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($modal as $row)
                            <tr>
                                <td>{{ $row->tanggal?->format('d M Y') }}</td>
                                <td class="font-semibold">{{ $row->owner?->name }}</td>
                                <td class="font-bold text-brand-blue dark:text-brand-gold">{{ $idr((int) $row->nominal) }}</td>
                                <td class="text-black/55 dark:text-white/60">{{ $row->catatan }}</td>
                                <td class="text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('modal-usaha.edit', $row) }}" class="btn-ghost px-3 py-2">Edit</a>
                                        <form method="POST" action="{{ route('modal-usaha.destroy', $row) }}" x-data @submit.prevent="$store.ui.confirm({title:'Hapus modal?', text:'Data modal akan dihapus.'}).then(r=>{ if(r.isConfirmed) $el.submit() })">
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
                                    Belum ada data modal usaha.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $modal->links() }}
            </div>
        </div>
    </div>
</x-app-layout>

