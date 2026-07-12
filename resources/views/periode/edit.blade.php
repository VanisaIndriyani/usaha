<x-app-layout>
    <x-slot name="header">
        <div>
            <div class="text-sm font-semibold text-black/55 dark:text-white/60">Pengaturan</div>
            <div class="text-xl font-bold tracking-tight text-brand-navy dark:text-white">Edit Periode</div>
        </div>
    </x-slot>

    <div class="glass-card">
        <div class="card-body">
            <form method="POST" action="{{ route('periode.update', $periode) }}" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <x-input-label for="nama" value="Nama Periode" />
                    <x-text-input id="nama" name="nama" type="text" class="mt-1 block w-full" :value="old('nama', $periode->nama)" placeholder="Contoh: Juli 2026" required />
                    <x-input-error class="mt-2" :messages="$errors->get('nama')" />
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <x-input-label for="tanggal_mulai" value="Tanggal Mulai" />
                        <x-text-input id="tanggal_mulai" name="tanggal_mulai" type="date" class="mt-1 block w-full" :value="old('tanggal_mulai', $periode->tanggal_mulai->toDateString())" required />
                        <x-input-error class="mt-2" :messages="$errors->get('tanggal_mulai')" />
                    </div>
                    <div>
                        <x-input-label for="tanggal_selesai" value="Tanggal Selesai" />
                        <x-text-input id="tanggal_selesai" name="tanggal_selesai" type="date" class="mt-1 block w-full" :value="old('tanggal_selesai', $periode->tanggal_selesai->toDateString())" required />
                        <x-input-error class="mt-2" :messages="$errors->get('tanggal_selesai')" />
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" name="is_active" value="1" @checked($periode->is_active) class="rounded border-gray-300 text-brand-navy focus:ring-brand-navy">
                        <span class="text-sm text-gray-700 dark:text-gray-300">Set sebagai periode aktif</span>
                    </label>
                </div>

                <div class="flex items-center gap-2">
                    <button class="btn-primary" type="submit">Simpan</button>
                    <a href="{{ route('periode.index') }}" class="btn-ghost">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
