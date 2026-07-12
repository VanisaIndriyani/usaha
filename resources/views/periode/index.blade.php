<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <div class="text-sm font-semibold text-black/55 dark:text-white/60">Pengaturan</div>
                <div class="text-xl font-bold tracking-tight text-brand-navy dark:text-white">Periode</div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('periode.create') }}" class="btn-primary">Tambah Periode</a>
            </div>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 gap-4">
        <div class="glass-card">
            <div class="card-header">
                <div>
                    <div class="text-sm font-semibold text-black/55 dark:text-white/60">Status</div>
                    <div class="text-lg font-bold tracking-tight text-brand-navy dark:text-white">Periode Aktif</div>
                </div>
                @if($activePeriode)
                    <span class="badge-gold">{{ $activePeriode->nama }}</span>
                @else
                    <span class="px-3 py-1 rounded-full bg-gray-200 text-gray-600 text-xs font-semibold">Tidak ada periode aktif</span>
                @endif
            </div>
            <div class="card-body text-sm text-black/55 dark:text-white/60">
                @if($activePeriode)
                    Periode aktif: {{ $activePeriode->nama }} ({{ $activePeriode->tanggal_mulai->format('d M Y') }} - {{ $activePeriode->tanggal_selesai->format('d M Y') }})
                @else
                    Silakan buat periode baru dan aktifkan.
                @endif
            </div>
        </div>

        <div class="glass-card">
            <div class="card-header">
                <div>
                    <div class="text-sm font-semibold text-black/55 dark:text-white/60">Daftar</div>
                    <div class="text-lg font-bold tracking-tight text-brand-navy dark:text-white">Semua Periode</div>
                </div>
                <span class="badge-gold">{{ $periodes->count() }}</span>
            </div>
            <div class="card-body">
                <div class="table-modern">
                    <table class="w-full">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Tanggal Mulai</th>
                                <th>Tanggal Selesai</th>
                                <th>Status</th>
                                <th class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($periodes as $periode)
                                <tr>
                                    <td class="font-semibold">{{ $periode->nama }}</td>
                                    <td>{{ $periode->tanggal_mulai->format('d M Y') }}</td>
                                    <td>{{ $periode->tanggal_selesai->format('d M Y') }}</td>
                                    <td>
                                        @if($periode->is_active)
                                            <span class="px-2 py-1 rounded-full bg-green-100 text-green-800 text-xs font-semibold">Aktif</span>
                                        @else
                                            <span class="px-2 py-1 rounded-full bg-gray-100 text-gray-600 text-xs font-semibold">Non-aktif</span>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            @if(!$periode->is_active)
                                                <form method="POST" action="{{ route('periode.activate', $periode) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn-ghost px-3 py-2">Aktifkan</button>
                                                </form>
                                            @endif
                                            <a href="{{ route('periode.edit', $periode) }}" class="btn-ghost px-3 py-2">Edit</a>
                                            <form method="POST" action="{{ route('periode.destroy', $periode) }}" x-data @submit.prevent="$store.ui.confirm({title:'Hapus periode?', text:'Data periode akan dihapus.'}).then(r=>{ if(r.isConfirmed) $el.submit() })">
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
                                        Belum ada periode.
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
