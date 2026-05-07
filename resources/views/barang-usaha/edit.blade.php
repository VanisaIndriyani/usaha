<x-app-layout>
    <x-slot name="header">
        <div>
            <div class="text-sm font-semibold text-black/55 dark:text-white/60">Operasional</div>
            <div class="text-xl font-bold tracking-tight text-brand-navy dark:text-white">Edit Barang</div>
        </div>
    </x-slot>

    <div class="glass-card">
        <div class="card-body">
            <form method="POST" action="{{ route('barang-usaha.update', $barangUsaha) }}" enctype="multipart/form-data" class="space-y-5">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <x-input-label for="nama_barang" value="Nama Barang" />
                        <x-text-input id="nama_barang" name="nama_barang" type="text" class="mt-1 block w-full" :value="old('nama_barang', $barangUsaha->nama_barang)" required />
                        <x-input-error class="mt-2" :messages="$errors->get('nama_barang')" />
                    </div>

                    <div>
                        <x-input-label for="kategori" value="Kategori" />
                        <select id="kategori" name="kategori" class="input mt-1">
                            @foreach ($kategoriList as $k)
                                <option value="{{ $k }}" @selected(old('kategori', $barangUsaha->kategori) === $k)>{{ $k }}</option>
                            @endforeach
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('kategori')" />
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <x-input-label for="harga" value="Harga" />
                        <x-text-input id="harga" name="harga" type="number" class="mt-1 block w-full" :value="old('harga', $barangUsaha->harga)" min="0" required />
                        <x-input-error class="mt-2" :messages="$errors->get('harga')" />
                    </div>
                    <div>
                        <x-input-label for="jumlah" value="Jumlah" />
                        <x-text-input id="jumlah" name="jumlah" type="number" class="mt-1 block w-full" :value="old('jumlah', $barangUsaha->jumlah)" min="1" required />
                        <x-input-error class="mt-2" :messages="$errors->get('jumlah')" />
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <x-input-label for="supplier" value="Supplier" />
                        <x-text-input id="supplier" name="supplier" type="text" class="mt-1 block w-full" :value="old('supplier', $barangUsaha->supplier)" />
                        <x-input-error class="mt-2" :messages="$errors->get('supplier')" />
                    </div>
                    <div>
                        <x-input-label for="tanggal_beli" value="Tanggal Beli" />
                        <x-text-input id="tanggal_beli" name="tanggal_beli" type="date" class="mt-1 block w-full" :value="old('tanggal_beli', $barangUsaha->tanggal_beli?->toDateString())" required />
                        <x-input-error class="mt-2" :messages="$errors->get('tanggal_beli')" />
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <div class="h-14 w-14 overflow-hidden rounded-2xl bg-brand-gray/60 ring-1 ring-black/5 dark:bg-white/10 dark:ring-white/10">
                        @if ($barangUsaha->foto_path)
                            <img src="{{ asset('storage/' . $barangUsaha->foto_path) }}" alt="{{ $barangUsaha->nama_barang }}" class="h-full w-full object-cover">
                        @endif
                    </div>
                    <div class="flex-1">
                        <x-input-label for="foto" value="Ganti Foto" />
                        <input id="foto" name="foto" type="file" class="mt-1 block w-full text-sm text-black/60 file:mr-4 file:rounded-xl file:border-0 file:bg-brand-blue file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-brand-navy dark:text-white/70 dark:file:bg-white/10 dark:file:text-white dark:hover:file:bg-white/15" accept="image/*" />
                        <x-input-error class="mt-2" :messages="$errors->get('foto')" />
                    </div>
                </div>

                <div>
                    <x-input-label for="catatan" value="Catatan" />
                    <textarea id="catatan" name="catatan" class="input mt-1" rows="4" placeholder="Opsional...">{{ old('catatan', $barangUsaha->catatan) }}</textarea>
                    <x-input-error class="mt-2" :messages="$errors->get('catatan')" />
                </div>

                <div class="flex items-center gap-2">
                    <button class="btn-primary" type="submit">Update</button>
                    <a href="{{ route('barang-usaha.index') }}" class="btn-ghost">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

