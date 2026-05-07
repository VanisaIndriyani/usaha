<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <div class="text-sm font-semibold text-black/55 dark:text-white/60">Operasional</div>
                <div class="text-xl font-bold tracking-tight text-brand-navy dark:text-white">Barang & Kebutuhan Usaha</div>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <form method="GET" action="{{ route('barang-usaha.index') }}" class="flex flex-wrap items-center gap-2">
                    <input name="q" value="{{ $q }}" placeholder="Cari barang / supplier..." class="input w-56" />
                    <select name="kategori" class="input w-52">
                        <option value="">Semua kategori</option>
                        @foreach ($kategoriList as $k)
                            <option value="{{ $k }}" @selected($kategori === $k)>{{ $k }}</option>
                        @endforeach
                    </select>
                    <button class="btn-ghost" type="submit">Filter</button>
                </form>
                <a href="{{ route('barang-usaha.create') }}" class="btn-primary">Tambah</a>
            </div>
        </div>
    </x-slot>

    @php
        $idr = fn (int $v) => 'Rp ' . number_format($v, 0, ',', '.');
    @endphp

    <div class="glass-card">
        <div class="card-header">
            <div>
                <div class="text-sm font-semibold text-black/55 dark:text-white/60">Total</div>
                <div class="text-lg font-bold tracking-tight text-brand-navy dark:text-white">Pengeluaran Barang</div>
            </div>
            <span class="badge-gold">{{ $idr($totalPengeluaran) }}</span>
        </div>
        <div class="card-body">
            <div class="table-modern">
                <table class="w-full">
                    <thead>
                        <tr>
                            <th>Foto</th>
                            <th>Nama</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Jumlah</th>
                            <th>Total</th>
                            <th>Tanggal</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($barang as $row)
                            <tr>
                                <td>
                                    <div class="h-10 w-10 overflow-hidden rounded-xl bg-brand-gray/60 ring-1 ring-black/5 dark:bg-white/10 dark:ring-white/10">
                                        @if ($row->foto_path)
                                            <img src="{{ asset('storage/' . $row->foto_path) }}" alt="{{ $row->nama_barang }}" class="h-full w-full object-cover">
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="font-bold">{{ $row->nama_barang }}</div>
                                    <div class="mt-0.5 text-xs text-black/45 dark:text-white/55">{{ $row->supplier }}</div>
                                </td>
                                <td><span class="badge-gold">{{ $row->kategori }}</span></td>
                                <td class="font-semibold">{{ $idr((int) $row->harga) }}</td>
                                <td>{{ number_format((int) $row->jumlah, 0, ',', '.') }}</td>
                                <td class="font-bold text-brand-blue dark:text-brand-gold">{{ $idr((int) $row->total) }}</td>
                                <td>{{ $row->tanggal_beli?->format('d M Y') }}</td>
                                <td class="text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('barang-usaha.edit', $row) }}" class="btn-ghost px-3 py-2">Edit</a>
                                        <form method="POST" action="{{ route('barang-usaha.destroy', $row) }}" x-data @submit.prevent="$store.ui.confirm({title:'Hapus barang?', text:'Data barang akan dihapus.'}).then(r=>{ if(r.isConfirmed) $el.submit() })">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-danger px-3 py-2">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-10 text-center text-sm text-black/50 dark:text-white/60">
                                    Belum ada data barang usaha.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $barang->links() }}
            </div>
        </div>
    </div>
</x-app-layout>

