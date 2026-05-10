<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-900 antialiased">
        <div class="relative min-h-screen overflow-hidden bg-gradient-to-br from-[#2563eb] via-[#1e40af] to-[#2563eb]">
            <div class="pointer-events-none absolute inset-0 opacity-[0.65]" style="background-image: radial-gradient(circle at 1px 1px, rgba(239,246,255,0.55) 1px, transparent 0); background-size: 18px 18px;"></div>
            <div class="pointer-events-none absolute -left-24 -top-24 h-72 w-72 rounded-full bg-white/15 blur-3xl"></div>
            <div class="pointer-events-none absolute -bottom-32 -right-32 h-80 w-80 rounded-full bg-sky-300/20 blur-3xl"></div>

            <div class="relative mx-auto flex min-h-screen max-w-6xl items-center justify-center px-4 py-10">
                <div class="grid w-full grid-cols-1 items-stretch gap-6 lg:grid-cols-2 lg:gap-10">
                    <div class="hidden rounded-[20px] border border-white/15 bg-white/10 p-8 shadow-2xl shadow-blue-900/25 backdrop-blur-xl lg:flex lg:flex-col lg:justify-between">
                        <div>
                            <div class="flex items-center gap-3">
                                <div class="grid h-12 w-12 place-items-center rounded-full bg-gradient-to-br from-[#3b82f6] to-[#1e40af] shadow-sm">
                                    <span class="text-sm font-extrabold tracking-wide text-white">BC</span>
                                </div>
                                <div class="text-xl font-semibold tracking-wide text-white">BlueChat</div>
                            </div>

                            <div class="mt-6 text-3xl font-extrabold leading-tight text-white">
                                Mini chat modern
                                <span class="block text-white/80">Real-time seperti WhatsApp</span>
                            </div>

                            <div class="mt-4 text-sm leading-relaxed text-white/75">
                                Login untuk mulai chat real-time dengan Laravel 11 + Reverb. UI dibuat responsive untuk laptop dan HP.
                            </div>

                            <div class="mt-7 space-y-3">
                                <div class="flex items-center gap-3 rounded-[20px] bg-white/10 px-4 py-3 text-white/85 ring-1 ring-white/10">
                                    <span class="grid h-9 w-9 place-items-center rounded-full bg-white/10">
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                            <path d="M21 15a4 4 0 01-4 4H8l-5 3V7a4 4 0 014-4h10a4 4 0 014 4v8z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                                        </svg>
                                    </span>
                                    <div class="text-sm font-semibold">Pesan muncul tanpa refresh</div>
                                </div>
                                <div class="flex items-center gap-3 rounded-[20px] bg-white/10 px-4 py-3 text-white/85 ring-1 ring-white/10">
                                    <span class="grid h-9 w-9 place-items-center rounded-full bg-white/10">
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                            <path d="M12 22a8 8 0 100-16 8 8 0 000 16z" stroke="currentColor" stroke-width="2"/>
                                            <path d="M6 14.5l2.5 2.5L18 7.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </span>
                                    <div class="text-sm font-semibold">Online/offline realtime</div>
                                </div>
                                <div class="flex items-center gap-3 rounded-[20px] bg-white/10 px-4 py-3 text-white/85 ring-1 ring-white/10">
                                    <span class="grid h-9 w-9 place-items-center rounded-full bg-white/10">
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                            <path d="M12 17.5a4.5 4.5 0 10-4.5-4.5 4.5 4.5 0 004.5 4.5z" stroke="currentColor" stroke-width="2"/>
                                            <path d="M18 20.5a6.5 6.5 0 00-12 0" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                            <path d="M15 6.5h5M17.5 4v5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                        </svg>
                                    </span>
                                    <div class="text-sm font-semibold">Private chat aman</div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 text-xs text-white/70">
                            Laravel 11 • Reverb • Echo
                        </div>
                    </div>

                    <div class="rounded-[20px] border border-white/15 bg-white/85 p-6 shadow-2xl shadow-blue-900/25 backdrop-blur-xl sm:p-8">
                        <div class="mb-6 flex items-center gap-3 lg:hidden">
                            <div class="grid h-11 w-11 place-items-center rounded-full bg-gradient-to-br from-[#3b82f6] to-[#1e40af] shadow-sm">
                                <span class="text-sm font-extrabold tracking-wide text-white">BC</span>
                            </div>
                            <div class="text-lg font-semibold tracking-wide text-slate-900">BlueChat</div>
                        </div>

                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>

        <script>
            (() => {
                const toggles = document.querySelectorAll('[data-password-toggle]');
                toggles.forEach((btn) => {
                    btn.addEventListener('click', () => {
                        const targetId = btn.getAttribute('data-target');
                        if (!targetId) return;
                        const input = document.getElementById(targetId);
                        if (!input) return;

                        const isPassword = input.getAttribute('type') === 'password';
                        input.setAttribute('type', isPassword ? 'text' : 'password');
                        btn.setAttribute('aria-label', isPassword ? 'Sembunyikan password' : 'Tampilkan password');

                        const eye = btn.querySelector('[data-eye]');
                        const eyeOff = btn.querySelector('[data-eye-off]');
                        if (eye && eyeOff) {
                            eye.classList.toggle('hidden', isPassword);
                            eyeOff.classList.toggle('hidden', !isPassword);
                        }
                    });
                });
            })();
        </script>
    </body>
</html>
