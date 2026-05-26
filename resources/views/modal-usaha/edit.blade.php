<x-app-layout>
    <x-slot name="header">
        <div>
            <div class="text-sm font-semibold text-black/55 dark:text-white/60">Keuangan</div>
            <div class="text-xl font-bold tracking-tight text-brand-navy dark:text-white">Edit Modal</div>
        </div>
    </x-slot>

    <div class="glass-card">
        <div class="card-body">
            <form method="POST" action="{{ route('modal-usaha.update', $modalUsaha) }}" class="space-y-5">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <x-input-label for="owner_id" value="Nama Owner" />
                        <select id="owner_id" name="owner_id" class="input mt-1">
                            @foreach ($owners as $owner)
                                <option value="{{ $owner->id }}" @selected(old('owner_id', $modalUsaha->owner_id) == $owner->id)>{{ $owner->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('owner_id')" />
                    </div>

                    <div>
                        <x-input-label for="tanggal" value="Tanggal" />
                        <x-text-input id="tanggal" name="tanggal" type="date" class="mt-1 block w-full" :value="old('tanggal', $modalUsaha->tanggal?->toDateString())" required />
                        <x-input-error class="mt-2" :messages="$errors->get('tanggal')" />
                    </div>
                </div>

                <div>
                    <x-input-label for="akun" value="Akun" />
                    <select id="akun" name="akun" class="input mt-1">
                        <option value="BRI" @selected(old('akun', $modalUsaha->akun ?? 'BRI') === 'BRI')>BRI</option>
                        <option value="Cash" @selected(old('akun', $modalUsaha->akun ?? 'BRI') === 'Cash')>Cash</option>
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('akun')" />
                </div>

                <div>
                    <x-input-label for="nominal" value="Nominal Modal" />
                    <x-text-input id="nominal" name="nominal" type="text" inputmode="numeric" data-money class="mt-1 block w-full" :value="old('nominal', $modalUsaha->nominal)" required />
                    <x-input-error class="mt-2" :messages="$errors->get('nominal')" />
                </div>

                <div>
                    <x-input-label for="catatan" value="Catatan" />
                    <textarea id="catatan" name="catatan" class="input mt-1" rows="4" placeholder="Opsional...">{{ old('catatan', $modalUsaha->catatan) }}</textarea>
                    <x-input-error class="mt-2" :messages="$errors->get('catatan')" />
                </div>

                <div class="flex items-center gap-2">
                    <button class="btn-primary" type="submit">Update</button>
                    <a href="{{ route('modal-usaha.index') }}" class="btn-ghost">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
