<x-app-layout>
    <x-slot name="header">
        <div>
            <div class="text-sm font-semibold text-black/55 dark:text-white/60">Operasional</div>
            <div class="text-xl font-bold tracking-tight text-brand-navy dark:text-white">Tambah Catatan Stok</div>
        </div>
    </x-slot>

    <div class="glass-card">
        <div class="card-body">
            <form method="POST" action="{{ route('catatan-stok.store') }}" class="space-y-5">
                @csrf

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <x-input-label for="nama_item" value="Nama Item" />
                        <x-text-input id="nama_item" name="nama_item" type="text" class="mt-1 block w-full" :value="old('nama_item')" required />
                        <x-input-error class="mt-2" :messages="$errors->get('nama_item')" />
                    </div>

                    <div>
                        <x-input-label for="jenis" value="Jenis" />
                        <select id="jenis" name="jenis" class="input mt-1">
                            @foreach ($jenisList as $j)
                                <option value="{{ $j }}" @selected(old('jenis') === $j)>{{ $j }}</option>
                            @endforeach
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('jenis')" />
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <div>
                        <x-input-label for="jumlah" value="Jumlah" />
                        <x-text-input id="jumlah" name="jumlah" type="number" step="0.01" class="mt-1 block w-full" :value="old('jumlah', 0)" min="0" required />
                        <x-input-error class="mt-2" :messages="$errors->get('jumlah')" />
                    </div>
                    <div>
                        <x-input-label for="satuan" value="Satuan" />
                        <x-text-input id="satuan" name="satuan" type="text" class="mt-1 block w-full" :value="old('satuan', 'pcs')" required />
                        <x-input-error class="mt-2" :messages="$errors->get('satuan')" />
                    </div>
                    <div>
                        <x-input-label for="tanggal" value="Tanggal" />
                        <x-text-input id="tanggal" name="tanggal" type="date" class="mt-1 block w-full" :value="old('tanggal', now()->toDateString())" required />
                        <x-input-error class="mt-2" :messages="$errors->get('tanggal')" />
                    </div>
                </div>

                <div>
                    <x-input-label for="catatan" value="Catatan" />
                    <textarea id="catatan" name="catatan" class="input mt-1" rows="4" placeholder="Opsional...">{{ old('catatan') }}</textarea>
                    <x-input-error class="mt-2" :messages="$errors->get('catatan')" />
                </div>

                <div class="flex items-center gap-2">
                    <button class="btn-primary" type="submit">Simpan</button>
                    <a href="{{ route('catatan-stok.index') }}" class="btn-ghost">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

