<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\ProductionHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class MultiStepFormController extends Controller
{
    /*========================
     * STEP 0: TAMPILKAN FORM
     *========================*/
    public function show(string $machine = null)
    {
        // Ambil enum untuk dropdown dari tabel recipes
        $crossheads = $this->getEnumValues('recipes', 'crosshead_type');
        $diesTypes  = $this->getEnumValues('recipes', 'dies_type');
        $processes  = $this->getEnumValues('recipes', 'process');

        // Material + jumlah stok
        $materials = Material::withCount([
            'stockDies as stock_count',
            'stockDies as core_count' => fn($q) => $q->where('position', 'core'),
            'stockDies as ring_count' => fn($q) => $q->where('position', 'ring'),
        ])->orderBy('name')->get(['id', 'name']);

        // State dari session (kalau ada)
        $form1       = session('form1', []);
        $form2       = session('form2', []);
        $results     = session('results', []);
        $jakartaDate = now('Asia/Jakarta')->toDateString();

        return view('jacketing', compact(
            'crossheads',
            'diesTypes',
            'processes',
            'materials',
            'form1',
            'form2',
            'results',
            'jakartaDate',
            'machine'
        ));
    }

    /*=========================================
     * OPSIONAL: SUBMIT STEP 1 & STEP 2 (session)
     *=========================================*/
    public function submitStep1(Request $request)
    {
        $validated = $request->validate([
            'crosshead_type' => 'required',
            'dies_type'      => 'required',
        ]);
        session(['form1' => $validated]);
        return back()->with('step', 2);
    }

    public function submitStep2(Request $request)
    {
        $validated = $request->validate([
            'creator'           => 'required',
            'cable_type'        => 'required',
            'sap_number'        => 'required',
            'order_by'          => 'required',
            'process'           => 'required',
            'date'              => 'required|date',
            'input_diameter'    => 'required|numeric',
            'working_thickness' => 'required|numeric',
        ]);

        $validated['output_diameter'] = $validated['input_diameter'] + (2 * $validated['working_thickness']);
        session(['form2' => $validated]);

        $form1   = session('form1', []);
        $results = $this->calculateDiesAndRatios($form1, $validated);
        session(['results' => $results]);

        return back()->with('step', 3);
    }

    /*========================
     * UTIL: Ambil ENUM values
     *========================*/
    private function getEnumValues(string $table, string $column): array
    {
        $columns = DB::select("SHOW COLUMNS FROM `{$table}`");
        $type = null;
        foreach ($columns as $col) {
            if ($col->Field === $column) {
                $type = $col->Type;
                break;
            }
        }
        if (!$type) return [];
        preg_match("/^enum\((.*)\)$/", $type, $matches);
        if (!isset($matches[1])) return [];
        return array_map(fn($v) => trim($v, "'"), explode(',', $matches[1]));
    }

    /*==================================================
     * UTIL: Kalkulasi rekomendasi (dipakai oleh Step 2)
     *==================================================*/
    private function calculateDiesAndRatios(array $form1, array $form2): array
    {
        $crosshead       = $form1['crosshead_type'] ?? null;
        $diesType        = $form1['dies_type'] ?? null;
        $inputDiameter   = (float)($form2['input_diameter'] ?? 0);
        $outputDiameter  = (float)($form2['output_diameter'] ?? 0);

        $targetCore = $inputDiameter;
        $targetRing = $outputDiameter;

        $coreDie = $this->nearestStockDie('core', $targetCore, $crosshead, $diesType);
        $ringDie = $this->nearestStockDie('ring', $targetRing, $crosshead, $diesType);

        $ddrBase = $coreDie['diameter'] ?? $targetCore ?: 1;
        $drbBase = $ringDie['diameter'] ?? $targetRing ?: 1;

        $DDR = $ddrBase > 0 ? round($outputDiameter / $ddrBase, 4) : null;
        $DRB = $drbBase > 0 ? round($outputDiameter / $drbBase, 4) : null;

        return [
            'core_die' => $coreDie ?: null,
            'ring_die' => $ringDie ?: null,
            'DDR'      => $DDR,
            'DRB'      => $DRB,
            'targets'  => [
                'target_core' => $targetCore,
                'target_ring' => $targetRing,
            ],
        ];
    }

    /*==========================================
     * UTIL: Ambil stok die terdekat (pakai DB)
     *==========================================*/
    private function nearestStockDie(string $position, float $targetDiameter, ?string $crossheadType, ?string $diesType): ?array
    {
        if ($targetDiameter <= 0) return null;

        $q = DB::table('stock_dies')
            ->select([
                'id',
                'dies_diameter',
                'material',
                'position',
                'crosshead_type',
                'dies_type',
                'condition',
                'ordered_by',
                'supplier',
                'arrival_date',
                'checked_by',
                'checked_date',
            ])
            ->where('position', $position);

        if ($crossheadType) $q->where('crosshead_type', $crossheadType);
        if ($diesType)      $q->where('dies_type', $diesType);

        $die = $q->orderByRaw('ABS(dies_diameter - ?) ASC', [$targetDiameter])
            ->orderBy('dies_diameter', 'asc')
            ->first();

        if (!$die) return null;

        return [
            'id'             => $die->id,
            'diameter'       => (float) $die->dies_diameter,
            'material'       => $die->material ?? null,
            'position'       => $die->position,
            'crosshead_type' => $die->crosshead_type ?? null,
            'dies_type'      => $die->dies_type ?? null,
            'condition'      => $die->condition ?? null,
            'ordered_by'     => $die->ordered_by ?? null,
            'supplier'       => $die->supplier ?? null,
            'arrival_date'   => $die->arrival_date ?? null,
            'checked_by'     => $die->checked_by ?? null,
            'checked_date'   => $die->checked_date ?? null,
        ];
    }

    /*========================
     * STEP 3: SIMPAN DATA
     *========================*/
    public function store(Request $request)
    {
        // penting: baca _action dulu, fallback ke action
        $action  = $request->input('_action', $request->input('action'));
        $machine = strtoupper($request->route('machine') ?? $request->input('machine') ?? 'JC2');

        /**
         * === MODE POPUP HISTORY ===
         * Jika datang dari popup History: _action=report + history_id
         * → Ambil data ProductionHistory yang sudah ada → generate PDF → balas JSON (download_url)
         */
        if ($action === 'report' && $request->filled('history_id')) {
            $historyId = (int) $request->input('history_id');
            $history = ProductionHistory::with(['coreDie', 'ringDie', 'material'])->findOrFail($historyId);

            $pdf = Pdf::loadView('reports.production', ['h' => $history])
                ->setPaper('a4', 'portrait');

            $filename = 'production-report-' . now('Asia/Jakarta')->format('Ymd-His') . '.pdf';

            // simpan sementara di storage
            $tmpPath = 'reports/tmp/' . Str::uuid() . '.pdf';
            Storage::put($tmpPath, $pdf->output());

            // signed URL (5 menit)
            $signed = URL::temporarySignedRoute(
                'reports.download',
                now()->addMinutes(5),
                ['p' => Crypt::encryptString($tmpPath), 'n' => $filename]
            );

            return response()->json([
                'ok'           => true,
                'download_url' => $signed,
                'filename'     => $filename,
                'history_url'  => route('history'),
            ]);
        }

        // ===== VALIDASI NORMAL (alur Card 3 biasa) =====
        $v = $request->validate([
            'crosshead_type'       => 'required|string|max:50',
            'dies_type'            => 'required|string|max:50',
            'process'              => 'required|string|max:50',

            'core_die_id'          => 'nullable|integer',
            'ring_die_id'          => 'nullable|integer',

            'input_diameter'       => 'required|numeric',
            'working_thickness'    => 'required|numeric',
            'output_diameter'      => 'required|numeric',
            'ddr'                  => 'nullable|numeric',
            'dbr'                  => 'nullable|numeric',

            'creator'              => 'required|string|max:255',
            'cable_type'           => 'required|string|max:255',
            'sap_number'           => 'required|string|max:255',
            'order_by'             => 'required|string|max:255',
            'date'                 => 'required|date',
            'material_id'          => 'required|integer',

            'selected_core_source' => 'nullable|in:calc,stock',
            'selected_ring_source' => 'nullable|in:calc,stock',
            'selected_core_dia'    => 'nullable|numeric',
            'selected_ring_dia'    => 'nullable|numeric',
        ]);

        $E = (float) $v['output_diameter'];

        // ===== CORE =====
        $coreDiaMm = null;
        $coreSrc   = null;
        $coreDieId = $v['core_die_id'] ?? null;

        if (($v['selected_core_source'] ?? null) === 'calc' && $request->filled('selected_core_dia')) {
            $coreDiaMm = round((float)$request->input('selected_core_dia'), 2);
            $coreSrc   = 'calc';
            $coreDieId = null;
        } elseif (!empty($coreDieId)) {
            $mm = DB::table('stock_dies')->where('id', $coreDieId)->value('dies_diameter');
            if ($mm !== null) {
                $coreDiaMm = round((float)$mm, 2);
                $coreSrc = 'stock';
            }
        } elseif ($request->filled('ddr') && $E > 0) {
            $coreDiaMm = round(((float)$request->ddr) * $E, 2);
            $coreSrc   = 'calc';
        }

        // ===== RING =====
        $ringDiaMm = null;
        $ringSrc   = null;
        $ringDieId = $v['ring_die_id'] ?? null;

        if (($v['selected_ring_source'] ?? null) === 'calc' && $request->filled('selected_ring_dia')) {
            $ringDiaMm = round((float)$request->input('selected_ring_dia'), 2);
            $ringSrc   = 'calc';
            $ringDieId = null;
        } elseif (!empty($ringDieId)) {
            $mm = DB::table('stock_dies')->where('id', $ringDieId)->value('dies_diameter');
            if ($mm !== null) {
                $ringDiaMm = round((float)$mm, 2);
                $ringSrc = 'stock';
            }
        } elseif ($request->filled('dbr') && $E > 0) {
            $ringDiaMm = round(((float)$request->dbr) * $E, 2);
            $ringSrc   = 'calc';
        }

        // ===== SIMPAN SEKALI =====
        $history = ProductionHistory::create([
            'machine'            => $machine,
            'crosshead_type'     => $v['crosshead_type'],
            'dies_type'          => $v['dies_type'],
            'process'            => $v['process'],

            'core_die_id'        => $coreDieId,
            'core_dia_mm'        => $coreDiaMm,
            'core_dia_src'       => $coreSrc,

            'ring_die_id'        => $ringDieId,
            'ring_dia_mm'        => $ringDiaMm,
            'ring_dia_src'       => $ringSrc,

            'input_diameter'     => $v['input_diameter'],
            'working_thickness'  => $v['working_thickness'],
            'output_diameter'    => $v['output_diameter'],
            'ddr'                => $request->input('ddr'),
            'dbr'                => $request->input('dbr'),

            'creator'            => $v['creator'],
            'cable_type'         => $v['cable_type'],
            'sap_number'         => $v['sap_number'],
            'order_by'           => $v['order_by'],
            'production_date'    => $v['date'],
            'material_id'        => $v['material_id'],
        ]);

        // === Jika tombol "Download Report" dari Card 3 ===
        if ($action === 'report') {
            $pdf = Pdf::loadView('reports.production', ['h' => $history])
                ->setPaper('a4', 'portrait');

            $filename = 'production-report-' . now('Asia/Jakarta')->format('Ymd-His') . '.pdf';

            // simpan sementara di storage
            $tmpPath = 'reports/tmp/' . Str::uuid() . '.pdf';
            Storage::put($tmpPath, $pdf->output());

            // signed URL (5 menit)
            $signed = URL::temporarySignedRoute(
                'reports.download',
                now()->addMinutes(5),
                ['p' => Crypt::encryptString($tmpPath), 'n' => $filename]
            );

            return response()->json([
                'ok'           => true,
                'download_url' => $signed,
                'filename'     => $filename,
                'history_url'  => route('history'),
            ]);
        }

        // default
        return redirect()->route('history')->with('ok', 'Data tersimpan.');
    }

    public function downloadReport(Request $request)
    {
        abort_if(!$request->hasValidSignature(), 403);
        $path = Crypt::decryptString($request->query('p'));
        $name = $request->query('n', basename($path));

        abort_if(!Storage::exists($path), 404);

        // (opsional) hapus file setelah diunduh: bisa pakai queue/cron bersih-bersih
        return Storage::download($path, $name);
    }
}
