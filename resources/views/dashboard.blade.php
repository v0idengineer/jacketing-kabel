@extends('layouts.app')

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jacketing Kabel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

@section('content')
<div x-data="{ show: false }" x-init="setTimeout(() => show = true, 100)">
    <div
        x-show="show"
        x-transition:enter="transition-opacity transition-transform duration-700 delay-100 ease-out"
        x-transition:enter-start="opacity-0 translate-y-3"
        x-transition:enter-end="opacity-100 translate-y-0"
        class="relative z-0 mx-auto w-full max-w-[1800px] px-3 sm:px-4 lg:px-6">

        <!-- TICKER -->
        <div
            x-data="ticker({
              items: [
                'Reminder: update resep sebelum shift malam',
                'Lead time rata-rata: 1.8 hari',
                'Batch selesai QC: 18',
                'Dies baru masuk: 22 pcs',
                'Cutover JC-5 pukul 14:00'
              ],
              speed: 70,
              sep: ' • '
            })"
            x-init="mount()"
            class="mb-4 sm:mb-6">
            <div class="rounded-2xl sm:rounded-3xl ring-1 ring-slate-200/60 bg-gradient-to-r from-indigo-50 via-sky-50 to-emerald-50 shadow-sm sm:shadow-md">
                <div class="relative overflow-hidden py-2.5 sm:py-3 px-3 sm:px-5 select-none">
                    <div class="pointer-events-none absolute inset-y-0 left-0 w-16 sm:w-24 bg-gradient-to-r from-indigo-50 via-indigo-50/70 to-transparent"></div>
                    <div class="pointer-events-none absolute inset-y-0 right-0 w-16 sm:w-24 bg-gradient-to-l from-emerald-50 via-emerald-50/70 to-transparent"></div>

                    <div class="flex whitespace-nowrap will-change-transform" x-ref="track">
                        <span class="bg-clip-text text-transparent bg-gradient-to-r from-slate-700 via-sky-600 to-emerald-600 font-medium text-sm sm:text-base"
                            x-ref="seg1" x-text="text"></span>
                        <span class="bg-clip-text text-transparent bg-gradient-to-r from-slate-700 via-sky-600 to-emerald-600 font-medium text-sm sm:text-base"
                            x-ref="seg2" x-text="text"></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Judul -->
        <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-center text-gray-800 mb-3 sm:mb-4">
            RnD Jacketing Cable
        </h1>
        <h2 class="text-lg sm:text-xl font-semibold text-gray-800 mb-2 sm:mb-3 text-left tracking-tight">
            Menu
        </h2>

        <!-- GRID MENU -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 lg:gap-7 xl:gap-8">

            <!-- JC-2 -->
            <div class="bg-white rounded-xl shadow-sm sm:shadow-md p-4 sm:p-6 hover:bg-gray-50">
                <h2 class="text-lg sm:text-xl font-semibold text-gray-700 mb-2 sm:mb-4">JC-2</h2>
                <p class="text-gray-500 text-sm sm:text-base mb-4">Mesin Jacketing JC-2 untuk produksi kabel.</p>
                <a href="{{ route('form.show', ['machine' => 'jc2']) }}"
                    class="group block rounded-xl border border-slate-200 bg-white p-4 sm:p-5 shadow hover:shadow-md hover:-translate-y-0.5 transition-all">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="grid h-9 w-9 sm:h-10 sm:w-10 place-items-center rounded-lg bg-slate-900 text-white text-sm">JC</span>
                            <div>
                                <h4 class="text-sm sm:text-base font-semibold text-slate-900">Mesin JC-2</h4>
                                <p class="text-xs text-slate-500">Siap digunakan</p>
                            </div>
                        </div>
                        <span class="rounded-full bg-slate-100 px-2.5 py-1 text-[10px] sm:text-[11px] font-medium text-slate-600">Jacketing</span>
                    </div>
                    <div class="mt-3 flex items-center text-slate-900 font-semibold">
                        Mulai
                        <svg class="ml-2 h-4 w-4 transition-transform group-hover:translate-x-0.5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 111.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" />
                        </svg>
                    </div>
                </a>
            </div>

            <!-- JC-3 -->
            <div class="bg-white rounded-xl shadow-sm sm:shadow-md p-4 sm:p-6 hover:bg-gray-50">
                <h2 class="text-lg sm:text-xl font-semibold text-gray-700 mb-2 sm:mb-4">JC-3</h2>
                <p class="text-gray-500 text-sm sm:text-base mb-4">Mesin Jacketing JC-3 untuk produksi kabel.</p>
                <a href="{{ route('form.show', ['machine' => 'jc3']) }}"
                    class="group block rounded-xl border border-slate-200 bg-white p-4 sm:p-5 shadow hover:shadow-md hover:-translate-y-0.5 transition-all">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="grid h-9 w-9 sm:h-10 sm:w-10 place-items-center rounded-lg bg-slate-900 text-white text-sm">JC</span>
                            <div>
                                <h4 class="text-sm sm:text-base font-semibold text-slate-900">Mesin JC-3</h4>
                                <p class="text-xs text-slate-500">Siap digunakan</p>
                            </div>
                        </div>
                        <span class="rounded-full bg-slate-100 px-2.5 py-1 text-[10px] sm:text-[11px] font-medium text-slate-600">Jacketing</span>
                    </div>
                    <div class="mt-3 flex items-center text-slate-900 font-semibold">
                        Mulai
                        <svg class="ml-2 h-4 w-4 transition-transform group-hover:translate-x-0.5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 111.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" />
                        </svg>
                    </div>
                </a>
            </div>

            <!-- JC-5 -->
            <div class="bg-white rounded-xl shadow-sm sm:shadow-md p-4 sm:p-6 hover:bg-gray-50">
                <h2 class="text-lg sm:text-xl font-semibold text-gray-700 mb-2 sm:mb-4">JC-5</h2>
                <p class="text-gray-500 text-sm sm:text-base mb-4">Mesin Jacketing JC-5 untuk produksi kabel.</p>
                <a href="{{ route('form.show', ['machine' => 'jc5']) }}"
                    class="group block rounded-xl border border-slate-200 bg-white p-4 sm:p-5 shadow hover:shadow-md hover:-translate-y-0.5 transition-all">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="grid h-9 w-9 sm:h-10 sm:w-10 place-items-center rounded-lg bg-slate-900 text-white text-sm">JC</span>
                            <div>
                                <h4 class="text-sm sm:text-base font-semibold text-slate-900">Mesin JC-5</h4>
                                <p class="text-xs text-slate-500">Siap digunakan</p>
                            </div>
                        </div>
                        <span class="rounded-full bg-slate-100 px-2.5 py-1 text-[10px] sm:text-[11px] font-medium text-slate-600">Jacketing</span>
                    </div>
                    <div class="mt-3 flex items-center text-slate-900 font-semibold">
                        Mulai
                        <svg class="ml-2 h-4 w-4 transition-transform group-hover:translate-x-0.5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 111.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" />
                        </svg>
                    </div>
                </a>
            </div>

            <!-- JC-6 -->
            <div class="bg-white rounded-xl shadow-sm sm:shadow-md p-4 sm:p-6 hover:bg-gray-50">
                <h2 class="text-lg sm:text-xl font-semibold text-gray-700 mb-2 sm:mb-4">JC-6</h2>
                <p class="text-gray-500 text-sm sm:text-base mb-4">Mesin Jacketing JC-6 untuk produksi kabel.</p>
                <a href="{{ route('form.show', ['machine' => 'jc6']) }}"
                    class="group block rounded-xl border border-slate-200 bg-white p-4 sm:p-5 shadow hover:shadow-md hover:-translate-y-0.5 transition-all">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="grid h-9 w-9 sm:h-10 sm:w-10 place-items-center rounded-lg bg-slate-900 text-white text-sm">JC</span>
                            <div>
                                <h4 class="text-sm sm:text-base font-semibold text-slate-900">Mesin JC-6</h4>
                                <p class="text-xs text-slate-500">Siap digunakan</p>
                            </div>
                        </div>
                        <span class="rounded-full bg-slate-100 px-2.5 py-1 text-[10px] sm:text-[11px] font-medium text-slate-600">Jacketing</span>
                    </div>
                    <div class="mt-3 flex items-center text-slate-900 font-semibold">
                        Mulai
                        <svg class="ml-2 h-4 w-4 transition-transform group-hover:translate-x-0.5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 111.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" />
                        </svg>
                    </div>
                </a>
            </div>

            <!-- Stock Dies -->
            <div class="bg-white rounded-xl shadow-sm sm:shadow-md p-4 sm:p-6 hover:bg-gray-50">
                <h2 class="text-lg sm:text-xl font-semibold text-gray-700 mb-2 sm:mb-4">Stock Dies</h2>
                <p class="text-gray-500 text-sm sm:text-base mb-4">Dies yang tersedia untuk mesin jacketing.</p>
                <a href="{{ route('stock.index') }}"
                    class="group block rounded-xl border border-emerald-200 bg-emerald-50/60 p-4 sm:p-5 shadow hover:shadow-md hover:-translate-y-0.5 transition-all">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="grid h-9 w-9 sm:h-10 sm:w-10 place-items-center rounded-lg bg-emerald-600 text-white font-semibold text-sm">ST</span>
                            <div>
                                <h4 class="text-sm sm:text-base font-semibold text-emerald-900">Stock Dies</h4>
                                <p class="text-xs text-emerald-700/80">Kelola & input stok dies</p>
                            </div>
                        </div>
                        <span class="rounded-full bg-white/70 px-2.5 py-1 text-[10px] sm:text-[11px] font-medium text-emerald-700 border border-emerald-200">Data</span>
                    </div>
                    <div class="mt-3 flex items-center text-emerald-800 font-semibold">
                        Buka Stok
                        <svg class="ml-2 h-4 w-4 transition-transform group-hover:translate-x-0.5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 111.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" />
                        </svg>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('ticker', ({
            items = [],
            speed = 70,
            sep = ' • '
        }) => ({
            text: '',
            offset: 0,
            segW: 0,
            lastTs: null,
            rafId: null,
            _onVisible: null,
            mount() {
                this.text = items.join(sep) + sep;
                this.$nextTick(() => {
                    const measure = () => {
                        this.segW = Math.ceil(this.$refs.seg1?.getBoundingClientRect().width || 0);
                        if (!this.segW) {
                            requestAnimationFrame(measure);
                            return;
                        }
                        this.start();
                    };
                    measure();
                });
                this._onVisible = () => {
                    if (document.visibilityState === 'visible') this.start();
                };
                document.addEventListener('visibilitychange', this._onVisible, {
                    passive: true
                });
            },
            start() {
                this.lastTs = null;
                if (this.rafId) cancelAnimationFrame(this.rafId);
                this.rafId = requestAnimationFrame(this.loop.bind(this));
            },
            loop(ts) {
                if (this.lastTs == null) this.lastTs = ts;
                const dt = (ts - this.lastTs) / 1000;
                this.lastTs = ts;
                this.offset += speed * dt;
                if (this.offset >= this.segW) this.offset -= this.segW;
                this.$refs.track.style.transform = `translate3d(${-this.offset}px,0,0)`;
                this.rafId = requestAnimationFrame(this.loop.bind(this));
            },
            destroy() {
                if (this.rafId) cancelAnimationFrame(this.rafId);
                if (this._onVisible) document.removeEventListener('visibilitychange', this._onVisible);
            }
        }));
    });
</script>
@endpush