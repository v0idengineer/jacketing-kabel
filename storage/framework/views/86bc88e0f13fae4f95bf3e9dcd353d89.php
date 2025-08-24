

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jacketing Kabel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<?php $__env->startSection('content'); ?>
<div x-data="{ show: false }" x-init="setTimeout(() => show = true, 100)">
    <div
        x-show="show"
        x-transition:enter="transition-opacity transition-transform duration-700 delay-100 ease-out"
        x-transition:enter-start="opacity-0 translate-x-24"
        x-transition:enter-end="opacity-100 translate-x-0"
        x-transition:leave="transition-opacity transition-transform duration-500 ease-in"
        x-transition:leave-start="opacity-100 translate-x-0"
        x-transition:leave-end="opacity-0 translate-x-24"
        class="container my-5 mx-auto px-4">

        <form id="jacketingForm" method="POST" action="<?php echo e(route('jacketing.store')); ?>" x-data="{ step: 1 }">
            <?php if($errors->any()): ?>
            <div class="mb-4 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-rose-800">
                <ul class="list-disc pl-5">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $err): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($err); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
            <?php endif; ?>
            <?php echo csrf_field(); ?>

            
            <input type="hidden" name="machine" value="<?php echo e($machine); ?>">

            
            <input type="hidden" id="form_action" name="_action" value="">

            
            <input type="hidden" id="selected_core_source" name="selected_core_source">
            <input type="hidden" id="selected_ring_source" name="selected_ring_source">
            <input type="hidden" id="selected_core_dia" name="selected_core_dia">
            <input type="hidden" id="selected_ring_dia" name="selected_ring_dia">

            
            <input type="hidden" name="core_die_id" id="core_die_id">
            <input type="hidden" name="ring_die_id" id="ring_die_id">

            
            <div id="card1" class="mb-6 rounded-2xl border border-slate-200 bg-white shadow-sm hover:shadow-md transition-all">
                <div class="px-6 py-4 text-lg font-bold text-slate-900 border-b border-slate-200">
                    Crosshead & Dies
                </div>

                <div class="p-6 space-y-5">
                    
                    <div>
                        <label for="crosshead_type" class="block text-sm font-semibold text-slate-700 mb-1.5">Crosshead Type</label>
                        <div class="relative">
                            <select
                                id="crosshead_type"
                                name="crosshead_type"
                                x-ref="crosshead_type"
                                :class="step >= 2 ? 'pointer-events-none opacity-60' : ''"
                                class="block w-full appearance-none rounded-xl border border-slate-300 bg-white
                 px-3 pr-10 py-2.5 text-slate-700 shadow-sm transition-colors
                 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500
                 hover:border-slate-400">
                                <option value="">-- Pilih --</option>
                                <?php $__currentLoopData = $crossheads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option
                                    value="<?php echo e($item); ?>"
                                    <?php echo e(old('crosshead_type', $form1['crosshead_type'] ?? '') == $item ? 'selected' : ''); ?>>
                                    <?php echo e($item); ?>

                                </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <svg class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 h-5 w-5 text-slate-500"
                                viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd"
                                    d="M5.23 7.21a.75.75 0 011.06.02L10 11.18l3.71-3.95a.75.75 0 111.08 1.04l-4.25 4.52a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>

                    
                    <div>
                        <label for="dies_type" class="block text-sm font-semibold text-slate-700 mb-1.5">Dies Type</label>
                        <div class="relative">
                            <select
                                id="dies_type"
                                name="dies_type"
                                x-ref="dies_type"
                                :class="step >= 2 ? 'pointer-events-none opacity-60' : ''"
                                class="block w-full appearance-none rounded-xl border border-slate-300 bg-white
                 px-3 pr-10 py-2.5 text-slate-700 shadow-sm transition-colors
                 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500
                 hover:border-slate-400">
                                <option value="">-- Pilih --</option>
                                <?php $__currentLoopData = $diesTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option
                                    value="<?php echo e($item); ?>"
                                    <?php echo e(old('dies_type', $form1['dies_type'] ?? '') == $item ? 'selected' : ''); ?>>
                                    <?php echo e($item); ?>

                                </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <svg class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 h-5 w-5 text-slate-500"
                                viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd"
                                    d="M5.23 7.21a.75.75 0 011.06.02L10 11.18l3.71-3.95a.75.75 0 111.08 1.04l-4.25 4.52a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>

                    
                    <button
                        type="button"
                        id="btnNextCard1"
                        @click="
        if ($refs.crosshead_type.value && $refs.dies_type.value) {
          step = 2;
        } else {
          Swal.fire({
            icon: 'warning',
            title: 'Peringatan',
            text: 'Silakan pilih Crosshead dan Dies Type terlebih dahulu!',
            confirmButtonColor: '#3B82F6'
          });
        }
      "
                        class="w-full rounded-lg px-4 py-2.5 bg-slate-900 text-white font-semibold hover:bg-slate-800 transition-colors">
                        Next →
                    </button>
                </div>
            </div>

            
            <div id="card2" x-ref="card2"
                class="mb-6 rounded-2xl border border-slate-200 bg-white shadow-sm hover:shadow-md transition-all">
                <div class="px-6 py-4 text-lg font-bold text-slate-900 border-b border-slate-200">
                    Resep Produksi
                </div>

                <div class="p-6 space-y-5"
                    x-show="step >= 2"
                    x-transition:enter="transition-all duration-500 ease-out"
                    x-transition:enter-start="opacity-0 max-h-0"
                    x-transition:enter-end="opacity-100 max-h-[1000px]"
                    x-transition:leave="transition-all duration-300 ease-in"
                    x-transition:leave-start="opacity-100 max-h-[1000px]"
                    x-transition:leave-end="opacity-0 max-h-0"
                    style="overflow:hidden">

                    
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Creator</label>
                        <input type="text" name="creator"
                            value="<?php echo e(old('creator', $form2['creator'] ?? '')); ?>"
                            x-bind:readonly="step >= 3"
                            x-bind:class="step >= 3 ? 'opacity-60' : ''"
                            class="block w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-slate-700 shadow-sm
                    focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                            placeholder="Nama pembuat" required>
                    </div>

                    
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Cable Type</label>
                        <input type="text" name="cable_type"
                            value="<?php echo e(old('cable_type', $form2['cable_type'] ?? '')); ?>"
                            x-bind:readonly="step >= 3"
                            x-bind:class="step >= 3 ? 'opacity-60' : ''"
                            class="block w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-slate-700 shadow-sm
                    focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                            placeholder="Jenis kabel" required>
                    </div>

                    
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">SAP Number</label>
                        <input type="text" name="sap_number"
                            value="<?php echo e(old('sap_number', $form2['sap_number'] ?? '')); ?>"
                            x-bind:readonly="step >= 3"
                            x-bind:class="step >= 3 ? 'opacity-60' : ''"
                            class="block w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-slate-700 shadow-sm
                    focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                            placeholder="Nomor SAP" required>
                    </div>

                    
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Order By</label>
                        <input type="text" name="order_by"
                            value="<?php echo e(old('order_by', $form2['order_by'] ?? '')); ?>"
                            x-bind:readonly="step >= 3"
                            x-bind:class="step >= 3 ? 'opacity-60' : ''"
                            class="block w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-slate-700 shadow-sm
                    focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                            placeholder="Pemesan" required>
                    </div>

                    
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Process</label>
                        <div class="relative">
                            <select name="process" id="process"
                                :class="step >= 3 ? 'pointer-events-none opacity-60' : ''"
                                class="block w-full appearance-none rounded-xl border border-slate-300 bg-white
                       px-3 pr-10 py-2.5 text-slate-700 shadow-sm transition-colors
                       focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500
                       hover:border-slate-400"
                                required>
                                <option value="">-- Pilih --</option>
                                <?php $__currentLoopData = $processes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $proc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($proc); ?>" <?php echo e(old('process', $form2['process'] ?? '') == $proc ? 'selected' : ''); ?>>
                                    <?php echo e(ucwords(str_replace('_', ' ', $proc))); ?>

                                </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <svg class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 h-5 w-5 text-slate-500"
                                viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.18l3.71-3.95a.75.75 0 111.08 1.04l-4.25 4.52a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>

                    
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Date</label>
                        <input type="date" name="date"
                            class="block w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-slate-700 shadow-sm
                    focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                            value="<?php echo e(old('date', $jakartaDate)); ?>" readonly>
                    </div>

                    
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Material</label>
                        <?php
                        $selected = (string) old('material_id', $form2['material_id'] ?? '');
                        $items = ($materials ?? collect());
                        $avail = $items->filter(fn($m) => (int)($m->stock_count ?? 0) > 0);
                        $empty = $items->reject(fn($m) => (int)($m->stock_count ?? 0) > 0);
                        ?>

                        <div class="relative">
                            <select name="material_id" id="material_id"
                                :class="step >= 3 ? 'pointer-events-none opacity-60' : ''"
                                class="block w-full appearance-none rounded-xl border border-slate-300 bg-white
                       px-3 pr-10 py-2.5 text-slate-700 shadow-sm transition-colors
                       focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500
                       hover:border-slate-400"
                                required>
                                <option value="">-- Pilih material --</option>

                                <?php if($avail->count()): ?>
                                <optgroup label="Tersedia">
                                    <?php $__currentLoopData = $avail; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($m->id); ?>" data-has-stock="1"
                                        style="color:#16a34a; font-weight:600;"
                                        <?php echo e($selected === (string)$m->id ? 'selected' : ''); ?>>
                                        <?php echo e($m->name); ?> (stok: <?php echo e((int)$m->stock_count); ?>)
                                    </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </optgroup>
                                <?php endif; ?>

                                <?php if($empty->count()): ?>
                                <optgroup label="Tidak ada stok">
                                    <?php $__currentLoopData = $empty; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($m->id); ?>" data-has-stock="0" disabled style="color:#94a3b8;">
                                        <?php echo e($m->name); ?> (kosong)
                                    </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </optgroup>
                                <?php endif; ?>
                            </select>
                            <svg class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 h-5 w-5 text-slate-500"
                                viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.18l3.71-3.95a.75.75 0 111.08 1.04l-4.25 4.52a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                            </svg>
                        </div>

                        <?php $__errorArgs = ['material_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-sm text-red-600 mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                        <div class="mt-1 text-xs text-gray-500 flex gap-3">
                            <span><span class="inline-block w-2 h-2 rounded-full align-middle" style="background:#16a34a"></span> tersedia</span>
                            <span><span class="inline-block w-2 h-2 rounded-full align-middle" style="background:#94a3b8"></span> tidak ada stok</span>
                        </div>
                    </div>

                    
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Input Diameter (mm)</label>
                        <input type="number" step="0.01" min="1" max="50"
                            name="input_diameter" id="input_diameter"
                            value="<?php echo e(old('input_diameter', $form2['input_diameter'] ?? '')); ?>"
                            x-bind:readonly="step >= 3"
                            x-bind:class="step >= 3 ? 'opacity-60' : ''"
                            class="block w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-slate-700 shadow-sm
                    focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                            placeholder="Diameter input" required
                            oninput="calculateOutput()">
                    </div>

                    
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Working Thickness (mm)</label>
                        <input type="number" step="0.01" min="0.5" max="5"
                            name="working_thickness" id="working_thickness"
                            value="<?php echo e(old('working_thickness', $form2['working_thickness'] ?? '')); ?>"
                            x-bind:readonly="step >= 3"
                            x-bind:class="step >= 3 ? 'opacity-60' : ''"
                            class="block w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-slate-700 shadow-sm
                    focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                            placeholder="Ketebalan kerja" required
                            oninput="calculateOutput()">
                    </div>

                    
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Output Diameter (mm)</label>
                        <input type="number" step="0.01" name="output_diameter" id="output_diameter"
                            value="<?php echo e(old('output_diameter', $form2['output_diameter'] ?? '')); ?>"
                            class="block w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-slate-700 shadow-sm
                    focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                            placeholder="Hasil perhitungan otomatis" readonly>
                        <p id="diameter_warning" class="text-xs mt-1 text-slate-500"></p>
                    </div>

                    
                    <button type="button"
                        @click="
              let inputs = $refs.card2.querySelectorAll('input, select, textarea');
              let kosong = Array.from(inputs).some(el => el.type !== 'hidden' && !el.value.trim());
              if (kosong) {
                Swal.fire({ icon: 'warning', title: 'Peringatan', text: 'Silakan lengkapi semua data pada Resep Produksi terlebih dahulu!', confirmButtonColor: '#3B82F6' });
              } else {
                step = 3;
                if (typeof loadRecommendations === 'function') loadRecommendations();
                if (typeof computeTargetCoreRing === 'function') computeTargetCoreRing();
                if (typeof renderCalcOptions === 'function') renderCalcOptions(true);
              }"
                        class="w-full rounded-lg px-4 py-2.5 bg-slate-900 text-white font-semibold hover:bg-slate-800 transition-colors">
                        Next →
                    </button>
                </div>
            </div>

            
            <div id="card3"
                class="mb-6 rounded-2xl border border-slate-200 bg-white shadow-sm hover:shadow-md transition-all">
                <div class="px-6 py-4 text-lg font-bold text-slate-900 border-b border-slate-200">
                    Kalkulasi
                </div>

                <div class="p-6 space-y-6"
                    x-show="step >= 3"
                    x-transition:enter="transition-all duration-500 ease-out"
                    x-transition:enter-start="opacity-0 max-h-0"
                    x-transition:enter-end="opacity-100 max-h-[2000px]"
                    x-transition:leave="transition-all duration-300 ease-in"
                    x-transition:leave-start="opacity-100 max-h-[2000px]"
                    x-transition:leave-end="opacity-0 max-h-0"
                    style="overflow:hidden">

                    
                    <div class="rounded-xl border border-slate-200 p-4 flex flex-col">
                        <div class="font-semibold text-slate-700 mb-3">Hasil Kalkulasi</div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-1.5">Core Dies</label>
                                <div id="calc_core_options" class="flex flex-wrap gap-2"></div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-1.5">Ring Dies</label>
                                <div id="calc_ring_options" class="flex flex-wrap gap-2"></div>
                            </div>
                        </div>

                        
                        <div class="mt-4 flex justify-end">
                            <button type="submit" id="btnDrawDesign"
                                class="px-4 py-2 rounded-lg bg-black text-white font-semibold hover:bg-neutral-800">
                                Draw Design
                            </button>
                        </div>
                    </div>

                    
                    <div class="rounded-xl border border-slate-200 p-4 flex flex-col">
                        <div class="font-semibold text-slate-700 mb-3">Opsi Dies dari Stok</div>

                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                            <div class="md:col-span-3 space-y-4">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">Stock Dies — Core</label>
                                    <div id="core_options" class="flex flex-wrap gap-2"></div>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">Stock Dies — Ring</label>
                                    <div id="ring_options" class="flex flex-wrap gap-2"></div>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">DDR (Actual)</label>
                                    <input type="text" id="c3_ddr" name="ddr"
                                        class="block w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-slate-800 shadow-sm"
                                        readonly>
                                    <small id="ddr_hint" class="text-xs text-slate-500"></small>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">DBR (Actual)</label>
                                    <input type="text" id="c3_dbr" name="dbr"
                                        class="block w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-slate-800 shadow-sm"
                                        readonly>
                                    <small id="dbr_hint" class="text-xs text-slate-500"></small>
                                </div>
                            </div>
                        </div>

                        
                        <div class="mt-4 flex justify-end">
                            <button type="button" id="btnDownloadReport"
                                class="px-4 py-2 rounded-lg bg-black text-white font-semibold hover:bg-neutral-800">
                                Download Report
                            </button>
                        </div>
                    </div>

                    <div class="mt-6">
                        <a href="<?php echo e(route('jacketing.index')); ?>"
                            class="rounded-lg px-4 py-2.5 bg-slate-100 text-slate-800 font-semibold hover:bg-slate-200 transition-colors">
                            Kembali
                        </a>
                    </div>
                </div>
            </div>

            
            <?php if(session('saved_recipe_id')): ?>
            <div x-data="{open:true}" x-show="open" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
                <div class="bg-white rounded-2xl p-6 w-[420px] shadow-xl">
                    <h3 class="text-lg font-bold mb-1">Data tersimpan ✅</h3>
                    <p class="text-sm text-gray-600 mb-4">Resep dan riwayat produksi berhasil disimpan.</p>
                    <div class="flex gap-3 justify-end">
                        <a href="<?php echo e(route('design.print', ['recipe'=>session('saved_recipe_id')])); ?>"
                            class="px-4 py-2 rounded-lg bg-indigo-600 text-white font-semibold hover:bg-indigo-700">
                            Cetak Design
                        </a>
                        <a href="<?php echo e(route('jacketing.index')); ?>"
                            class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300">
                            Kembali
                        </a>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </form>

    </div>
</div>
<?php $__env->stopSection(); ?>


<script>
    tailwind.config = {
        theme: {
            extend: {}
        },
        plugins: [],
        corePlugins: {
            transitionProperty: true,
            transitionDuration: true,
            transitionTimingFunction: true,
            transitionDelay: true,
            opacity: true,
            transform: true,
        }
    }
</script>


<script>
    function calculateOutput() {
        const inputA = parseFloat(document.getElementById('input_diameter').value);
        const F = parseFloat(document.getElementById('working_thickness').value);
        const warning = document.getElementById('diameter_warning');
        const outputField = document.getElementById('output_diameter');

        if (!isNaN(inputA) && !isNaN(F)) {
            const output = inputA + (2 * F);
            const val = output.toFixed(2);
            outputField.value = val;

            outputField.classList.remove('border-green-500', 'border-red-500');
            warning.textContent = "";

            if (inputA < 1 || inputA > 50) {
                warning.textContent = "⚠️ Input Diameter harus antara 1 - 50 mm";
                outputField.classList.add('border-red-500');
            } else if (F < 0.5 || F > 5) {
                warning.textContent = "⚠️ Working Thickness harus antara 0.5 - 5 mm";
                outputField.classList.add('border-red-500');
            } else {
                outputField.classList.add('border-green-500');
                if (typeof loadRecommendations === 'function') loadRecommendations();
                if (typeof computeTargetCoreRing === 'function') computeTargetCoreRing();
                if (typeof renderCalcOptions === 'function') renderCalcOptions();
            }
        } else {
            outputField.value = "";
            warning.textContent = "";
        }
    }
</script>


<script>
    function num(v) {
        return v == null || v === '' ? null : Number(v)
    }

    function setVal(id, v) {
        const el = document.getElementById(id);
        if (el) el.value = v ?? ''
    }

    function badge(el, ok) {
        el.classList.remove('text-red-600', 'text-green-600');
        el.classList.add(ok ? 'text-green-600' : 'text-red-600')
    }

    const pickState = {
        core: {
            source: null,
            dia: null,
            id: null
        },
        ring: {
            source: null,
            dia: null,
            id: null
        }
    };

    function commitStateHidden() {
        setVal('selected_core_source', pickState.core.source || '');
        setVal('selected_ring_source', pickState.ring.source || '');
        setVal('selected_core_dia', pickState.core.dia != null ? Number(pickState.core.dia).toFixed(2) : '');
        setVal('selected_ring_dia', pickState.ring.dia != null ? Number(pickState.ring.dia).toFixed(2) : '');
        setVal('core_die_id', pickState.core.source === 'stock' ? (pickState.core.id || '') : '');
        setVal('ring_die_id', pickState.ring.source === 'stock' ? (pickState.ring.id || '') : '');
    }

    window._btnsCoreCalc = [];
    window._btnsRingCalc = [];
    window._btnsCoreStock = [];
    window._btnsRingStock = [];

    function highlightPicked() {
        const all = [...(window._btnsCoreCalc || []), ...(window._btnsRingCalc || []), ...(window._btnsCoreStock || []), ...(window._btnsRingStock || [])];
        all.forEach(b => b.classList.remove('ring-2', 'ring-offset-2', 'ring-indigo-500'));
        const mark = (role, source, dia, id) => {
            const list = role === 'core' ? (source === 'calc' ? window._btnsCoreCalc : window._btnsCoreStock) :
                (source === 'calc' ? window._btnsRingCalc : window._btnsRingStock);
            (list || []).forEach(b => {
                const sameSource = b.dataset.source === source;
                const sameDia = Math.abs(Number(b.dataset.dia) - Number(dia)) < 1e-6;
                const sameId = source === 'calc' ? true : (String(b.dataset.id) === String(id));
                if (sameSource && sameDia && sameId) b.classList.add('ring-2', 'ring-offset-2', 'ring-indigo-500');
            });
        };
        if (pickState.core.source) mark('core', pickState.core.source, pickState.core.dia, pickState.core.id);
        if (pickState.ring.source) mark('ring', pickState.ring.source, pickState.ring.dia, pickState.ring.id);
    }

    function getMaterialLabel() {
        const sel = document.getElementById('material_id');
        return sel?.options?.[sel.selectedIndex]?.text || ''
    }

    function isPVC() {
        return getMaterialLabel().toLowerCase().includes('pvc')
    }

    function isPE() {
        const t = getMaterialLabel().toLowerCase();
        return t.includes(' pe') || t.includes('hdpe') || t.includes('lldpe')
    }

    function getDiesType() {
        return (document.querySelector('select[name="dies_type"]')?.value || document.querySelector('input[name="dies_type"]')?.value || '').toLowerCase()
    }

    function getTol(targetMm) {
        const byPct = (Number(targetMm) || 0) * 0.03;
        return Math.max(0.20, byPct)
    }

    function computeTargetCoreRing() {
        const A = Number(document.getElementById('input_diameter')?.value);
        const F = Number(document.getElementById('working_thickness')?.value);
        const E = Number(document.getElementById('output_diameter')?.value);
        const diesType = getDiesType();

        let coreTarget = null,
            ringTarget = null;
        if (!isNaN(A) && !isNaN(F) && !isNaN(E) && E > 0) {
            if (diesType.includes('compress')) {
                coreTarget = A + 2;
                ringTarget = isPE() ? (E + 0.2 * F) : E;
            } else {
                const ddrT = (window.ddrRange?.min != null && window.ddrRange?.max != null) ? (Number(window.ddrRange.min) + Number(window.ddrRange.max)) / 2 : null;
                const dbrT = (window.dbrRange?.min != null && window.dbrRange?.max != null) ? (Number(window.dbrRange.min) + Number(window.dbrRange.max)) / 2 : null;
                if (ddrT != null) coreTarget = E * ddrT;
                if (dbrT != null) ringTarget = E * dbrT;
            }
        }
        window._targetCore = coreTarget != null ? Number(coreTarget.toFixed(2)) : null;
        window._targetRing = ringTarget != null ? Number(ringTarget.toFixed(2)) : null;
    }

    /* Render opsi kalkulasi (abu) */
    function renderCalcOptions(autoPick = false) {
        const coreBox = document.getElementById('calc_core_options');
        const ringBox = document.getElementById('calc_ring_options');
        if (!coreBox || !ringBox) return;
        coreBox.innerHTML = '';
        ringBox.innerHTML = '';
        window._btnsCoreCalc = [];
        window._btnsRingCalc = [];
        const diesType = getDiesType();
        const makeBtn = (label, role, dia, title, onClick) => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'px-3 py-1 rounded-lg bg-slate-600 hover:bg-slate-700 text-white text-sm font-semibold';
            btn.textContent = label;
            if (title) btn.title = title;
            btn.dataset.role = role;
            btn.dataset.source = 'calc';
            btn.dataset.dia = Number(dia).toFixed(2);
            btn.onclick = onClick;
            return btn;
        };

        if (window._targetCore != null) {
            const tol = getTol(window._targetCore);
            const title = diesType.includes('compress') ? `Target ${window._targetCore.toFixed(2)} ± ${tol.toFixed(2)} mm` :
                (window.ddrRange ? `DDR target ${window.ddrRange.min ?? '-'}–${window.ddrRange.max ?? '-'}` : '');
            const b = makeBtn(`Core Calc (${window._targetCore.toFixed(2)})`, 'core', window._targetCore, title, () => chooseCoreCalc(window._targetCore));
            window._btnsCoreCalc.push(b);
            coreBox.appendChild(b);
            if (autoPick) chooseCoreCalc(window._targetCore, {
                silent: true
            });
        } else coreBox.innerHTML = '<span class="text-sm text-gray-500">Target core belum tersedia.</span>';

        if (window._targetRing != null) {
            const tol = getTol(window._targetRing);
            const title = diesType.includes('compress') ? `Target ${window._targetRing.toFixed(2)} ± ${tol.toFixed(2)} mm` :
                (window.dbrRange ? `DBR target ${window.dbrRange.min ?? '-'}–${window.dbrRange.max ?? '-'}` : '');
            const b = makeBtn(`Ring Calc (${window._targetRing.toFixed(2)})`, 'ring', window._targetRing, title, () => chooseRingCalc(window._targetRing));
            window._btnsRingCalc.push(b);
            ringBox.appendChild(b);
            if (autoPick) chooseRingCalc(window._targetRing, {
                silent: true
            });
        } else ringBox.innerHTML = '<span class="text-sm text-gray-500">Target ring belum tersedia.</span>';

        highlightPicked();
        updateDDRDBR();
    }

    async function loadRecommendations() {
        const materialId = document.querySelector('select[name="material_id"]')?.value;
        const output = document.getElementById('output_diameter')?.value;
        const crosshead = document.querySelector('select[name="crosshead_type"]')?.value || document.querySelector('input[name="crosshead_type"]')?.value || '';
        const diesType = document.querySelector('select[name="dies_type"]')?.value || document.querySelector('input[name="dies_type"]')?.value || '';

        if (!materialId || !output) {
            renderOptions('core_options', [], () => {});
            renderOptions('ring_options', [], () => {});
            return;
        }

        const url = new URL(<?php echo json_encode(route('dies.recommend'), 15, 512) ?>);
        url.searchParams.set('material_id', materialId);
        url.searchParams.set('output', output);
        if (crosshead) url.searchParams.set('crosshead_type', crosshead);
        if (diesType) url.searchParams.set('dies_type', diesType);

        const res = await fetch(url.toString(), {
            headers: {
                'Accept': 'application/json'
            }
        });
        if (!res.ok) {
            console.error('recommend error', res.status);
            return;
        }
        const data = await res.json();

        renderOptions('core_options', data.core || [], chooseCoreStock);
        renderOptions('ring_options', data.ring || [], chooseRingStock);

        window.ddrRange = {
            min: num(data.ranges?.ddr_min),
            max: num(data.ranges?.ddr_max)
        };
        window.dbrRange = {
            min: num(data.ranges?.dbr_min),
            max: num(data.ranges?.dbr_max)
        };

        computeTargetCoreRing();
        renderCalcOptions();
    }

    function renderOptions(containerId, list, handler) {
        const box = document.getElementById(containerId);
        if (!box) return;
        box.innerHTML = '';
        const out = Number(document.getElementById('output_diameter')?.value || 0);
        const items = list || [];
        let okCount = 0;
        const isCore = containerId.startsWith('core');
        const diesType = getDiesType();
        const targetMm = isCore ? window._targetCore : window._targetRing;
        const tol = getTol(targetMm);
        const rng = isCore ? window.ddrRange : window.dbrRange;
        const min = num(rng?.min),
            max = num(rng?.max);
        if (isCore) window._btnsCoreStock = [];
        else window._btnsRingStock = [];

        items.forEach((row, i) => {
            const dia = Number(row.dies_diameter);
            const ratio = out && dia ? (dia / out) : null;
            let ok = false;
            let title = '';
            if (diesType.includes('compress')) {
                ok = (targetMm > 0 && Math.abs(dia - targetMm) <= tol);
                title = `Target ${targetMm?targetMm.toFixed(2):'-'} ± ${tol.toFixed(2)} mm` + (ratio ? ` • Rasio ${(isCore?'DDR':'DBR')} ${ratio.toFixed(3)}` : '');
            } else {
                ok = ratio != null && (min == null || ratio >= min) && (max == null || ratio <= max);
                title = (ratio ? `${isCore?'DDR':'DBR'} = ${ratio.toFixed(3)}` : '') + ` • Target ${min ?? '-'}–${max ?? '-'}`;
            }

            const btn = document.createElement('button');
            btn.type = 'button';
            btn.textContent = `${isCore?'Core':'Ring'}-${i+1} (${row.dies_diameter})`;
            btn.title = title;
            btn.dataset.role = isCore ? 'core' : 'ring';
            btn.dataset.source = 'stock';
            btn.dataset.id = row.id;
            btn.dataset.dia = dia.toFixed(2);

            if (ok) {
                btn.className = 'px-3 py-1 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold';
                btn.onclick = () => handler(row.id, dia);
                okCount++;
            } else {
                if (diesType.includes('compress')) {
                    btn.className = 'px-3 py-1 rounded-lg bg-amber-100 text-amber-700 border border-amber-300 text-sm font-semibold hover:bg-amber-200';
                    btn.onclick = () => handler(row.id, dia);
                } else {
                    btn.className = 'px-3 py-1 rounded-lg bg-rose-100 text-rose-700 border border-rose-300 text-sm font-semibold cursor-not-allowed opacity-70 line-through';
                    btn.disabled = true;
                }
            }

            if (isCore) window._btnsCoreStock.push(btn);
            else window._btnsRingStock.push(btn);
            box.appendChild(btn);
        });

        if (items.length && okCount === 0 && !getDiesType().includes('compress')) {
            (isCore ? window._btnsCoreStock : window._btnsRingStock).forEach((btn) => {
                btn.className = 'px-3 py-1 rounded-lg bg-amber-100 text-amber-700 border border-amber-300 text-sm font-semibold hover:bg-amber-200';
                btn.disabled = false;
                const id = btn.dataset.id,
                    dia = Number(btn.dataset.dia);
                btn.onclick = () => (isCore ? chooseCoreStock(id, dia) : chooseRingStock(id, dia));
            });
            const note = document.createElement('div');
            note.className = 'text-xs text-amber-600 mt-2';
            note.textContent = 'Tidak ada kandidat dalam rentang — menampilkan yang terdekat.';
            box.appendChild(note);
        }

        highlightPicked();
    }

    function chooseCoreCalc(dia, opt = {}) {
        pickState.core = {
            source: 'calc',
            dia: Number(dia),
            id: null
        };
        commitStateHidden();
        highlightPicked();
        if (!opt.silent) updateDDRDBR();
    }

    function chooseRingCalc(dia, opt = {}) {
        pickState.ring = {
            source: 'calc',
            dia: Number(dia),
            id: null
        };
        commitStateHidden();
        highlightPicked();
        if (!opt.silent) updateDDRDBR();
    }

    function chooseCoreStock(id, dia) {
        pickState.core = {
            source: 'stock',
            dia: Number(dia),
            id: id
        };
        commitStateHidden();
        highlightPicked();
        updateDDRDBR();
    }

    function chooseRingStock(id, dia) {
        pickState.ring = {
            source: 'stock',
            dia: Number(dia),
            id: id
        };
        commitStateHidden();
        highlightPicked();
        updateDDRDBR();
    }

    function updateDDRDBR() {
        const out = Number(document.getElementById('output_diameter')?.value || 0);
        const coreDia = pickState.core.dia;
        const ringDia = pickState.ring.dia;
        const ddr = out && coreDia ? (coreDia / out) : null;
        const dbr = out && ringDia ? (ringDia / out) : null;
        setVal('c3_ddr', ddr != null ? ddr.toFixed(3) : '');
        setVal('c3_dbr', dbr != null ? dbr.toFixed(3) : '');
        const diesType = getDiesType();
        const ddrHint = document.getElementById('ddr_hint');
        const dbrHint = document.getElementById('dbr_hint');

        if (diesType.includes('compress')) {
            const tgtC = window._targetCore,
                tgtR = window._targetRing;
            const tolC = getTol(tgtC),
                tolR = getTol(tgtR);
            if (ddrHint) {
                const ok = coreDia != null && tgtC > 0 && Math.abs(coreDia - tgtC) <= tolC;
                badge(ddrHint, ok);
                ddrHint.textContent = `Target: ${tgtC?tgtC.toFixed(2):'-'} ± ${tolC.toFixed(2)} mm`;
            }
            if (dbrHint) {
                const ok = ringDia != null && tgtR > 0 && Math.abs(ringDia - tgtR) <= tolR;
                badge(dbrHint, ok);
                dbrHint.textContent = `Target: ${tgtR?tgtR.toFixed(2):'-'} ± ${tolR.toFixed(2)} mm`;
            }
        } else {
            if (ddrHint && window.ddrRange) {
                const ok = ddr != null && (window.ddrRange.min == null || ddr >= window.ddrRange.min) && (window.ddrRange.max == null || ddr <= window.ddrRange.max);
                badge(ddrHint, ok);
                ddrHint.textContent = `Target: ${window.ddrRange.min ?? '-'} – ${window.ddrRange.max ?? '-'}`;
            }
            if (dbrHint && window.dbrRange) {
                const ok = dbr != null && (window.dbrRange.min == null || dbr >= window.dbrRange.min) && (window.dbrRange.max == null || dbr <= window.dbrRange.max);
                badge(dbrHint, ok);
                dbrHint.textContent = `Target: ${window.dbrRange.min ?? '-'} – ${window.dbrRange.max ?? '-'}`;
            }
        }
    }

    function colorizeMaterialSelect() {
        const sel = document.getElementById('material_id');
        if (!sel) return;
        const opt = sel.options[sel.selectedIndex];
        sel.classList.remove('ring-2', 'ring-green-500', 'ring-red-400');
        if (opt && opt.dataset.hasStock === '1') sel.classList.add('ring-2', 'ring-green-500');
        else if (opt && opt.dataset.hasStock === '0') sel.classList.add('ring-2', 'ring-red-400');
    }

    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('jacketingForm');

        // Draw Design: set _action dan biarkan submit normal
        document.getElementById('btnDrawDesign')?.addEventListener('click', () => {
            if (window._targetCore != null && !pickState.core.source) chooseCoreCalc(window._targetCore, {
                silent: true
            });
            if (window._targetRing != null && !pickState.ring.source) chooseRingCalc(window._targetRing, {
                silent: true
            });
            document.getElementById('form_action').value = 'draw';
            commitStateHidden();
        });

        // pastikan state terkirim saat submit normal
        form.addEventListener('submit', () => {
            commitStateHidden();
        });

        // Inisialisasi awal
        computeTargetCoreRing();
        renderCalcOptions(true);
        colorizeMaterialSelect();

        document.getElementById('input_diameter')?.addEventListener('input', () => {
            calculateOutput();
            computeTargetCoreRing();
            renderCalcOptions();
        });
        document.getElementById('working_thickness')?.addEventListener('input', () => {
            calculateOutput();
            computeTargetCoreRing();
            renderCalcOptions();
        });
        document.getElementById('output_diameter')?.addEventListener('input', () => {
            loadRecommendations();
            computeTargetCoreRing();
            renderCalcOptions();
        });

        document.querySelector('select[name="material_id"]')?.addEventListener('change', () => {
            colorizeMaterialSelect();
            loadRecommendations();
            computeTargetCoreRing();
            renderCalcOptions();
        });
        document.querySelector('select[name="crosshead_type"]')?.addEventListener('change', loadRecommendations);
        document.querySelector('select[name="dies_type"]')?.addEventListener('change', () => {
            loadRecommendations();
            computeTargetCoreRing();
            renderCalcOptions();
        });

        const E = document.getElementById('output_diameter')?.value;
        if (E) {
            loadRecommendations();
            computeTargetCoreRing();
            renderCalcOptions(true);
        }
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('jacketingForm');
        const btnReport = document.getElementById('btnDownloadReport');

        btnReport?.addEventListener('click', async (e) => {
            e.preventDefault();

            // pastikan state terset
            if (window._targetCore != null && !pickState.core.source) chooseCoreCalc(window._targetCore, {
                silent: true
            });
            if (window._targetRing != null && !pickState.ring.source) chooseRingCalc(window._targetRing, {
                silent: true
            });
            // set _action=report (jangan pakai name="action")
            document.getElementById('form_action').value = 'report';
            commitStateHidden();

            const fd = new FormData(form);
            fd.set('_action', 'report');

            try {
                const postUrl = form.getAttribute('action'); // hindari bentrok dengan field bernama "action"
                const res = await fetch(postUrl, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json,application/pdf;q=0.9,*/*;q=0.5',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': form.querySelector('input[name=_token]').value
                    },
                    credentials: 'same-origin',
                    body: fd
                });

                const ct = (res.headers.get('content-type') || '').toLowerCase();

                // === balasan JSON (sesuai controller) ===
                if (ct.includes('application/json')) {
                    const data = await res.json();

                    // unduh dari signed URL
                    const fileRes = await fetch(data.download_url, {
                        credentials: 'same-origin',
                        cache: 'no-store'
                    });
                    if (!fileRes.ok) throw new Error('HTTP file ' + fileRes.status);
                    const blob = await fileRes.blob();
                    const url = URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = data.filename || 'production-report.pdf';
                    document.body.appendChild(a);
                    a.click();
                    a.remove();
                    URL.revokeObjectURL(url);

                    // popup + redirect 5 detik
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Report tersimpan',
                            html: 'Mengalihkan ke History dalam <b id="cnt">5</b> detik…',
                            timer: 5000,
                            timerProgressBar: true,
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            didOpen: () => {
                                Swal.showLoading();
                                const el = document.getElementById('cnt');
                                const iv = setInterval(() => {
                                    const left = Math.ceil((Swal.getTimerLeft() || 0) / 1000);
                                    if (el) el.textContent = left;
                                }, 200);
                                Swal._iv = iv;
                            },
                            willClose: () => clearInterval(Swal._iv)
                        });
                    }
                    setTimeout(() => {
                        window.location.assign(data.history_url);
                    }, 5000);
                    return;
                }

                // === balasan langsung PDF (fallback) ===
                if (ct.includes('application/pdf') || ct.includes('application/octet-stream')) {
                    const blob = await res.blob();
                    const url = URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    // coba ambil filename dari Content-Disposition
                    const cd = res.headers.get('content-disposition') || '';
                    const m = cd.match(/filename="?([^"]+)"?/i);
                    a.download = m ? m[1] : ('production-report-' + new Date().toISOString().slice(0, 19).replace(/[:T]/g, '') + '.pdf');
                    a.href = url;
                    document.body.appendChild(a);
                    a.click();
                    a.remove();
                    URL.revokeObjectURL(url);

                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Report terunduh',
                            text: 'Mengalihkan ke History…',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                    setTimeout(() => {
                        window.location.assign(<?php echo json_encode(route('history'), 15, 512) ?>);
                    }, 1500);
                    return;
                }

                // === balasan HTML (error/redirect) ===
                const html = await res.text();
                throw new Error('Server mengirim HTML (status ' + res.status + '). Rincian: ' + html.slice(0, 200));

            } catch (err) {
                console.error(err);
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal membuat report',
                        text: (err && err.message) ? String(err.message) : 'Coba lagi.'
                    });
                } else {
                    alert('Gagal membuat report.');
                }
            }
        });
    });
</script>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\cablejacketing\jacketing-kabel\resources\views/jacketing.blade.php ENDPATH**/ ?>