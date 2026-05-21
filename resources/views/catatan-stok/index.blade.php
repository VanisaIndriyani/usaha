<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <div class="text-sm font-semibold text-black/55 dark:text-white/60">Operasional</div>
                <div class="text-xl font-bold tracking-tight text-brand-navy dark:text-white">Catatan Stok</div>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <form method="GET" action="{{ route('catatan-stok.index') }}" class="flex flex-wrap items-center gap-2">
                    <input name="q" value="{{ $q }}" placeholder="Cari item / catatan..." class="input w-56" />
                    <select name="jenis" class="input w-48">
                        <option value="">Semua jenis</option>
                        @foreach ($jenisList as $j)
                            <option value="{{ $j }}" @selected($jenis === $j)>{{ $j }}</option>
                        @endforeach
                    </select>
                    <input name="start" value="{{ $start }}" type="date" class="input w-44" />
                    <input name="end" value="{{ $end }}" type="date" class="input w-44" />
                    <button class="btn-ghost" type="submit">Terapkan</button>
                </form>
                <a href="{{ route('catatan-stok.create') }}" class="btn-primary">Tambah</a>
            </div>
        </div>
    </x-slot>

    <div class="glass-card">
        <div class="card-header">
            <div>
                <div class="text-sm font-semibold text-black/55 dark:text-white/60">Data</div>
                <div class="text-lg font-bold tracking-tight text-brand-navy dark:text-white">Catatan Stok</div>
            </div>
            <span class="badge-gold">{{ $catatan->total() }}</span>
        </div>
        <div class="card-body">
            <div class="table-modern">
                <table class="w-full">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Item</th>
                            <th>Jenis</th>
                            <th>Jumlah</th>
                            <th>Satuan</th>
                            <th>Catatan</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($catatan as $row)
                            <tr>
                                <td>{{ $row->tanggal?->format('d M Y') }}</td>
                                <td class="font-bold">{{ $row->nama_item }}</td>
                                <td><span class="badge-gold">{{ $row->jenis }}</span></td>
                                <td class="font-semibold">{{ number_format((float) $row->jumlah, 2, ',', '.') }}</td>
                                <td class="text-black/55 dark:text-white/60">{{ $row->satuan }}</td>
                                <td class="text-black/55 dark:text-white/60">{{ $row->catatan }}</td>
                                <td class="text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('catatan-stok.edit', $row) }}" class="btn-ghost px-3 py-2">Edit</a>
                                        <form method="POST" action="{{ route('catatan-stok.destroy', $row) }}" x-data @submit.prevent="$store.ui.confirm({title:'Hapus catatan?', text:'Catatan stok akan dihapus.'}).then(r=>{ if(r.isConfirmed) $el.submit() })">
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
                                    Belum ada catatan stok.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $catatan->links() }}
            </div>
        </div>
    </div>
</x-app-layout>

