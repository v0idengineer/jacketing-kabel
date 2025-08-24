<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\StockDie;
use App\Models\Material;

class StockDieController extends Controller
{
    protected function getEnumValues(string $table, string $column): array
    {
        $result = DB::select("SHOW COLUMNS FROM `$table` WHERE Field = ?", [$column]);
        if (!$result) return [];
        $type = $result[0]->Type; // enum('a','b',...)
        if (!preg_match('/^enum\((.*)\)$/', $type, $m)) return [];
        return array_map(fn($v) => trim($v, "'"), explode(',', $m[1]));
    }

    /** List + filter */
    public function index(Request $request)
    {
        $crossheadTypes = $this->getEnumValues('recipes', 'crosshead_type');
        $diesTypes      = $this->getEnumValues('recipes', 'dies_type');
        $materials      = Material::orderBy('name')->get(['id', 'name']);

        $query = StockDie::with('material')
            ->orderByDesc('arrival_date')
            ->orderByDesc('id');

        if ($request->filled('crosshead_type')) {
            $query->where('crosshead_type', $request->crosshead_type);
        }
        if ($request->filled('dies_type')) {
            $query->where('dies_type', $request->dies_type);
        }
        // Opsional: filter posisi core/ring
        if ($request->filled('position')) {
            $query->where('position', $request->position);
        }

        $stockDies = $query->paginate(20)->withQueryString();

        return view('stock.index', compact('materials', 'diesTypes', 'crossheadTypes', 'stockDies'));
    }

    public function create()
    {
        $crossheadTypes = $this->getEnumValues('recipes', 'crosshead_type');
        $diesTypes      = $this->getEnumValues('recipes', 'dies_type');
        $materials      = Material::orderBy('name')->get(['id', 'name']);

        return view('stock.create', compact('materials', 'diesTypes', 'crossheadTypes'));
    }

    /** Store */
    public function store(Request $request)
    {
        $request->validate([
            'dies_code'      => ['required', 'string', 'max:50'],
            'dies_diameter'  => ['required', 'numeric', 'min:0.01'],  // wajib
            'dies_type'      => ['required', 'string', 'max:50'],
            'position'       => ['required', 'in:core,ring'],        // wajib (NOT NULL)
            'crosshead_type' => ['required', 'string', 'max:50'],
            'material_id'    => ['required', 'exists:materials,id'], // wajib
            'ordered_by'     => ['nullable', 'string', 'max:255'],
            'supplier'       => ['nullable', 'string', 'max:255'],
            'arrival_date'   => ['nullable', 'date'],
            'condition'      => ['nullable', 'string', 'max:255'],
            'checked_by'     => ['nullable', 'string', 'max:255'],
            'checked_date'   => ['nullable', 'date'],
        ]);

        // fallback (kalau form lama masih kirim 'diameter' / 'type')
        $diesDiameter = $request->input('dies_diameter', $request->input('diameter'));
        $diesType     = $request->input('dies_type', $request->input('type'));

        StockDie::create([
            'dies_code'      => $request->dies_code,
            'dies_diameter'  => $diesDiameter,
            'dies_type'      => $diesType,
            'position'       => $request->position,
            'crosshead_type' => $request->crosshead_type,
            'material_id'    => $request->material_id,
            'ordered_by'     => $request->ordered_by,
            'supplier'       => $request->supplier,
            'arrival_date'   => $request->arrival_date,
            'condition'      => $request->condition,
            'checked_by'     => $request->checked_by,
            'checked_date'   => $request->checked_date,
        ]);

        return redirect()->route('stock.index')->with('success', 'Stock die berhasil ditambahkan!');
    }

    /** Detail */
    public function show($id)
    {
        $stockDie = StockDie::with('material')->findOrFail($id);
        return view('stock.show', compact('stockDie'));
    }

    /** Edit form */
    public function edit($id)
    {
        $stockDie       = StockDie::with('material')->findOrFail($id);
        $materials      = Material::orderBy('name')->get(['id', 'name']);
        $crossheadTypes = $this->getEnumValues('recipes', 'crosshead_type');
        $diesTypes      = $this->getEnumValues('recipes', 'dies_type');

        return view('stock.edit', compact('stockDie', 'materials', 'crossheadTypes', 'diesTypes'));
    }

    /** Update */
    public function update(Request $request, $id)
    {
        $request->validate([
            'dies_code'      => ['required', 'string', 'max:50'],
            'dies_diameter'  => ['required', 'numeric', 'min:0.01'],
            'dies_type'      => ['required', 'string', 'max:50'],
            'position'       => ['required', 'in:core,ring'],
            'crosshead_type' => ['required', 'string', 'max:50'],
            'material_id'    => ['required', 'exists:materials,id'],
            'material_dies'  => ['nullable', 'string', 'max:255'],
            'ordered_by'     => ['nullable', 'string', 'max:255'],
            'supplier'       => ['nullable', 'string', 'max:255'],
            'arrival_date'   => ['nullable', 'date'],
            'condition'      => ['nullable', 'string', 'max:255'],
            'checked_by'     => ['nullable', 'string', 'max:255'],
            'checked_date'   => ['nullable', 'date'],
        ]);

        $stockDie = \App\Models\StockDie::findOrFail($id);

        // alias aman kalau nama field beda
        $diesDiameter = $request->input('dies_diameter', $request->input('diameter'));
        $diesType     = $request->input('dies_type', $request->input('type'));

        // kosong => null utk kolom tanggal
        $arrival = $request->filled('arrival_date') ? $request->input('arrival_date') : null;
        $checked = $request->filled('checked_date') ? $request->input('checked_date') : null;

        $stockDie->update([
            'dies_code'      => $request->dies_code,
            'dies_diameter'  => $diesDiameter,
            'dies_type'      => $diesType,
            'position'       => $request->position,
            'crosshead_type' => $request->crosshead_type,
            'material_id'    => $request->material_id,
            'material_dies'  => $request->material_dies,
            'ordered_by'     => $request->ordered_by,
            'supplier'       => $request->supplier,
            'arrival_date'   => $arrival,
            'condition'      => $request->condition,
            'checked_by'     => $request->checked_by,
            'checked_date'   => $checked,
        ]);

        // === PAKSA balik JSON kalau dipanggil via fetch/AJAX atau minta JSON
        if (
            $request->ajax() ||
            $request->wantsJson() ||
            strtolower($request->header('x-requested-with')) === 'xmlhttprequest'
        ) {
            return response()->json([
                'ok'         => true,
                'id'         => $stockDie->id,
                'message'    => 'Stock die berhasil diperbarui!',
                'updated_at' => optional($stockDie->updated_at)->timezone('Asia/Jakarta')->format('Y-m-d H:i:s'),
            ], 200);
        }

        // Fallback non-AJAX
        return redirect()->route('stock.index')->with('success', 'Stock die berhasil diperbarui!');
    }


    /** Delete */
    public function destroy($id)
    {
        $stockDie = StockDie::findOrFail($id);
        $stockDie->delete();
        return redirect()->route('stock.index')->with('success', 'Stock die berhasil dihapus!');
    }
}
