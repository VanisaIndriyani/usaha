<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email Address')" class="text-brand-navy font-semibold" />
            <x-text-input id="email" class="block mt-1 w-full bg-gray-50/50 border-gray-200 focus:bg-white transition-colors" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
          

            <x-text-input id="password" class="block mt-1 w-full bg-gray-50/50 border-gray-200 focus:bg-white transition-colors"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="block">
            <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-brand-blue shadow-sm focus:ring-brand-blue group-hover:border-brand-blue transition-colors" name="remember">
                <span class="ms-2 text-sm text-gray-600 group-hover:text-brand-navy transition-colors">{{ __('Ingat saya') }}</span>
            </label>
        </div>

        <div>
            <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-3 bg-gradient-to-r from-brand-navy to-brand-blue border border-transparent rounded-xl font-bold text-sm text-white uppercase tracking-widest hover:from-brand-blue hover:to-brand-navy focus:outline-none focus:ring-2 focus:ring-brand-blue focus:ring-offset-2 transition-all duration-300 shadow-lg shadow-brand-blue/30 transform hover:-translate-y-0.5">
                {{ __('Log in') }}
            </button>
        </div>
    </form>
</x-guest-layout>
