<x-app-layout>
    <x-slot name="header">
        <div>
            <div class="text-sm font-semibold text-black/55 dark:text-white/60">SDM</div>
            <div class="text-xl font-bold tracking-tight text-brand-navy dark:text-white">Edit Karyawan</div>
        </div>
    </x-slot>

    <div class="glass-card">
        <div class="card-body">
            <form method="POST" action="{{ route('karyawan.update', $karyawan) }}" enctype="multipart/form-data" class="space-y-5">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <x-input-label for="nama" value="Nama" />
                        <x-text-input id="nama" name="nama" type="text" class="mt-1 block w-full" :value="old('nama', $karyawan->nama)" required />
                        <x-input-error class="mt-2" :messages="$errors->get('nama')" />
                    </div>
                    <div>
                        <x-input-label for="jabatan" value="Jabatan" />
                        <x-text-input id="jabatan" name="jabatan" type="text" class="mt-1 block w-full" :value="old('jabatan', $karyawan->jabatan)" required />
                        <x-input-error class="mt-2" :messages="$errors->get('jabatan')" />
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <x-input-label for="no_hp" value="No HP" />
                        <x-text-input id="no_hp" name="no_hp" type="text" class="mt-1 block w-full" :value="old('no_hp', $karyawan->no_hp)" />
                        <x-input-error class="mt-2" :messages="$errors->get('no_hp')" />
                    </div>
                    <div>
                        <x-input-label for="tanggal_masuk" value="Tanggal Masuk" />
                        <x-text-input id="tanggal_masuk" name="tanggal_masuk" type="date" class="mt-1 block w-full" :value="old('tanggal_masuk', $karyawan->tanggal_masuk?->toDateString())" required />
                        <x-input-error class="mt-2" :messages="$errors->get('tanggal_masuk')" />
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <x-input-label for="status_kerja" value="Status Kerja" />
                        <select id="status_kerja" name="status_kerja" class="input mt-1">
                            <option value="aktif" @selected(old('status_kerja', $karyawan->status_kerja) === 'aktif')>aktif</option>
                            <option value="nonaktif" @selected(old('status_kerja', $karyawan->status_kerja) === 'nonaktif')>nonaktif</option>
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('status_kerja')" />
                    </div>
                    <div>
                        <x-input-label for="gaji_harian" value="Gaji Harian" />
                        <x-text-input id="gaji_harian" name="gaji_harian" type="number" class="mt-1 block w-full" :value="old('gaji_harian', $karyawan->gaji_harian)" min="0" required />
                        <x-input-error class="mt-2" :messages="$errors->get('gaji_harian')" />
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div class="col-span-2">
                        <x-input-label for="foto" value="Foto" />
                        <div class="mt-1 flex items-center gap-4">
                            <div class="h-12 w-12 overflow-hidden rounded-2xl bg-brand-gray/60 ring-1 ring-black/5 dark:bg-white/10 dark:ring-white/10">
                                @if ($karyawan->foto_path)
                                    <img src="{{ asset('storage/' . $karyawan->foto_path) }}" alt="{{ $karyawan->nama }}" class="h-full w-full object-cover">
                                @endif
                            </div>
                            <input id="foto" name="foto" type="file" class="block w-full text-sm text-black/60 file:mr-4 file:rounded-xl file:border-0 file:bg-brand-blue file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-brand-navy dark:text-white/70 dark:file:bg-white/10 dark:file:text-white dark:hover:file:bg-white/15" accept="image/*" />
                        </div>
                        <x-input-error class="mt-2" :messages="$errors->get('foto')" />
                    </div>
                </div>

                <div>
                    <x-input-label for="alamat" value="Alamat" />
                    <textarea id="alamat" name="alamat" class="input mt-1" rows="3">{{ old('alamat', $karyawan->alamat) }}</textarea>
                    <x-input-error class="mt-2" :messages="$errors->get('alamat')" />
                </div>

                <div class="flex items-center gap-2">
                    <button class="btn-primary" type="submit">Update</button>
                    <a href="{{ route('karyawan.index') }}" class="btn-ghost">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

