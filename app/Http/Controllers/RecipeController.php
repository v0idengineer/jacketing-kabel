<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Recipe;
use App\Models\StockDie;
use App\Models\ProductionHistory;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class RecipeController extends Controller
{
    public function index(): View
    {
        $recipes = Recipe::with(['material'])->latest()->paginate(20);
        return view('recipes.index', compact('recipes'));
    }

    public function create(): View
    {
        $materials   = Material::orderBy('name')->get();
        $processes   = $this->getProcesses();
        $jakartaDate = now('Asia/Jakarta')->toDateString();
        $form2       = session('form2') ?? [];

        return view('recipes.create', compact('materials', 'processes', 'jakartaDate', 'form2'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'creator'            => ['required', 'string', 'max:255'],
            'cable_type'         => ['required', 'string', 'max:255'],
            'sap_number'         => ['required', 'string', 'max:255'],
            'order_by'           => ['required', 'string', 'max:255'],
            'process'            => ['required', 'string', 'max:255'],
            'date'               => ['required', 'date'],
            'input_diameter'     => ['required', 'numeric', 'min:0.01'],
            'working_thickness'  => ['required', 'numeric', 'min:0.01'],
            'output_diameter'    => ['required', 'numeric', 'min:0.01'],
            'material_id'        => ['required', 'exists:materials,id'],
            'core_die_id'        => ['nullable', 'exists:stock_dies,id'],
            'ring_die_id'        => ['nullable', 'exists:stock_dies,id'],
            'crosshead_type'     => ['nullable', 'string', 'max:255'],
            'dies_type'          => ['nullable', 'string', 'max:255'],
        ]);

        $recipe = Recipe::create([
            'creator'           => $validated['creator'],
            'cable_type'        => $validated['cable_type'],
            'sap_number'        => $validated['sap_number'],
            'order_by'          => $validated['order_by'],
            'process'           => $validated['process'],
            'date'              => $validated['date'],
            'input_diameter'    => $validated['input_diameter'],
            'working_thickness' => $validated['working_thickness'],
            'output_diameter'   => $validated['output_diameter'],
            'material_id'       => $validated['material_id'],
            'core_die_id'       => $validated['core_die_id'] ?? null,
            'ring_die_id'       => $validated['ring_die_id'] ?? null,
        ]);

        $out     = (float) $validated['output_diameter'];
        $coreDie = !empty($validated['core_die_id']) ? StockDie::find($validated['core_die_id']) : null;
        $ringDie = !empty($validated['ring_die_id']) ? StockDie::find($validated['ring_die_id']) : null;

        // Rumus AKTUAL yang benar:
        // DDR = core_diameter / output
        // DBR = ring_diameter / output
        $ddrActual = ($coreDie && $coreDie->dies_diameter > 0) ? round($coreDie->dies_diameter / $out, 3) : null;
        $dbrActual = ($ringDie && $ringDie->dies_diameter > 0) ? round($ringDie->dies_diameter / $out, 3) : null;

        $history = ProductionHistory::create([
            'recipe_id'   => $recipe->id,
            'material_id' => $recipe->material_id,
            'core_die_id' => $coreDie->id ?? null,
            'ring_die_id' => $ringDie->id ?? null,
            'ddr'         => $ddrActual,
            'dbr'         => $dbrActual,
        ]);

        return redirect()
            ->route('jacketing.index')
            ->with('saved_recipe_id', $recipe->id)
            ->with('saved_history_id', $history->id)
            ->with('success', 'Resep & riwayat produksi berhasil disimpan.');
    }

    // === SNAPSHOT YANG JALAN: rekomendasi dies ===
    public function recommend(Request $request): JsonResponse
    {
        $request->validate([
            'material_id'    => ['required', 'exists:materials,id'],
            'output'         => ['required', 'numeric', 'min:0.01'],
            'crosshead_type' => ['nullable', 'string', 'max:255'],
            'dies_type'      => ['nullable', 'string', 'max:255'],
        ]);

        $material = Material::findOrFail($request->material_id);
        $output   = (float) $request->output;

        // Target DDR/DBR masuk akal (fallback aman bila null)
        $ddrTarget = $material->ddr_normal
            ?? (($material->ddr_min && $material->ddr_max) ? ($material->ddr_min + $material->ddr_max) / 2 : 2.00);
        $dbrTarget = ($material->drb_min && $material->drb_max)
            ? ($material->drb_min + $material->drb_max) / 2 : 1.00;

        // Diameter target
        // DDR = core/output => core_target = DDR_target * output
        // DBR = ring/output => ring_target = DBR_target * output
        $coreTarget = $output * $ddrTarget;
        $ringTarget = $output * $dbrTarget;

        $coreQ = StockDie::query()->where('position', 'core');
        $ringQ = StockDie::query()->where('position', 'ring');

        if ($request->filled('crosshead_type')) {
            $coreQ->where('crosshead_type', $request->crosshead_type);
            $ringQ->where('crosshead_type', $request->crosshead_type);
        }
        if ($request->filled('dies_type')) {
            $coreQ->where('dies_type', $request->dies_type);
            $ringQ->where('dies_type', $request->dies_type);
        }

        // jika stokmu memang sudah terhubung ke material_id, ini dibiarkan.
        // kalau belum, KOMENTARI dua baris berikut sementara.
        $coreQ->where('material_id', $request->material_id);
        $ringQ->where('material_id', $request->material_id);

        $cores = $coreQ
            ->orderByRaw('ABS(dies_diameter - ?) ASC', [$coreTarget])
            ->limit(3)
            ->get(['id', 'dies_code', 'dies_diameter']);

        $rings = $ringQ
            ->orderByRaw('ABS(dies_diameter - ?) ASC', [$ringTarget])
            ->limit(3)
            ->get(['id', 'dies_code', 'dies_diameter']);

        return response()->json([
            'targets' => [
                'ddr'           => $ddrTarget,
                'dbr'           => $dbrTarget,
                'core_diameter' => round($coreTarget, 3),
                'ring_diameter' => round($ringTarget, 3),
            ],
            'ranges' => [
                'ddr_min' => $material->ddr_min,
                'ddr_max' => $material->ddr_max,
                'dbr_min' => $material->drb_min,
                'dbr_max' => $material->drb_max,
            ],
            'core' => $cores,
            'ring' => $rings,
        ]);
    }

    private function getProcesses(): array
    {
        return ['extrusion', 'jacketing', 'inspection'];
    }
}
