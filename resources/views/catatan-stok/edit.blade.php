<x-app-layout>
    <x-slot name="header">
        <div>
            <div class="text-sm font-semibold text-black/55 dark:text-white/60">Operasional</div>
            <div class="text-xl font-bold tracking-tight text-brand-navy dark:text-white">Edit Catatan Stok</div>
        </div>
    </x-slot>

    <div class="glass-card" x-data="{ jenisStok: @js(old('jenis', $catatanStok->jenis)) }">
        <div class="card-body">
            <form method="POST" action="{{ route('catatan-stok.update', $catatanStok) }}" enctype="multipart/form-data" class="space-y-5">
                @csrf
                @method('PATCH')

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <x-input-label for="nama_item" value="Nama Item" />
                        <x-text-input id="nama_item" name="nama_item" type="text" class="mt-1 block w-full" :value="old('nama_item', $catatanStok->nama_item)" required />
                        <x-input-error class="mt-2" :messages="$errors->get('nama_item')" />
                    </div>

                    <div>
                        <x-input-label for="jenis" value="Jenis" />
                        <select id="jenis" name="jenis" class="input mt-1" x-model="jenisStok">
                            @foreach ($jenisList as $j)
                                <option value="{{ $j }}" @selected(old('jenis', $catatanStok->jenis) === $j)>{{ $j }}</option>
                            @endforeach
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('jenis')" />
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <div>
                        <x-input-label for="jumlah" value="Jumlah" />
                        <x-text-input id="jumlah" name="jumlah" type="number" step="0.01" class="mt-1 block w-full" :value="old('jumlah', $catatanStok->jumlah)" min="0" required />
                        <x-input-error class="mt-2" :messages="$errors->get('jumlah')" />
                    </div>
                    <div>
                        <x-input-label for="satuan" value="Satuan" />
                        <x-text-input id="satuan" name="satuan" type="text" class="mt-1 block w-full" :value="old('satuan', $catatanStok->satuan)" required />
                        <x-input-error class="mt-2" :messages="$errors->get('satuan')" />
                    </div>
                    <div>
                        <x-input-label for="tanggal" value="Tanggal" />
                        <x-text-input id="tanggal" name="tanggal" type="date" class="mt-1 block w-full" :value="old('tanggal', $catatanStok->tanggal?->toDateString())" required />
                        <x-input-error class="mt-2" :messages="$errors->get('tanggal')" />
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2" x-show="jenisStok === 'Pembelian'" x-cloak>
                    <div>
                        <x-input-label for="nominal" value="Nominal Pembelian" />
                        <x-text-input id="nominal" name="nominal" type="text" class="mt-1 block w-full money-input" :value="old('nominal', $catatanStok->nominal)" data-money required />
                        <div class="mt-1 text-xs text-black/45">Jika sumber dana owner atau kasir, utang akan ikut diperbarui otomatis.</div>
                        <x-input-error class="mt-2" :messages="$errors->get('nominal')" />
                    </div>
                    <div>
                        <x-input-label for="sumber_dana" value="Sumber Dana" />
                        <select id="sumber_dana" name="sumber_dana" class="input mt-1">
                            @foreach ($sumberDanaList as $key => $label)
                                <option value="{{ $key }}" @selected(old('sumber_dana', $catatanStok->sumber_dana ?? 'saldo_usaha') === $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('sumber_dana')" />
                    </div>
                </div>

                <div>
                    <x-input-label for="catatan" value="Catatan" />
                    <textarea id="catatan" name="catatan" class="input mt-1" rows="4" placeholder="Opsional...">{{ old('catatan', $catatanStok->catatan) }}</textarea>
                    <x-input-error class="mt-2" :messages="$errors->get('catatan')" />
                </div>

                <div>
                    <x-input-label for="bukti" value="Upload Bukti (opsional)" />
                    @if ($catatanStok->bukti_path)
                        <div class="mt-2">
                            <a href="{{ asset('storage/' . $catatanStok->bukti_path) }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 text-sm font-semibold text-brand-blue hover:underline dark:text-brand-gold">
                                Lihat bukti saat ini
                            </a>
                        </div>
                    @endif
                    <input id="bukti" name="bukti" type="file" class="mt-2 block w-full text-sm text-black/60 file:mr-4 file:rounded-xl file:border-0 file:bg-brand-blue file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-brand-navy dark:text-white/70 dark:file:bg-white/10 dark:file:text-white dark:hover:file:bg-white/15" accept="image/*" />
                    <x-input-error class="mt-2" :messages="$errors->get('bukti')" />
                </div>

                <div class="flex items-center gap-2">
                    <button class="btn-primary" type="submit">Simpan</button>
                    <a href="{{ route('catatan-stok.index') }}" class="btn-ghost">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
