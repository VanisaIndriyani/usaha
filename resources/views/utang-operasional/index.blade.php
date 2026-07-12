<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <div class="text-sm font-semibold text-black/55">Pembiayaan</div>
                <div class="text-xl font-bold tracking-tight text-brand-navy">{{ $pageTitle }}</div>
            </div>
            <form method="GET" action="{{ $pihak === 'owner' ? route('utang-owner.index') : route('utang-kasir.index') }}" class="flex flex-wrap items-center gap-2">
                <input name="q" value="{{ $q }}" placeholder="Cari deskripsi..." class="input w-56" />
                <select name="status" class="input w-44">
                    <option value="">Semua status</option>
                    @foreach ($statusList as $key => $label)
                        <option value="{{ $key }}" @selected($status === $key)>{{ $label }}</option>
                    @endforeach
                </select>
                <input name="start" value="{{ $start }}" type="date" class="input w-40" />
                <input name="end" value="{{ $end }}" type="date" class="input w-40" />
                <button class="btn-ghost" type="submit">Terapkan</button>
            </form>
        </div>
    </x-slot>

    @php
        $idr = fn (int $value) => 'Rp ' . number_format($value, 0, ',', '.');
    @endphp

    <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
        <div class="glass-card">
            <div class="card-body">
                <div class="text-xs font-semibold uppercase tracking-wide text-black/45">Total {{ $pageLabel }}</div>
                <div class="mt-2 text-2xl font-extrabold text-brand-navy">{{ $idr($total) }}</div>
            </div>
        </div>
        <div class="glass-card">
            <div class="card-body">
                <div class="text-xs font-semibold uppercase tracking-wide text-black/45">Belum Lunas</div>
                <div class="mt-2 text-2xl font-extrabold text-amber-600">{{ $idr($belumLunas) }}</div>
            </div>
        </div>
        <div class="glass-card">
            <div class="card-body">
                <div class="text-xs font-semibold uppercase tracking-wide text-black/45">Lunas</div>
                <div class="mt-2 text-2xl font-extrabold text-emerald-600">{{ $idr($lunas) }}</div>
            </div>
        </div>
    </div>

    <div class="mt-4 glass-card">
        <div class="card-header">
            <div>
                <div class="text-sm font-semibold text-black/55">Data</div>
                <div class="text-lg font-bold tracking-tight text-brand-navy">{{ $pageTitle }}</div>
            </div>
            <span class="badge-gold">{{ $utang->total() }}</span>
        </div>
        <div class="card-body">
            <div class="table-modern">
                <table class="w-full">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Deskripsi</th>
                            <th>Nominal</th>
                            <th>Status</th>
                            <th>Catatan</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($utang as $row)
                            <tr>
                                <td>{{ $row->tanggal?->format('d M Y') }}</td>
                                <td class="font-bold">{{ $row->deskripsi }}</td>
                                <td class="font-bold text-brand-blue">{{ $idr((int) $row->nominal) }}</td>
                                <td>
                                    <span class="{{ $row->status === 'lunas' ? 'inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700' : 'inline-flex items-center rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-700' }}">
                                        {{ $row->status === 'lunas' ? 'Lunas' : 'Belum Lunas' }}
                                    </span>
                                </td>
                                <td class="text-black/55">{{ $row->catatan ?: '-' }}</td>
                                <td class="text-right">
                                    <form method="POST" action="{{ route('utang-operasional.toggle-status', $row) }}" x-data @submit.prevent="$store.ui.confirm({title:'Ubah status utang?', text:'Status utang akan diperbarui.'}).then(r=>{ if(r.isConfirmed) $el.submit() })">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="{{ $row->status === 'lunas' ? 'btn-ghost px-3 py-2' : 'btn-primary px-3 py-2' }}">
                                            {{ $row->status === 'lunas' ? 'Buka Lagi' : 'Tandai Lunas' }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-10 text-center text-sm text-black/50">
                                    Belum ada data utang {{ strtolower($pageLabel) }}.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $utang->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
