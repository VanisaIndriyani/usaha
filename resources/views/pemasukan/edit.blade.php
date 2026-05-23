<x-app-layout>
    <x-slot name="header">
        <div>
            <div class="text-sm font-semibold text-black/55 dark:text-white/60">Keuangan</div>
            <div class="text-xl font-bold tracking-tight text-brand-navy dark:text-white">Edit Pemasukan</div>
        </div>
    </x-slot>

    <div class="glass-card">
        <div class="card-body">
            <form method="POST" action="{{ route('pemasukan.update', $pemasukan) }}" enctype="multipart/form-data" class="space-y-5">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <x-input-label for="tanggal" value="Tanggal" />
                        <x-text-input id="tanggal" name="tanggal" type="date" class="mt-1 block w-full" :value="old('tanggal', $pemasukan->tanggal?->toDateString())" required />
                        <x-input-error class="mt-2" :messages="$errors->get('tanggal')" />
                    </div>
                </div>

                <div>
                    <x-input-label for="nama_pemasukan" value="Nama Pemasukan" />
                    <x-text-input id="nama_pemasukan" name="nama_pemasukan" type="text" class="mt-1 block w-full" :value="old('nama_pemasukan', $pemasukan->nama_pemasukan)" required />
                    <x-input-error class="mt-2" :messages="$errors->get('nama_pemasukan')" />
                </div>

                <div>
                    <x-input-label for="nominal" value="Nominal" />
                    <x-text-input id="nominal" name="nominal" type="text" inputmode="numeric" data-money class="mt-1 block w-full" :value="old('nominal', $pemasukan->nominal)" required />
                    <x-input-error class="mt-2" :messages="$errors->get('nominal')" />
                </div>

                <div>
                    <x-input-label for="catatan" value="Catatan" />
                    <textarea id="catatan" name="catatan" class="input mt-1" rows="4" placeholder="Opsional...">{{ old('catatan', $pemasukan->catatan) }}</textarea>
                    <x-input-error class="mt-2" :messages="$errors->get('catatan')" />
                </div>

                <div>
                    <x-input-label for="bukti" value="Upload Bukti (opsional)" />
                    @if ($pemasukan->bukti_path)
                        <div class="mt-2">
                            <a href="{{ asset('storage/' . $pemasukan->bukti_path) }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 text-sm font-semibold text-brand-blue hover:underline dark:text-brand-gold">
                                Lihat bukti saat ini
                            </a>
                        </div>
                    @endif
                    <input id="bukti" name="bukti" type="file" class="mt-2 block w-full text-sm text-black/60 file:mr-4 file:rounded-xl file:border-0 file:bg-brand-blue file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-brand-navy dark:text-white/70 dark:file:bg-white/10 dark:file:text-white dark:hover:file:bg-white/15" accept="image/*" />
                    <x-input-error class="mt-2" :messages="$errors->get('bukti')" />
                </div>

                <div class="flex items-center gap-2">
                    <button class="btn-primary" type="submit">Update</button>
                    <a href="{{ route('pemasukan.index') }}" class="btn-ghost">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
