<x-app-layout>
    <x-slot name="header">
        <div>
            <div class="text-sm font-semibold text-black/55 dark:text-white/60">Keuangan</div>
            <div class="text-xl font-bold tracking-tight text-brand-navy dark:text-white">Buat Profit Sharing</div>
        </div>
    </x-slot>

    <div class="glass-card">
        <div class="card-body">
            <form method="POST" action="{{ route('profit-sharing.store') }}" class="space-y-5">
                @csrf

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <x-input-label for="periode_mulai" value="Periode Mulai" />
                        <x-text-input id="periode_mulai" name="periode_mulai" type="date" class="mt-1 block w-full" :value="old('periode_mulai', now()->startOfMonth()->toDateString())" required />
                        <x-input-error class="mt-2" :messages="$errors->get('periode_mulai')" />
                    </div>
                    <div>
                        <x-input-label for="periode_selesai" value="Periode Selesai" />
                        <x-text-input id="periode_selesai" name="periode_selesai" type="date" class="mt-1 block w-full" :value="old('periode_selesai', now()->endOfMonth()->toDateString())" required />
                        <x-input-error class="mt-2" :messages="$errors->get('periode_selesai')" />
                    </div>
                </div>

                <div>
                    <x-input-label for="laba_bersih" value="Laba Bersih" />
                    <x-text-input id="laba_bersih" name="laba_bersih" type="number" class="mt-1 block w-full" :value="old('laba_bersih', 0)" min="0" required />
                    <x-input-error class="mt-2" :messages="$errors->get('laba_bersih')" />
                    <div class="mt-2 text-xs text-black/45 dark:text-white/55">
                        Pembagian otomatis mengikuti total modal masing-masing owner (hingga tanggal periode selesai).
                    </div>
                </div>

                <div>
                    <x-input-label for="catatan" value="Catatan" />
                    <textarea id="catatan" name="catatan" class="input mt-1" rows="4">{{ old('catatan') }}</textarea>
                    <x-input-error class="mt-2" :messages="$errors->get('catatan')" />
                </div>

                <div class="flex items-center gap-2">
                    <button class="btn-primary" type="submit">Buat</button>
                    <a href="{{ route('profit-sharing.index') }}" class="btn-ghost">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

