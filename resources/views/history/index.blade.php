@extends('layouts.app')

@section('content')
<style>
    [x-cloak] {
        display: none !important
    }
</style>
<title>Production History</title>

<div
    class="mx-auto w-full max-w-[1800px] px-3 sm:px-4 lg:px-6 py-4 sm:py-6"
    x-data="{ open:false }"
    @keydown.escape.window="open=false">

    {{-- Flash message --}}
    @if(session('ok'))
    <div class="mb-3 sm:mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-3 sm:px-4 py-2.5 sm:py-3 text-emerald-800 text-sm">
        {{ session('ok') }}
    </div>
    @endif
    @if(session('error'))
    <div class="mb-3 sm:mb-4 rounded-lg border border-rose-200 bg-rose-50 px-3 sm:px-4 py-2.5 sm:py-3 text-rose-800 text-sm">
        {{ session('error') }}
    </div>
    @endif

    {{-- Header: Back + Title + Search --}}
    <div class="mb-4 sm:mb-6 grid gap-3 md:grid-cols-2 md:items-center">
        <div class="flex items-center gap-2.5 sm:gap-3">
            <a href="{{ route('dashboard') }}"
                class="rounded-lg bg-slate-900 px-3 py-2 text-white text-sm font-semibold hover:bg-slate-800 whitespace-nowrap">
                ← Back to Dashboard
            </a>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900">Production History</h1>
        </div>

        {{-- Search --}}
        <form method="GET" action="{{ route('history') }}"
            class="flex w-full flex-col sm:flex-row sm:items-center gap-2 sm:justify-end">
            <input type="hidden" name="sort" value="{{ $sort }}">
            <input type="hidden" name="dir" value="{{ $dir }}">

            <div class="flex-1 sm:flex-initial">
                <input type="text" name="q" value="{{ $q }}"
                    placeholder="Cari Nomor SAP..."
                    class="w-full sm:w-56 md:w-64 rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500" />
            </div>

            <div class="flex gap-2">
                <button type="submit"
                    class="rounded-lg bg-slate-900 text-white px-3 py-2 text-sm font-semibold hover:bg-slate-800">
                    Search
                </button>
                @if(!empty($q) || $sort !== 'id' || $dir !== 'desc')
                <a href="{{ route('history') }}"
                    class="rounded-lg bg-slate-100 text-slate-800 px-3 py-2 text-sm font-semibold hover:bg-slate-200">
                    Reset
                </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto rounded-xl border border-slate-200 bg-white shadow-sm">
        <table class="w-full min-w-[980px] text-xs sm:text-[13px] leading-5">
            <thead class="bg-slate-50 text-slate-600">
                <tr>
                    <th class="px-2.5 sm:px-3 py-2 text-left">Tipe Kabel</th>

                    {{-- Header Nomor SAP sortable via created_at --}}
                    @php
                    $isCreatedAt = ($sort === 'created_at');
                    $nextDir = $isCreatedAt ? ($dir === 'desc' ? 'asc' : 'desc') : 'desc';
                    $qs = array_filter([
                    'q' => $q ?: null,
                    'sort' => 'created_at',
                    'dir' => $nextDir,
                    ], fn($v) => !is_null($v) && $v !== '');
                    $sapSortUrl = route('history', $qs);
                    @endphp
                    <th class="px-2.5 sm:px-3 py-2 text-left">
                        <a href="{{ $sapSortUrl }}" class="inline-flex items-center gap-1 hover:underline">
                            Nomor SAP
                            @if($isCreatedAt)
                            @if($dir === 'desc')
                            <span aria-hidden="true">▾</span>
                            @else
                            <span aria-hidden="true">▴</span>
                            @endif
                            @else
                            <span class="text-slate-400" aria-hidden="true">↕</span>
                            @endif
                        </a>
                        <div class="text-[10px] sm:text-[11px] text-slate-400">Klik untuk urut berdasar waktu dibuat</div>
                    </th>

                    <th class="px-2.5 sm:px-3 py-2 text-left whitespace-nowrap">Output Ø</th>
                    <th class="px-2.5 sm:px-3 py-2 text-left whitespace-nowrap">Core Ø</th>
                    <th class="px-2.5 sm:px-3 py-2 text-left whitespace-nowrap">Ring Ø</th>
                    <th class="px-2.5 sm:px-3 py-2 text-left">DBR</th>
                    <th class="px-2.5 sm:px-3 py-2 text-left">DDR</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-100">
                @forelse($histories as $h)
                @php
                $core = $h->coreDie; $ring = $h->ringDie;
                $coreDia = $core && $core->dies_diameter !== null
                ? number_format((float)$core->dies_diameter, 2)
                : (!is_null($h->core_dia_mm) ? number_format((float)$h->core_dia_mm, 2) : null);
                $ringDia = $ring && $ring->dies_diameter !== null
                ? number_format((float)$ring->dies_diameter, 2)
                : (!is_null($h->ring_dia_mm) ? number_format((float)$h->ring_dia_mm, 2) : null);
                @endphp

                <tr class="hover:bg-slate-50/60 cursor-pointer"
                    @click="
                        open = true;
                        $refs.modalTitle.textContent = 'History #{{ $h->id }} — {{ $h->machine }}';
                        $refs.modalBody.innerHTML = $refs['tpl{{ $h->id }}'].innerHTML;

                        // set data untuk tombol footer modal
                        $refs.btnDownload.dataset.historyId = '{{ $h->id }}';

                        @php
                            $designHref = isset($h->recipe_id) && $h->recipe_id
                                ? route('design.print', ['recipe' => $h->recipe_id])
                                : url('/design/create?history_id='.$h->id);
                        @endphp
                        $refs.btnDesign.href = '{{ $designHref }}';
                    ">
                    <td class="px-2.5 sm:px-3 py-2 text-slate-900 font-medium">{{ $h->cable_type }}</td>
                    <td class="px-2.5 sm:px-3 py-2 text-slate-700">{{ $h->sap_number }}</td>
                    <td class="px-2.5 sm:px-3 py-2 whitespace-nowrap">{{ number_format((float)$h->output_diameter, 2) }} mm</td>
                    <td class="px-2.5 sm:px-3 py-2 whitespace-nowrap">{{ $coreDia ? ($coreDia.' mm') : '-' }}</td>
                    <td class="px-2.5 sm:px-3 py-2 whitespace-nowrap">{{ $ringDia ? ($ringDia.' mm') : '-' }}</td>
                    <td class="px-2.5 sm:px-3 py-2 whitespace-nowrap">{{ $h->dbr !== null ? number_format((float)$h->dbr, 3) : '-' }}</td>
                    <td class="px-2.5 sm:px-3 py-2 whitespace-nowrap">{{ $h->ddr !== null ? number_format((float)$h->ddr, 3) : '-' }}</td>
                </tr>

                {{-- TEMPLATE DETAIL --}}
                <template x-ref="tpl{{ $h->id }}">
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 sm:gap-4 text-[12.5px] sm:text-[13px]">
                            <div class="rounded-lg border border-slate-200 bg-white p-3">
                                <div class="font-semibold text-slate-700 mb-2">Dimensions</div>
                                <dl class="grid grid-cols-2 gap-x-3 gap-y-1.5">
                                    <dt class="text-slate-500">Input Ø</dt>
                                    <dd class="text-slate-900">{{ number_format((float)$h->input_diameter, 2) }} mm</dd>
                                    <dt class="text-slate-500">WT</dt>
                                    <dd class="text-slate-900">{{ number_format((float)$h->working_thickness, 2) }} mm</dd>
                                    <dt class="text-slate-500">Output Ø</dt>
                                    <dd class="text-slate-900">{{ number_format((float)$h->output_diameter, 2) }} mm</dd>
                                    <dt class="text-slate-500">DDR</dt>
                                    <dd class="text-slate-900">{{ $h->ddr !== null ? number_format((float)$h->ddr, 3) : '-' }}</dd>
                                    <dt class="text-slate-500">DBR</dt>
                                    <dd class="text-slate-900">{{ $h->dbr !== null ? number_format((float)$h->dbr, 3) : '-' }}</dd>
                                </dl>
                            </div>

                            <div class="rounded-lg border border-slate-200 bg-white p-3">
                                <div class="font-semibold text-slate-700 mb-2">Order & Material</div>
                                <dl class="grid grid-cols-2 gap-x-3 gap-y-1.5">
                                    <dt class="text-slate-500">Cable</dt>
                                    <dd class="text-slate-900">{{ $h->cable_type }}</dd>
                                    <dt class="text-slate-500">SAP</dt>
                                    <dd class="text-slate-900">{{ $h->sap_number }}</dd>
                                    <dt class="text-slate-500">Order By</dt>
                                    <dd class="text-slate-900">{{ $h->order_by }}</dd>
                                    <dt class="text-slate-500">Prod. Date</dt>
                                    <dd class="text-slate-900">{{ optional($h->production_date)->format('Y-m-d') }}</dd>
                                    <dt class="text-slate-500">Material</dt>
                                    <dd class="text-slate-900">{{ optional($h->material)->name ?? '-' }}</dd>
                                </dl>
                            </div>

                            <div class="rounded-lg border border-slate-200 bg-white p-3">
                                <div class="font-semibold text-slate-700 mb-2">Meta</div>
                                <dl class="grid grid-cols-2 gap-x-3 gap-y-1.5">
                                    <dt class="text-slate-500">Machine</dt>
                                    <dd class="text-slate-900">{{ $h->machine }}</dd>
                                    <dt class="text-slate-500">Crosshead</dt>
                                    <dd class="text-slate-900">{{ $h->crosshead_type }}</dd>
                                    <dt class="text-slate-500">Dies Type</dt>
                                    <dd class="text-slate-900">{{ $h->dies_type }}</dd>
                                    <dt class="text-slate-500">Process</dt>
                                    <dd class="text-slate-900">{{ $h->process }}</dd>
                                    <dt class="text-slate-500">Created</dt>
                                    <dd class="text-slate-900">{{ optional($h->created_at)->format('Y-m-d H:i') }}</dd>
                                </dl>
                            </div>
                        </div>

                        @php $core = $h->coreDie; $ring = $h->ringDie; @endphp
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4">
                            <div class="rounded-lg border border-slate-200 bg-white p-3">
                                <div class="font-semibold text-slate-700 mb-2">Core Die Detail</div>
                                @if($core)
                                <div class="text-slate-900">{{ $core->dies_code }} — {{ number_format((float)$core->dies_diameter,2) }} mm</div>
                                @elseif(!is_null($h->core_dia_mm))
                                <div class="text-slate-900">{{ number_format((float)$h->core_dia_mm,2) }} mm <span class="text-slate-500">({{ $h->core_dia_src ?: 'calc' }})</span></div>
                                @else
                                <div class="text-slate-500">-</div>
                                @endif
                            </div>
                            <div class="rounded-lg border border-slate-200 bg-white p-3">
                                <div class="font-semibold text-slate-700 mb-2">Ring Die Detail</div>
                                @if($ring)
                                <div class="text-slate-900">{{ $ring->dies_code }} — {{ number_format((float)$ring->dies_diameter,2) }} mm</div>
                                @elseif(!is_null($h->ring_dia_mm))
                                <div class="text-slate-900">{{ number_format((float)$h->ring_dia_mm,2) }} mm <span class="text-slate-500">({{ $h->ring_dia_src ?: 'calc' }})</span></div>
                                @else
                                <div class="text-slate-500">-</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </template>
                @empty
                <tr>
                    <td colspan="7" class="p-6 text-center text-slate-500">
                        Belum ada data riwayat produksi.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if(method_exists($histories, 'links'))
    <div class="mt-3 sm:mt-4">
        {{ $histories->appends(request()->query())->links() }}
    </div>
    @endif

    {{-- MODAL --}}
    <div x-show="open" x-cloak class="fixed inset-0 z-50 flex items-start sm:items-center justify-center p-3 sm:p-4">
        <div class="absolute inset-0 bg-black/50" @click="open=false"></div>

        <div class="relative w-full max-w-5xl bg-white rounded-2xl shadow-xl overflow-hidden
                mt-16 mb-6 sm:mt-0 sm:mb-0">
            <div class="flex items-center justify-between px-4 sm:px-5 py-3 border-b border-slate-200">
                <h3 class="text-base sm:text-lg font-semibold text-slate-900" x-ref="modalTitle">Detail</h3>
                <button class="rounded-md px-2 py-1 text-slate-600 hover:bg-slate-100" @click="open=false">✕</button>
            </div>

            <div class="p-4 sm:p-5 max-h-[72vh] overflow-auto" x-ref="modalBody">
                {{-- diisi dinamis dari template tiap baris --}}
            </div>

            {{-- FOOTER --}}
            <div class="px-4 sm:px-5 py-3 border-t border-slate-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 sm:gap-3">
                <div class="text-[11.5px] sm:text-[12px] text-slate-500">
                    Ekspor laporan atau buat desain dari data history ini.
                </div>
                <div class="flex items-center gap-2">
                    <a x-ref="btnDesign"
                        href="#"
                        class="px-3 sm:px-4 py-2 rounded-lg border border-slate-300 text-slate-700 font-semibold hover:bg-slate-50 text-sm">
                        Create Design
                    </a>
                    <button type="button"
                        x-ref="btnDownload"
                        class="px-3 sm:px-4 py-2 rounded-lg bg-black text-white font-semibold hover:bg-neutral-800 text-sm">
                        Download Report
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Alpine (kalau layout belum include) --}}
<script src="//unpkg.com/alpinejs" defer></script>

{{-- Handler tombol Download Report di popup --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const btn = document.querySelector('[x-ref="btnDownload"]');

        async function triggerFromHistory(historyId) {
            if (!historyId) return;

            const url = @json(route('jacketing.store'));
            const fd = new FormData();
            fd.set('_action', 'report');
            fd.set('history_id', historyId);

            const res = await fetch(url, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: fd,
                credentials: 'same-origin'
            });

            if (!res.ok) throw new Error('HTTP ' + res.status);

            const ct = (res.headers.get('content-type') || '').toLowerCase();
            if (ct.includes('application/json')) {
                const data = await res.json();
                const fileRes = await fetch(data.download_url, {
                    credentials: 'same-origin',
                    cache: 'no-store'
                });
                if (!fileRes.ok) throw new Error('HTTP file ' + fileRes.status);

                const blob = await fileRes.blob();
                const urlBlob = URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = urlBlob;
                a.download = data.filename || ('production-report-' + historyId + '.pdf');
                document.body.appendChild(a);
                a.click();
                a.remove();
                URL.revokeObjectURL(urlBlob);

                if (data.history_url) setTimeout(() => window.location.assign(data.history_url), 1200);
                return;
            }

            // fallback: server balas PDF langsung
            const blob = await res.blob();
            const urlBlob = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = urlBlob;
            a.download = 'production-report-' + historyId + '.pdf';
            document.body.appendChild(a);
            a.click();
            a.remove();
            URL.revokeObjectURL(urlBlob);
        }

        btn?.addEventListener('click', async (e) => {
            e.preventDefault();
            const id = e.currentTarget.dataset.historyId;
            try {
                await triggerFromHistory(id);
            } catch (err) {
                console.error(err);
                alert('Gagal membuat report: ' + (err?.message || ''));
            }
        });
    });
</script>
@endsection