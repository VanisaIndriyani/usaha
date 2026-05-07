<x-app-layout>
    <x-slot name="header">
        <div>
            <div class="text-sm font-semibold text-black/55 dark:text-white/60">Keuangan</div>
            <div class="text-xl font-bold tracking-tight text-brand-navy dark:text-white">Edit Profit Sharing</div>
        </div>
    </x-slot>

    @php
        $idr = fn (int $v) => 'Rp ' . number_format($v, 0, ',', '.');
        $ownerA = $owners[0] ?? null;
        $ownerB = $owners[1] ?? null;
    @endphp

    <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
        <div class="glass-card lg:col-span-2">
            <div class="card-body">
                <form method="POST" action="{{ route('profit-sharing.update', $profitSharing) }}" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <x-input-label for="periode_mulai" value="Periode Mulai" />
                            <x-text-input id="periode_mulai" name="periode_mulai" type="date" class="mt-1 block w-full" :value="old('periode_mulai', $profitSharing->periode_mulai?->toDateString())" required />
                            <x-input-error class="mt-2" :messages="$errors->get('periode_mulai')" />
                        </div>
                        <div>
                            <x-input-label for="periode_selesai" value="Periode Selesai" />
                            <x-text-input id="periode_selesai" name="periode_selesai" type="date" class="mt-1 block w-full" :value="old('periode_selesai', $profitSharing->periode_selesai?->toDateString())" required />
                            <x-input-error class="mt-2" :messages="$errors->get('periode_selesai')" />
                        </div>
                    </div>

                    <div>
                        <x-input-label for="laba_bersih" value="Laba Bersih" />
                        <x-text-input id="laba_bersih" name="laba_bersih" type="number" class="mt-1 block w-full" :value="old('laba_bersih', $profitSharing->laba_bersih)" min="0" required />
                        <x-input-error class="mt-2" :messages="$errors->get('laba_bersih')" />
                    </div>

                    <div>
                        <x-input-label for="catatan" value="Catatan" />
                        <textarea id="catatan" name="catatan" class="input mt-1" rows="4">{{ old('catatan', $profitSharing->catatan) }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('catatan')" />
                    </div>

                    <div class="flex items-center gap-2">
                        <button class="btn-primary" type="submit">Update</button>
                        <a href="{{ route('profit-sharing.index') }}" class="btn-ghost">Kembali</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="glass-card">
            <div class="card-header">
                <div>
                    <div class="text-sm font-semibold text-black/55 dark:text-white/60">Preview</div>
                    <div class="text-lg font-bold tracking-tight text-brand-navy dark:text-white">Hasil Tersimpan</div>
                </div>
                <span class="badge-gold">{{ $idr((int) $profitSharing->laba_bersih) }}</span>
            </div>
            <div class="card-body space-y-3 text-sm">
                <div class="flex items-center justify-between gap-3">
                    <div class="text-black/55 dark:text-white/60">{{ $ownerA?->name ?? 'Owner A' }}</div>
                    <div class="font-bold text-brand-navy dark:text-white">{{ $idr((int) $profitSharing->owner_a_nominal) }} ({{ number_format((float) $profitSharing->owner_a_persen, 2, ',', '.') }}%)</div>
                </div>
                <div class="flex items-center justify-between gap-3">
                    <div class="text-black/55 dark:text-white/60">{{ $ownerB?->name ?? 'Owner B' }}</div>
                    <div class="font-bold text-brand-navy dark:text-white">{{ $idr((int) $profitSharing->owner_b_nominal) }} ({{ number_format((float) $profitSharing->owner_b_persen, 2, ',', '.') }}%)</div>
                </div>
                <div class="flex items-center justify-between gap-3">
                    <div class="text-black/55 dark:text-white/60">Total Modal (basis)</div>
                    <div class="font-bold text-brand-navy dark:text-white">{{ $idr((int) $profitSharing->total_modal) }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

