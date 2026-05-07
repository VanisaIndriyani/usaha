<x-app-layout>
    <x-slot name="header">
        <div>
            <div class="text-sm font-semibold text-black/55 dark:text-white/60">Keuangan</div>
            <div class="text-xl font-bold tracking-tight text-brand-navy dark:text-white">Tambah Pengeluaran</div>
        </div>
    </x-slot>

    <div class="glass-card">
        <div class="card-body">
            <form method="POST" action="{{ route('pengeluaran.store') }}" class="space-y-5">
                @csrf

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <x-input-label for="tanggal" value="Tanggal" />
                        <x-text-input id="tanggal" name="tanggal" type="date" class="mt-1 block w-full" :value="old('tanggal', now()->toDateString())" required />
                        <x-input-error class="mt-2" :messages="$errors->get('tanggal')" />
                    </div>
                    <div>
                        <x-input-label for="kategori" value="Kategori" />
                        <select id="kategori" name="kategori" class="input mt-1">
                            @foreach ($kategoriList as $k)
                                <option value="{{ $k }}" @selected(old('kategori') === $k)>{{ $k }}</option>
                            @endforeach
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('kategori')" />
                    </div>
                </div>

                <div>
                    <x-input-label for="nama_pengeluaran" value="Nama Pengeluaran" />
                    <x-text-input id="nama_pengeluaran" name="nama_pengeluaran" type="text" class="mt-1 block w-full" :value="old('nama_pengeluaran')" required />
                    <x-input-error class="mt-2" :messages="$errors->get('nama_pengeluaran')" />
                </div>

                <div>
                    <x-input-label for="nominal" value="Nominal" />
                    <x-text-input id="nominal" name="nominal" type="number" class="mt-1 block w-full" :value="old('nominal')" min="1" required />
                    <x-input-error class="mt-2" :messages="$errors->get('nominal')" />
                </div>

                <div>
                    <x-input-label for="catatan" value="Catatan" />
                    <textarea id="catatan" name="catatan" class="input mt-1" rows="4" placeholder="Opsional...">{{ old('catatan') }}</textarea>
                    <x-input-error class="mt-2" :messages="$errors->get('catatan')" />
                </div>

                <div class="flex items-center gap-2">
                    <button class="btn-primary" type="submit">Simpan</button>
                    <a href="{{ route('pengeluaran.index') }}" class="btn-ghost">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

