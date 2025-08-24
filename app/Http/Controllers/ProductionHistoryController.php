<?php

namespace App\Http\Controllers;

use App\Models\ProductionHistory;
use Illuminate\Http\Request;

class ProductionHistoryController extends Controller
{
    /**
     * Tampilkan daftar riwayat produksi + search & sort.
     */
    public function index(Request $request)
    {
        // q: keyword SAP
        $q = trim((string) $request->query('q', ''));

        // sort & dir: whitelist untuk keamanan
        $allowedSort = ['id', 'created_at'];
        $sort = $request->query('sort', 'created_at');
        $dir  = strtolower($request->query('dir', 'desc')) === 'asc' ? 'asc' : 'desc';
        if (!in_array($sort, $allowedSort, true)) {
            $sort = 'id';
        }

        $histories = ProductionHistory::with(['coreDie', 'ringDie', 'material'])
            ->when($q !== '', function ($qr) use ($q) {
                $qr->where('sap_number', 'like', '%' . $q . '%');
            })
            ->orderBy($sort, $dir)
            ->paginate(25)
            ->appends($request->query()); // agar query tetap terbawa saat pagination

        return view('history.index', compact('histories', 'q', 'sort', 'dir'));
    }

    /**
     * (Opsional) Form buat input manual.
     */
    public function create()
    {
        return view('history.create');
    }

    /**
     * Simpan riwayat produksi (kalau input manual dipakai).
     */
    public function store(Request $request)
    {
        $data = $this->rules($request);

        ProductionHistory::create($data);

        return redirect()
            ->route('history.index')
            ->with('ok', 'Riwayat produksi berhasil dibuat.');
    }

    /**
     * Detail satu riwayat.
     */
    public function show(ProductionHistory $productionHistory)
    {
        $productionHistory->load(['coreDie', 'ringDie', 'material']);

        return view('history.show', compact('productionHistory'));
    }

    /**
     * (Opsional) Form edit manual.
     */
    public function edit(ProductionHistory $productionHistory)
    {
        return view('history.edit', compact('productionHistory'));
    }

    /**
     * Update riwayat (kalau edit manual dipakai).
     */
    public function update(Request $request, ProductionHistory $productionHistory)
    {
        $data = $this->rules($request);

        $productionHistory->update($data);

        return redirect()
            ->route('history.index')
            ->with('ok', 'Riwayat produksi berhasil diperbarui.');
    }

    /**
     * Hapus riwayat.
     */
    public function destroy(ProductionHistory $productionHistory)
    {
        $productionHistory->delete();

        return redirect()
            ->route('history.index')
            ->with('ok', 'Riwayat produksi dihapus.');
    }

    /**
     * Aturan validasi + normalisasi payload.
     * Disatukan agar store/update konsisten.
     */
    private function rules(Request $request): array
    {
        $validated = $request->validate([
            'machine'            => 'required|string|max:10',
            'crosshead_type'     => 'required|string|max:50',
            'dies_type'          => 'required|string|max:50',
            'process'            => 'required|string|max:50',

            'core_die_id'        => 'nullable|integer',
            'ring_die_id'        => 'nullable|integer',

            'input_diameter'     => 'required|numeric',
            'working_thickness'  => 'required|numeric',
            'output_diameter'    => 'required|numeric',

            'ddr'                => 'nullable|numeric',
            'dbr'                => 'nullable|numeric',

            'creator'            => 'required|string|max:255',
            'cable_type'         => 'required|string|max:255',
            'sap_number'         => 'required|string|max:255',
            'order_by'           => 'required|string|max:255',
            'production_date'    => 'required|date',
            'material_id'        => 'required|integer',
        ]);

        // Normalize angka ke float (kalau datang string)
        foreach (['input_diameter', 'working_thickness', 'output_diameter', 'ddr', 'dbr'] as $k) {
            if (array_key_exists($k, $validated) && $validated[$k] !== null) {
                $validated[$k] = (float) $validated[$k];
            }
        }

        return $validated;
    }
}
