<x-app-layout>
    <x-slot name="header">
        <div>
            <div class="text-sm font-semibold text-black/55 dark:text-white/60">Keuangan</div>
            <div class="text-xl font-bold tracking-tight text-brand-navy dark:text-white">Edit Gaji</div>
        </div>
    </x-slot>

    <div class="glass-card" x-data="gajiForm()">
        <div class="card-body">
            <form method="POST" action="{{ route('gaji.update', $gaji) }}" class="space-y-5">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <x-input-label for="karyawan_id" value="Karyawan" />
                        <select id="karyawan_id" name="karyawan_id" class="input mt-1" x-model="karyawanId" @change="updateGajiHarian">
                            <option value="">-- Pilih Karyawan --</option>
                            @foreach ($karyawanList as $k)
                                <option value="{{ $k->id }}" data-gaji="{{ (int) $k->gaji_harian }}">{{ $k->nama }} ({{ $k->jabatan }})</option>
                            @endforeach
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('karyawan_id')" />
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="bulan" value="Bulan" />
                            <x-text-input id="bulan" name="bulan" type="number" class="mt-1 block w-full" :value="old('bulan', $gaji->bulan)" min="1" max="12" required />
                            <x-input-error class="mt-2" :messages="$errors->get('bulan')" />
                        </div>
                        <div>
                            <x-input-label for="tahun" value="Tahun" />
                            <x-text-input id="tahun" name="tahun" type="number" class="mt-1 block w-full" :value="old('tahun', $gaji->tahun)" min="2000" max="2100" required />
                            <x-input-error class="mt-2" :messages="$errors->get('tahun')" />
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <div>
                        <x-input-label for="gaji_harian" value="Gaji Harian" />
                        <x-text-input id="gaji_harian" name="gaji_harian" type="number" class="mt-1 block w-full bg-gray-50" x-model="gajiHarian" min="0" required readonly />
                        <x-input-error class="mt-2" :messages="$errors->get('gaji_harian')" />
                    </div>
                    <div>
                        <x-input-label for="hari_kerja" value="Jumlah Hari Kerja" />
                        <x-text-input id="hari_kerja" name="hari_kerja" type="number" class="mt-1 block w-full" x-model="hariKerja" min="0" max="31" required />
                        <x-input-error class="mt-2" :messages="$errors->get('hari_kerja')" />
                    </div>
                    <div>
                        <x-input-label for="bonus" value="Bonus" />
                        <x-text-input id="bonus" name="bonus" type="number" class="mt-1 block w-full" x-model="bonus" min="0" required />
                        <x-input-error class="mt-2" :messages="$errors->get('bonus')" />
                    </div>
                </div>

                <div class="p-4 bg-brand-blue/5 rounded-xl border border-brand-blue/20">
                    <div class="text-sm font-semibold text-brand-navy">Total Gaji:</div>
                    <div class="text-2xl font-bold text-brand-blue" x-text="'Rp ' + calculateTotal()"></div>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <x-input-label for="status" value="Status" />
                        <select id="status" name="status" class="input mt-1">
                            <option value="belum_dibayar" @selected(old('status', $gaji->status) === 'belum_dibayar')>belum_dibayar</option>
                            <option value="dibayar" @selected(old('status', $gaji->status) === 'dibayar')>dibayar</option>
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('status')" />
                    </div>
                    <div>
                        <x-input-label for="tanggal_bayar" value="Tanggal Bayar (jika dibayar)" />
                        <x-text-input id="tanggal_bayar" name="tanggal_bayar" type="date" class="mt-1 block w-full" :value="old('tanggal_bayar', $gaji->tanggal_bayar?->toDateString())" />
                        <x-input-error class="mt-2" :messages="$errors->get('tanggal_bayar')" />
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <button class="btn-primary" type="submit">Update</button>
                    <a href="{{ route('gaji.index', ['bulan' => $gaji->bulan, 'tahun' => $gaji->tahun]) }}" class="btn-ghost">Batal</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function gajiForm() {
            return {
                karyawanId: '{{ old('karyawan_id', $gaji->karyawan_id) }}',
                gajiHarian: {{ old('gaji_harian', $gaji->gaji_harian) }},
                hariKerja: {{ old('hari_kerja', $gaji->hari_kerja) }},
                bonus: {{ old('bonus', $gaji->bonus) }},
                updateGajiHarian() {
                    const select = document.getElementById('karyawan_id');
                    const option = select.options[select.selectedIndex];
                    if (option && option.dataset.gaji) {
                        this.gajiHarian = parseInt(option.dataset.gaji);
                    } else {
                        this.gajiHarian = 0;
                    }
                },
                calculateTotal() {
                    const total = (parseInt(this.gajiHarian) || 0) * (parseInt(this.hariKerja) || 0) + (parseInt(this.bonus) || 0);
                    return new Intl.NumberFormat('id-ID').format(total);
                }
            }
        }
    </script>
</x-app-layout>

