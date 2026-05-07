<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3">
            <div>
                <div class="text-sm font-semibold text-black/55 dark:text-white/60">Keuangan</div>
                <div class="text-xl font-bold tracking-tight text-brand-navy dark:text-white">Profit Sharing Owner</div>
            </div>
            <a href="{{ route('profit-sharing.create') }}" class="btn-primary">Buat</a>
        </div>
    </x-slot>

    @php
        $idr = fn (int $v) => 'Rp ' . number_format($v, 0, ',', '.');
        $ownerA = $owners[0] ?? null;
        $ownerB = $owners[1] ?? null;
    @endphp

    <div class="glass-card">
        <div class="card-header">
            <div>
                <div class="text-sm font-semibold text-black/55 dark:text-white/60">Riwayat</div>
                <div class="text-lg font-bold tracking-tight text-brand-navy dark:text-white">Pembagian Keuntungan</div>
            </div>
            <span class="badge-gold">{{ $items->total() }}</span>
        </div>
        <div class="card-body">
            <div class="table-modern">
                <table class="w-full">
                    <thead>
                        <tr>
                            <th>Periode</th>
                            <th>Laba Bersih</th>
                            <th>{{ $ownerA?->name ?? 'Owner A' }}</th>
                            <th>{{ $ownerB?->name ?? 'Owner B' }}</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($items as $row)
                            <tr>
                                <td class="font-semibold">
                                    {{ $row->periode_mulai?->format('d M Y') }} – {{ $row->periode_selesai?->format('d M Y') }}
                                </td>
                                <td class="font-bold text-brand-blue dark:text-brand-gold">{{ $idr((int) $row->laba_bersih) }}</td>
                                <td>
                                    <div class="font-bold">{{ $idr((int) $row->owner_a_nominal) }}</div>
                                    <div class="text-xs text-black/45 dark:text-white/55">{{ number_format((float) $row->owner_a_persen, 2, ',', '.') }}%</div>
                                </td>
                                <td>
                                    <div class="font-bold">{{ $idr((int) $row->owner_b_nominal) }}</div>
                                    <div class="text-xs text-black/45 dark:text-white/55">{{ number_format((float) $row->owner_b_persen, 2, ',', '.') }}%</div>
                                </td>
                                <td class="text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('profit-sharing.edit', $row) }}" class="btn-ghost px-3 py-2">Edit</a>
                                        <form method="POST" action="{{ route('profit-sharing.destroy', $row) }}" x-data @submit.prevent="$store.ui.confirm({title:'Hapus profit sharing?', text:'Riwayat ini akan dihapus.'}).then(r=>{ if(r.isConfirmed) $el.submit() })">
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
                                    Belum ada data profit sharing.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $items->links() }}
            </div>
        </div>
    </div>
</x-app-layout>

