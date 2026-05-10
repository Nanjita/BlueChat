<x-guest-layout>
    <x-auth-session-status :status="session('status')" />

    <div class="mb-8">
        <div class="text-2xl font-extrabold tracking-tight text-slate-900">Login</div>
        <div class="mt-1 text-sm text-slate-600">Masuk untuk mulai chat real-time.</div>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <div class="relative mt-2">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M4 6h16v12H4V6z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                        <path d="M4 7l8 6 8-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <x-text-input id="email" class="block w-full rounded-full bg-white/70 pl-12 pr-4 py-3 focus:ring-[#3b82f6]" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-5">
            <x-input-label for="password" :value="__('Password')" />

            <div class="relative mt-2">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M17 11V8a5 5 0 10-10 0v3" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        <path d="M7 11h10v10H7V11z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                    </svg>
                </div>
                <x-text-input id="password" class="block w-full rounded-full bg-white/70 pl-12 pr-12 py-3 focus:ring-[#3b82f6]"
                                type="password"
                                name="password"
                                required autocomplete="current-password" />
                <button type="button" class="absolute inset-y-0 right-0 flex items-center pr-4 text-slate-500 hover:text-slate-700 focus:outline-none" data-password-toggle data-target="password" aria-label="Tampilkan password">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true" data-eye>
                        <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                        <path d="M12 15a3 3 0 100-6 3 3 0 000 6z" stroke="currentColor" stroke-width="2"/>
                    </svg>
                    <svg class="hidden h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true" data-eye-off>
                        <path d="M3 3l18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        <path d="M10.6 10.6a2 2 0 002.8 2.8" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        <path d="M9.9 5.1A10.8 10.8 0 0112 5c6.5 0 10 7 10 7a16.9 16.9 0 01-3.2 4.4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M6.1 6.1A16.7 16.7 0 002 12s3.5 7 10 7c1 0 2-.2 2.9-.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            </div>

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="mt-5 flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center gap-2">
                <input id="remember_me" type="checkbox" class="rounded-lg border-slate-300 text-[#2563eb] shadow-sm focus:ring-[#3b82f6]" name="remember">
                <span class="text-sm font-medium text-slate-600">{{ __('Remember me') }}</span>
            </label>
            @if (Route::has('password.request'))
               
            @endif
        </div>

        <div class="mt-6">
            <x-primary-button class="w-full justify-center rounded-full py-3 text-sm">
                {{ __('Log in') }}
            </x-primary-button>
        </div>

        <div class="mt-6 text-center text-sm text-slate-600">
            Belum punya akun?
            <a href="{{ route('register') }}" class="font-semibold text-[#2563eb] hover:text-[#1e40af]">Register</a>
        </div>
    </form>
</x-guest-layout>
