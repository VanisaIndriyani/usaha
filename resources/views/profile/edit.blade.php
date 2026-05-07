<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3">
            <div>
                <div class="text-sm font-semibold text-black/55 dark:text-white/60">Akun</div>
                <div class="text-xl font-bold tracking-tight text-brand-navy dark:text-white">Profile</div>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <div class="glass-card">
            <div class="card-body">
                <div class="max-w-2xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>
        </div>

        <div class="glass-card">
            <div class="card-body">
                <div class="max-w-2xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>
        </div>

        <div class="glass-card">
            <div class="card-body">
                <div class="max-w-2xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
