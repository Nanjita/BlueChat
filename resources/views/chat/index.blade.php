<x-app-layout>
    <div id="chat-app" class="h-[100dvh] w-full bg-gradient-to-br from-[#2563eb] via-[#1e40af] to-[#2563eb] p-3 sm:p-5" data-auth-id="{{ auth()->id() }}" data-auth-name="{{ auth()->user()->name }}" data-users='@json($users)'>
        <div class="mx-auto h-full w-full max-w-7xl">
            <div class="relative h-full overflow-hidden rounded-[20px] border border-white/15 bg-white/10 shadow-2xl shadow-blue-900/30 backdrop-blur-xl">
                <div class="flex h-full">
                    <div id="mobile-overlay" class="fixed inset-0 z-40 hidden bg-slate-950/60 lg:hidden"></div>

                    <aside id="chat-sidebar" class="fixed inset-y-0 left-0 z-50 hidden w-[320px] -translate-x-full border-r border-white/10 bg-white/85 backdrop-blur-xl transition-transform duration-300 ease-out lg:static lg:z-auto lg:flex lg:h-full lg:translate-x-0 lg:bg-white/90">
                        <div class="flex h-full w-full flex-col">
                            <div class="flex items-center justify-between gap-3 border-b border-white/10 px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="grid h-11 w-11 place-items-center rounded-full bg-gradient-to-br from-[#3b82f6] to-[#1e40af] shadow-sm">
                                        <span class="text-sm font-extrabold tracking-wide text-white">BC</span>
                                    </div>
                                    <div class="leading-tight">
                                        <div class="text-sm font-semibold text-slate-900">BlueChat</div>
                                        <div class="text-xs text-slate-600">{{ auth()->user()->name }}</div>
                                    </div>
                                </div>
                                <button id="sidebar-close" type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-white/70 text-slate-800 shadow-sm hover:bg-white lg:hidden">
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                        <path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </button>
                            </div>

                            <div class="px-5 py-4">
                                <div class="relative">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                            <path d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                        </svg>
                                    </div>
                                    <input id="user-search" type="text" class="w-full rounded-full border-white/10 bg-white/70 py-3 pl-12 pr-4 text-sm text-slate-900 placeholder:text-slate-400 shadow-sm focus:border-[#3b82f6] focus:ring-[#3b82f6]" placeholder="Cari user..." />
                                </div>
                            </div>

                            <div class="scrollbar-slim min-h-0 flex-1 overflow-y-auto px-3 pb-4">
                                <div id="user-list" class="space-y-1"></div>
                            </div>
                        </div>
                    </aside>

                    <section class="flex min-w-0 flex-1 flex-col">
                        <header class="sticky top-0 z-30 border-b border-white/10 bg-white/10 px-4 py-3 backdrop-blur-xl sm:px-6">
                            <div class="flex items-center justify-between gap-3">
                                <div class="flex min-w-0 items-center gap-3">
                                    <button id="sidebar-open" type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-white/15 text-white hover:bg-white/20 lg:hidden">
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                            <path d="M4 6h16M4 12h16M4 18h16" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
                                        </svg>
                                    </button>

                                    <div class="relative grid h-11 w-11 shrink-0 place-items-center rounded-full bg-gradient-to-br from-[#3b82f6] to-[#1e40af] shadow-sm">
                                        <span id="chat-avatar-letter" class="text-sm font-extrabold tracking-wide text-white">?</span>
                                    </div>

                                    <div class="min-w-0 leading-tight">
                                        <div id="chat-title" class="truncate text-base font-semibold text-white sm:text-lg">Pilih user</div>
                                        <div class="mt-0.5 flex items-center gap-2 text-xs text-white/70">
                                            <span id="chat-status-dot" class="h-2 w-2 rounded-full bg-slate-400/70"></span>
                                            <span id="chat-status-text">Offline</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2">
                                    <span class="hidden items-center gap-2 rounded-full bg-white/10 px-3 py-1 text-xs font-semibold text-white/80 sm:inline-flex">
                                        <span id="reverb-conn-dot" class="h-2 w-2 rounded-full bg-slate-400/70"></span>
                                        <span id="reverb-conn-text">Disconnected</span>
                                    </span>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-white/15 px-4 py-2 text-sm font-semibold text-white hover:bg-white/20">
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                                <path d="M10 17l1 0m8-5a8 8 0 11-16 0 8 8 0 0116 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                <path d="M14 12H7m0 0l2.5-2.5M7 12l2.5 2.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </header>

                        <div id="message-scroll" class="scrollbar-slim relative min-h-0 flex-1 overflow-y-auto px-4 py-4 sm:px-6">
                            <div class="pointer-events-none absolute inset-0 opacity-[0.55]" style="background-image: radial-gradient(circle at 1px 1px, rgba(239,246,255,0.55) 1px, transparent 0); background-size: 18px 18px;"></div>
                            <div class="relative">
                                <div id="chat-empty-state" class="grid min-h-[55vh] place-items-center py-10">
                                    <div class="mx-auto max-w-md text-center">
                                        <div class="mx-auto grid h-14 w-14 place-items-center rounded-[20px] bg-white/15 text-white shadow-sm backdrop-blur">
                                            <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                                <path d="M21 15a4 4 0 01-4 4H8l-5 3V7a4 4 0 014-4h10a4 4 0 014 4v8z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                                            </svg>
                                        </div>
                                        <div class="mt-4 text-lg font-semibold text-white">Mulai chat</div>
                                        <div class="mt-1 text-sm text-white/70">Pilih user di sidebar, lalu kirim pesan. Status online/offline akan update real-time.</div>
                                    </div>
                                </div>
                                <div id="message-list" class="space-y-3 pb-6"></div>
                            </div>
                        </div>

                        <div class="border-t border-white/10 bg-white/10 px-4 py-3 backdrop-blur-xl sm:px-6">
                            <form id="message-form" class="flex items-end gap-3">
                                <div class="flex-1">
                                    <div class="rounded-full bg-white/80 shadow-sm ring-1 ring-white/20 transition focus-within:ring-2 focus-within:ring-[#3b82f6]">
                                        <textarea id="message-input" rows="1" class="scrollbar-slim max-h-36 w-full resize-none rounded-full border-0 bg-transparent px-5 py-3 text-sm text-slate-900 placeholder:text-slate-400 focus:ring-0 sm:text-base" placeholder="Ketik pesan..." disabled></textarea>
                                    </div>
                                </div>
                                <button id="send-button" type="submit" class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-gradient-to-br from-[#3b82f6] to-[#1e40af] text-white shadow-lg shadow-blue-900/25 transition hover:brightness-110 disabled:cursor-not-allowed disabled:opacity-60" disabled aria-label="Kirim">
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                        <path d="M22 2L11 13" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                        <path d="M22 2L15 22l-4-9-9-4 20-7z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                                    </svg>
                                </button>
                            </form>
                            <div id="chat-hint" class="mt-2 text-xs text-white/70">Pilih user untuk mulai chat.</div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>

    @vite('resources/js/chat.js')
</x-app-layout>
