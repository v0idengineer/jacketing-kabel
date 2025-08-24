@extends('layouts.app')

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Dies</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <style>
        [x-cloak] {
            display: none !important
        }
    </style>
</head>

@section('content')
<div
    x-data="{ show:false, openCreate:false, openEdit:false }"
    x-init="setTimeout(()=>show=true,100)"
    x-on:close-edit.window="openEdit = false">

    {{-- BAR ATAS --}}
    <div class="w-full px-3 sm:px-4 lg:px-6">
        <div x-show="show"
            x-transition:enter="transition-opacity transition-transform duration-700 delay-100 ease-out"
            x-transition:enter-start="opacity-0 translate-y-4"
            x-transition:enter-end="opacity-100 translate-y-0"
            class="py-3 space-y-3 sm:space-y-0 sm:flex sm:items-center sm:gap-3">

            <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Stock Dies</h1>

            <form method="GET" action="{{ route('stock.index') }}"
                class="sm:ml-auto grid grid-cols-2 sm:flex gap-2 w-full sm:w-auto">
                <div class="relative col-span-2 sm:col-span-1 sm:w-52">
                    <select name="crosshead_type"
                        class="h-10 w-full appearance-none rounded-lg border border-slate-300 bg-white px-3 pr-8 text-sm text-slate-700 shadow-sm focus:border-slate-400 focus:ring-2 focus:ring-slate-800/10">
                        <option value="">-- Semua Crosshead Type --</option>
                        @foreach($crossheadTypes as $type)
                        <option value="{{ $type }}" {{ request('crosshead_type')==$type ? 'selected':'' }}>{{ $type }}</option>
                        @endforeach
                    </select>
                    <svg class="pointer-events-none absolute right-2 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-500" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.18l3.71-3.95a.75.75 0 111.08 1.04l-4.25 4.52a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                    </svg>
                </div>

                <div class="relative col-span-2 sm:col-span-1 sm:w-52">
                    <select name="dies_type"
                        class="h-10 w-full appearance-none rounded-lg border border-slate-300 bg-white px-3 pr-8 text-sm text-slate-700 shadow-sm focus:border-slate-400 focus:ring-2 focus:ring-slate-800/10">
                        <option value="">-- Semua Dies Type --</option>
                        @foreach($diesTypes as $type)
                        <option value="{{ $type }}" {{ request('dies_type')==$type ? 'selected':'' }}>{{ $type }}</option>
                        @endforeach
                    </select>
                    <svg class="pointer-events-none absolute right-2 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-500" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.18l3.71-3.95a.75.75 0 111.08 1.04l-4.25 4.52a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                    </svg>
                </div>

                <button type="submit" class="h-10 inline-flex items-center justify-center rounded-lg bg-slate-900 px-3 text-sm text-white font-semibold hover:bg-slate-800 col-span-1">
                    Filter
                </button>

                @if(request()->has('crosshead_type') || request()->has('dies_type'))
                <a href="{{ route('stock.index') }}"
                    class="h-10 inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-3 text-sm text-slate-700 hover:bg-slate-50 col-span-1">
                    Reset
                </a>
                @endif

                <button type="button" @click="openCreate=true"
                    class="h-10 col-span-2 sm:col-span-1 inline-flex items-center justify-center rounded-lg bg-emerald-600 px-3 text-sm text-white font-semibold hover:bg-emerald-700 whitespace-nowrap">
                    Tambah Stock
                </button>
            </form>
        </div>
    </div>

    {{-- TABEL --}}
    <div class="w-full px-2 sm:px-3 lg:px-4">
        <div class="overflow-x-auto rounded-xl border border-slate-200 bg-white shadow-sm">
            <table class="w-full min-w-[1100px] text-[13px] sm:text-sm">
                <thead class="bg-slate-50 text-slate-600">
                    <tr>
                        <th class="px-3 sm:px-4 py-3 text-left">ID</th>
                        <th class="px-3 sm:px-4 py-3 text-left">Code</th>
                        <th class="px-3 sm:px-4 py-3 text-left">Diameter</th>
                        <th class="px-3 sm:px-4 py-3 text-left">Tipe</th>
                        <th class="px-3 sm:px-4 py-3 text-left">Posisi</th>
                        <th class="px-3 sm:px-4 py-3 text-left">Crosshead</th>
                        <th class="px-3 sm:px-4 py-3 text-left">Material Kabel</th>
                        <th class="px-3 sm:px-4 py-3 text-left">Material Dies</th>
                        <th class="px-3 sm:px-4 py-3 text-left">Ordered by</th>
                        <th class="px-3 sm:px-4 py-3 text-left">Supplier</th>
                        <th class="px-3 sm:px-4 py-3 text-left">Arrival D</th>
                        <th class="px-3 sm:px-4 py-3 text-left">Condition</th>
                        <th class="px-3 sm:px-4 py-3 text-left">Checked by</th>
                        <th class="px-3 sm:px-4 py-3 text-left">Checked D</th>
                        <th class="px-3 sm:px-4 py-3 text-left">Created at</th>
                        <th class="px-3 sm:px-4 py-3 text-left">Updated at</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($stockDies as $dies)
                    @php
                    $pos = strtolower($dies->position ?? '');
                    $posClass = $pos === 'core' ? 'bg-indigo-50 text-indigo-700'
                    : ($pos === 'ring' ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-700');
                    $matName = $dies->relationLoaded('material') && $dies->material ? $dies->material->name : null;
                    @endphp
                    <tr class="hover:bg-slate-50/60 cursor-pointer"
                        data-row="{{ $dies->id }}"
                        @click="
                          openEdit = true;
                          $refs.editTitle.textContent = 'Edit Stock #{{ $dies->id }} — {{ $dies->dies_code }}';
                          $refs.editBody.innerHTML   = $refs['tpl{{ $dies->id }}'].innerHTML;
                        ">
                        <td class="px-3 sm:px-4 py-3 text-slate-700" data-field="id">{{ $dies->id }}</td>
                        <td class="px-3 sm:px-4 py-3 font-semibold text-slate-900" data-field="dies_code">{{ $dies->dies_code }}</td>
                        <td class="px-3 sm:px-4 py-3" data-field="dies_diameter">{{ number_format((float)$dies->dies_diameter,2) }}</td>
                        <td class="px-3 sm:px-4 py-3" data-field="dies_type">
                            <span class="inline-flex rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-700">{{ $dies->dies_type }}</span>
                        </td>
                        <td class="px-3 sm:px-4 py-3" data-field="position">
                            <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $posClass }}">{{ $dies->position ?? '-' }}</span>
                        </td>
                        <td class="px-3 sm:px-4 py-3" data-field="crosshead_type">
                            <span class="inline-flex rounded-full bg-sky-50 px-2.5 py-0.5 text-xs font-medium text-sky-700">{{ $dies->crosshead_type }}</span>
                        </td>
                        <td class="px-3 sm:px-4 py-3" data-field="material">
                            @if($matName)
                            <span class="inline-flex rounded-full bg-violet-50 px-2.5 py-0.5 text-xs font-medium text-violet-700">{{ $matName }}</span>
                            <span class="ml-1 text-xs text-slate-400">#{{ $dies->material_id }}</span>
                            @else
                            <span class="text-slate-700">#{{ $dies->material_id ?? '-' }}</span>
                            @endif
                        </td>
                        <td class="px-3 sm:px-4 py-3" data-field="material_dies">
                            <span class="inline-flex rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-700">{{ $dies->material_dies ?? 'stavac' }}</span>
                        </td>
                        <td class="px-3 sm:px-4 py-3" data-field="ordered_by">{{ $dies->ordered_by ?? '-' }}</td>
                        <td class="px-3 sm:px-4 py-3" data-field="supplier">{{ $dies->supplier ?? '-' }}</td>
                        <td class="px-3 sm:px-4 py-3" data-field="arrival_date">{{ optional($dies->arrival_date)->format('Y-m-d') ?? '-' }}</td>
                        <td class="px-3 sm:px-4 py-3" data-field="condition">{{ $dies->condition ?? '-' }}</td>
                        <td class="px-3 sm:px-4 py-3" data-field="checked_by">{{ $dies->checked_by ?? '-' }}</td>
                        <td class="px-3 sm:px-4 py-3" data-field="checked_date">{{ optional($dies->checked_date)->format('Y-m-d') ?? '-' }}</td>
                        <td class="px-3 sm:px-4 py-3 text-slate-500" data-field="created_at">{{ optional($dies->created_at)->format('Y-m-d H:i:s') ?? '-' }}</td>
                        <td class="px-3 sm:px-4 py-3 text-slate-500" data-field="updated_at">{{ optional($dies->updated_at)->format('Y-m-d H:i:s') ?? '-' }}</td>
                    </tr>

                    {{-- TEMPLATE FORM EDIT (per-row) --}}
                    <template x-ref="tpl{{ $dies->id }}">
                        <form method="POST" action="{{ route('stock.update',$dies->id) }}" data-id="{{ $dies->id }}">
                            @csrf
                            @method('PUT')

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-slate-600 mb-1">Dies Code</label>
                                    <input type="text" name="dies_code" value="{{ $dies->dies_code }}" required
                                        class="w-full rounded-xl border border-slate-300 px-3 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-slate-600 mb-1">Diameter (mm)</label>
                                    <input type="number" step="0.01" name="dies_diameter" value="{{ number_format((float)$dies->dies_diameter,2,'.','') }}" required
                                        class="w-full rounded-xl border border-slate-300 px-3 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-slate-600 mb-1">Dies Type</label>
                                    <select name="dies_type"
                                        class="w-full appearance-none rounded-xl border border-slate-300 bg-white px-3 pr-10 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                        @foreach($diesTypes as $type)
                                        <option value="{{ $type }}" {{ $dies->dies_type===$type?'selected':'' }}>{{ $type }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-slate-600 mb-1">Posisi</label>
                                    <select name="position"
                                        class="w-full appearance-none rounded-xl border border-slate-300 bg-white px-3 pr-10 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="core" {{ $dies->position==='core'?'selected':'' }}>core</option>
                                        <option value="ring" {{ $dies->position==='ring'?'selected':'' }}>ring</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-slate-600 mb-1">Crosshead Type</label>
                                    <select name="crosshead_type"
                                        class="w-full appearance-none rounded-xl border border-slate-300 bg-white px-3 pr-10 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                        @foreach($crossheadTypes as $type)
                                        <option value="{{ $type }}" {{ $dies->crosshead_type===$type?'selected':'' }}>{{ $type }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-slate-600 mb-1">Material</label>
                                    <select name="material_id"
                                        class="w-full appearance-none rounded-xl border border-slate-300 bg-white px-3 pr-10 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                        @foreach($materials as $m)
                                        <option value="{{ $m->id }}" {{ (int)$dies->material_id===(int)$m->id?'selected':'' }}>{{ $m->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-slate-600 mb-1">Material Dies</label>
                                    <input type="text" name="material_dies" value="{{ $dies->material_dies }}"
                                        class="w-full rounded-xl border border-slate-300 px-3 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-slate-600 mb-1">Ordered By</label>
                                    <input type="text" name="ordered_by" value="{{ $dies->ordered_by }}"
                                        class="w-full rounded-xl border border-slate-300 px-3 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-slate-600 mb-1">Supplier</label>
                                    <input type="text" name="supplier" value="{{ $dies->supplier }}"
                                        class="w-full rounded-xl border border-slate-300 px-3 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-slate-600 mb-1">Arrival Date</label>
                                    <input type="date" name="arrival_date"
                                        value="{{ optional($dies->arrival_date)->timezone('Asia/Jakarta')->format('Y-m-d') }}"
                                        class="w-full rounded-xl border border-slate-300 px-3 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-slate-600 mb-1">Condition</label>
                                    <input type="text" name="condition" value="{{ $dies->condition }}"
                                        class="w-full rounded-xl border border-slate-300 px-3 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-slate-600 mb-1">Checked By</label>
                                    <input type="text" name="checked_by" value="{{ $dies->checked_by }}"
                                        class="w-full rounded-xl border border-slate-300 px-3 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-slate-600 mb-1">Checked Date</label>
                                    <input type="date" name="checked_date"
                                        value="{{ optional($dies->checked_date)->timezone('Asia/Jakarta')->format('Y-m-d') }}"
                                        class="w-full rounded-xl border border-slate-300 px-3 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                </div>
                            </div>
                        </form>
                    </template>
                    @empty
                    <tr>
                        <td colspan="16" class="p-6 text-center text-slate-500">Data stock dies tidak ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="py-4">
            {{ $stockDies->withQueryString()->links() }}
        </div>
    </div>

    {{-- MODAL EDIT --}}
    <div x-cloak x-show="openEdit" class="fixed inset-0 z-50 flex items-start sm:items-center justify-center p-3 sm:p-4">
        <div class="absolute inset-0 bg-black/50" @click="openEdit=false"></div>

        <div class="relative max-w-4xl w-full bg-white rounded-2xl shadow-xl overflow-hidden mt-16 mb-6 sm:mt-0 sm:mb-0">
            <div class="flex items-center justify-between px-5 py-3 border-b border-slate-200">
                <h3 class="text-lg font-semibold text-slate-900" x-ref="editTitle">Edit</h3>
                <button class="rounded-md px-2 py-1 text-slate-600 hover:bg-slate-100" @click="openEdit=false">✕</button>
            </div>

            <div class="p-5 max-h-[70vh] overflow-auto" x-ref="editBody">
                {{-- injected form --}}
            </div>

            <div class="px-5 py-3 border-t border-slate-200 flex items-center justify-between gap-3">
                <div class="text-[12px] text-slate-500">Ubah data lalu simpan.</div>
                <div class="flex items-center gap-2">
                    <button class="px-4 py-2 rounded-lg border border-slate-300 text-slate-700 font-semibold hover:bg-slate-50" @click="openEdit=false">Close</button>
                    <button id="btnSaveEdit"
                        class="px-4 py-2 rounded-lg bg-slate-900 text-white font-semibold hover:bg-slate-800">
                        Save
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL CREATE --}}
    <div x-cloak x-show="openCreate" x-transition.opacity
        class="fixed inset-0 z-50 flex items-start sm:items-center justify-center px-3 sm:px-4 overflow-y-auto"
        @click.self="openCreate=false" aria-modal="true" role="dialog">

        {{-- backdrop --}}
        <div class="fixed inset-0 bg-black/50" @click="openCreate=false" aria-hidden="true"></div>

        {{-- modal --}}
        <div class="relative w-full max-w-3xl bg-white rounded-2xl shadow-2xl overflow-hidden
                mt-16 mb-6 sm:mt-0 sm:mb-0 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between px-6 py-4 border-b">
                <h2 class="text-lg font-semibold text-slate-900">Input Stock Dies</h2>
                <button @click="openCreate=false" class="text-slate-500 hover:text-slate-700" aria-label="Tutup">✕</button>
            </div>

            <form method="POST" action="{{ route('stock.store') }}" class="p-6">
                @csrf

                <div class="mb-6">
                    <h3 class="text-sm font-semibold text-slate-700 mb-3">Info Dies</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-slate-600 mb-1">Dies Code</label>
                            <input type="text" name="dies_code" value="{{ old('dies_code') }}" required
                                class="w-full rounded-xl border border-slate-300 px-3 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-600 mb-1">Diameter (mm)</label>
                            <input type="number" step="0.01" name="dies_diameter" value="{{ old('dies_diameter') }}" required
                                class="w-full rounded-xl border border-slate-300 px-3 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-600 mb-1">Dies Type</label>
                            <select name="dies_type"
                                class="w-full appearance-none rounded-xl border border-slate-300 bg-white px-3 pr-10 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">-- Pilih Dies Type --</option>
                                @foreach($diesTypes as $type)
                                <option value="{{ $type }}" @selected(old('dies_type')===$type)>{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-600 mb-1">Posisi</label>
                            <select name="position"
                                class="w-full appearance-none rounded-xl border border-slate-300 bg-white px-3 pr-10 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="core" @selected(old('position')==='core' )>core</option>
                                <option value="ring" @selected(old('position')==='ring' )>ring</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-600 mb-1">Crosshead Type</label>
                            <select name="crosshead_type"
                                class="w-full appearance-none rounded-xl border border-slate-300 bg-white px-3 pr-10 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">-- Pilih Crosshead Type --</option>
                                @foreach($crossheadTypes as $type)
                                <option value="{{ $type }}" @selected(old('crosshead_type')===$type)>{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-600 mb-1">Material</label>
                            <select name="material_id"
                                class="w-full appearance-none rounded-xl border border-slate-300 bg-white px-3 pr-10 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">-- Pilih Material --</option>
                                @foreach($materials as $m)
                                <option value="{{ $m->id }}" @selected(old('material_id')==$m->id)>{{ $m->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-600 mb-1">Material Dies</label>
                            <input type="text" name="material_dies" value="{{ old('material_dies') }}"
                                class="w-full rounded-xl border border-slate-300 px-3 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>
                </div>

                <div class="mb-2">
                    <h3 class="text-sm font-semibold text-slate-700 mb-3">Logistik & QC</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-slate-600 mb-1">Ordered By</label>
                            <input type="text" name="ordered_by" value="{{ old('ordered_by') }}"
                                class="w-full rounded-xl border border-slate-300 px-3 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-600 mb-1">Supplier</label>
                            <input type="text" name="supplier" value="{{ old('supplier') }}"
                                class="w-full rounded-xl border border-slate-300 px-3 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-600 mb-1">Arrival Date</label>
                            <input type="date" name="arrival_date"
                                value="{{ old('arrival_date', now('Asia/Jakarta')->format('Y-m-d')) }}"
                                class="w-full rounded-xl border border-slate-300 px-3 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-600 mb-1">Condition</label>
                            <input type="text" name="condition" value="{{ old('condition') }}"
                                class="w-full rounded-xl border border-slate-300 px-3 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-600 mb-1">Checked By</label>
                            <input type="text" name="checked_by" value="{{ old('checked_by') }}"
                                class="w-full rounded-xl border border-slate-300 px-3 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-600 mb-1">Checked Date</label>
                            <input type="date" name="checked_date"
                                value="{{ old('checked_date', now('Asia/Jakarta')->format('Y-m-d')) }}"
                                class="w-full rounded-xl border border-slate-300 px-3 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex gap-3 justify-end border-t pt-4">
                    <button type="button" @click="openCreate=false"
                        class="px-4 py-2.5 rounded-xl border border-slate-300 text-slate-700 hover:bg-slate-50">Batal</button>
                    <button type="submit"
                        class="px-4 py-2.5 rounded-xl bg-green-600 text-white hover:bg-green-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- TOASTS: pojok kanan bawah dengan animasi --}}
<div class="fixed inset-0 pointer-events-none z-[9999] flex items-end justify-end p-4 sm:p-6">
    <div class="w-full max-w-sm space-y-3" x-data x-cloak>
        <template x-for="t in $store.toasts?.items || []" :key="t.id">
            <div
                class="pointer-events-auto overflow-hidden rounded-xl bg-white shadow-xl ring-1 ring-black/5"
                x-transition:enter="transition duration-200 ease-out"
                x-transition:enter-start="opacity-0 translate-y-3 translate-x-3 scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 translate-x-0 scale-100"
                x-transition:leave="transition duration-150 ease-in"
                x-transition:leave-start="opacity-100 translate-y-0 translate-x-0 scale-100"
                x-transition:leave-end="opacity-0 translate-y-2 translate-x-2 scale-95">
                <div class="p-4 flex gap-3 items-start">
                    <div class="mt-0.5">
                        <svg x-show="t.type==='success'" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-600" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 00-1.414 0L8 12.586 4.707 9.293a1 1 0 00-1.414 1.414l4 4a1 1 0 001.414 0l8-8a1 1 0 000-1.414z" clip-rule="evenodd" />
                        </svg>
                        <svg x-show="t.type==='error'" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-rose-600" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-5.25a1 1 0 102 0V7.25a1 1 0 10-2 0v5.5zM10 15a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-semibold" x-text="t.title"></p>
                        <p class="mt-0.5 text-sm text-slate-700" x-text="t.message"></p>
                    </div>
                    <button class="ml-auto rounded-md p-1 text-slate-400 hover:text-slate-600" @click="$store.toasts.remove(t.id)" aria-label="Tutup">✕</button>
                </div>
                <div class="h-1 w-full relative overflow-hidden">
                    <div :class="t.type==='success' ? 'bg-emerald-600' : 'bg-rose-600'"
                        class="absolute inset-y-0 left-0"
                        :style="`width:${t.bar}%`"></div>
                </div>
            </div>
        </template>
    </div>
</div>

<script>
    // Toast helper
    function showToast(type, msg) {
        const wrap = document.getElementById('toast-wrap') || (() => {
            const d = document.createElement('div');
            d.id = 'toast-wrap';
            d.className = 'fixed right-4 bottom-4 z-[9999] flex flex-col gap-2';
            document.body.appendChild(d);
            return d;
        })();

        const el = document.createElement('div');
        const base = 'pointer-events-auto min-w-[260px] max-w-sm rounded-xl px-4 py-3 shadow-lg text-white flex items-start gap-3 animate-[slide-up_0.25s_ease-out]';
        const color = type === 'error' ? 'bg-rose-600' : 'bg-emerald-600';
        el.className = base + ' ' + color;

        el.innerHTML = `
      <div class="shrink-0">${type === 'error'
        ? '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M4.93 4.93l14.14 14.14M4.93 19.07L19.07 4.93"/></svg>'
        : '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>'}
      </div>
      <div class="text-sm leading-5">${msg || (type === 'error' ? 'Terjadi kesalahan' : 'Berhasil')}</div>
      <button class="ml-auto text-white/80 hover:text-white" aria-label="Close">&times;</button>
    `;

        el.querySelector('button').onclick = () => el.remove();
        wrap.appendChild(el);
        setTimeout(() => {
            el.style.transition = 'opacity .2s ease';
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 220);
        }, 3000);
    }

    // Handler tombol Save di modal edit
    document.addEventListener('click', async (e) => {
        if (e.target.id !== 'btnSaveEdit') return;

        const body = document.querySelector('[x-ref="editBody"]');
        const form = body?.querySelector('form');
        if (!form) return;

        const fd = new FormData(form);
        fd.set('_method', 'PUT');

        const csrf = form.querySelector('input[name=_token]')?.value ||
            document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
            '{{ csrf_token() }}';

        try {
            const res = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json'
                },
                credentials: 'same-origin',
                body: fd
            });

            let data = null,
                text = null;
            const ct = res.headers.get('content-type') || '';
            if (ct.includes('application/json')) data = await res.json();
            else text = await res.text();
            if (!res.ok) throw new Error(data?.message || text || ('HTTP ' + res.status));

            // Update row di tabel
            const id = form.dataset.id;
            const row = document.querySelector('tr[data-row="' + id + '"]');
            if (row) {
                const get = (name) => form.querySelector('[name="' + name + '"]');
                const setTxt = (field, txt) => {
                    const cell = row.querySelector('[data-field="' + field + '"]');
                    if (!cell) return;
                    const span = cell.querySelector('span');
                    if (span) span.textContent = (txt ?? '-') || '-';
                    else cell.textContent = (txt ?? '-') || '-';
                };
                const toFixed2 = (v) => (v === '' || v == null) ? '' : Number(v).toFixed(2);

                setTxt('dies_code', get('dies_code')?.value);
                setTxt('dies_diameter', toFixed2(get('dies_diameter')?.value));
                setTxt('dies_type', get('dies_type')?.value);
                setTxt('position', get('position')?.value);
                setTxt('crosshead_type', get('crosshead_type')?.value);

                const matSel = get('material_id');
                const matText = matSel ? (matSel.options[matSel.selectedIndex]?.text || '') : '';
                setTxt('material', matText);

                setTxt('material_dies', get('material_dies')?.value);
                setTxt('ordered_by', get('ordered_by')?.value);
                setTxt('supplier', get('supplier')?.value);
                setTxt('arrival_date', get('arrival_date')?.value);
                setTxt('condition', get('condition')?.value);
                setTxt('checked_by', get('checked_by')?.value);
                setTxt('checked_date', get('checked_date')?.value);

                if (data?.updated_at) setTxt('updated_at', data.updated_at);
            }

            window.dispatchEvent(new CustomEvent('close-edit'));
            showToast('success', (data?.message) || 'Data berhasil disimpan');

        } catch (err) {
            showToast('error', err?.message || 'Gagal menyimpan');
        }
    });
</script>
@endsection