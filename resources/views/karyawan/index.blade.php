<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <div class="text-sm font-semibold text-black/55 dark:text-white/60">SDM</div>
                <div class="text-xl font-bold tracking-tight text-brand-navy dark:text-white">Manajemen Karyawan</div>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <form method="GET" action="{{ route('karyawan.index') }}" class="flex flex-wrap items-center gap-2">
                    <input name="q" value="{{ $q }}" placeholder="Cari nama/email/HP..." class="input w-56" />
                    <select name="jabatan" class="input w-52">
                        <option value="">Semua jabatan</option>
                        @foreach ($jabatanList as $j)
                            <option value="{{ $j }}" @selected($jabatan === $j)>{{ $j }}</option>
                        @endforeach
                    </select>
                    <button class="btn-ghost" type="submit">Filter</button>
                </form>
                <a href="{{ route('karyawan.create') }}" class="btn-primary">Tambah</a>
            </div>
        </div>
    </x-slot>

    <div class="glass-card">
        <div class="card-header">
            <div>
                <div class="text-sm font-semibold text-black/55 dark:text-white/60">Data</div>
                <div class="text-lg font-bold tracking-tight text-brand-navy dark:text-white">Karyawan</div>
            </div>
            <span class="badge-gold">{{ $karyawan->total() }}</span>
        </div>
        <div class="card-body">
            <div class="table-modern">
                <table class="w-full">
                    <thead>
                        <tr>
                            <th>Foto</th>
                            <th>Nama</th>
                            <th>Jabatan</th>
                            <th>Status</th>
                            <th>Gaji Harian</th>
                            <th>Tanggal Masuk</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($karyawan as $row)
                            <tr>
                                <td>
                                    <div class="h-10 w-10 overflow-hidden rounded-xl bg-brand-gray/60 ring-1 ring-black/5 dark:bg-white/10 dark:ring-white/10">
                                        @if ($row->foto_path)
                                            <img src="{{ asset('storage/' . $row->foto_path) }}" alt="{{ $row->nama }}" class="h-full w-full object-cover">
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="font-bold">{{ $row->nama }}</div>
                                    <div class="mt-0.5 text-xs text-black/45 dark:text-white/55">{{ $row->email }}</div>
                                </td>
                                <td><span class="badge-gold">{{ $row->jabatan }}</span></td>
                                <td>
                                    <span class="badge-gold">{{ $row->status_kerja }}</span>
                                </td>
                                <td class="font-bold text-brand-blue dark:text-brand-gold">
                                    Rp {{ number_format((int) $row->gaji_harian, 0, ',', '.') }}
                                </td>
                                <td>{{ $row->tanggal_masuk?->format('d M Y') }}</td>
                                <td class="text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('karyawan.show', $row) }}" class="btn-ghost px-3 py-2">Detail</a>
                                        <a href="{{ route('karyawan.edit', $row) }}" class="btn-ghost px-3 py-2">Edit</a>
                                        <form method="POST" action="{{ route('karyawan.destroy', $row) }}" x-data @submit.prevent="$store.ui.confirm({title:'Hapus karyawan?', text:'Data karyawan akan dihapus.'}).then(r=>{ if(r.isConfirmed) $el.submit() })">
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
                                    Belum ada data karyawan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $karyawan->links() }}
            </div>
        </div>
    </div>
</x-app-layout>

