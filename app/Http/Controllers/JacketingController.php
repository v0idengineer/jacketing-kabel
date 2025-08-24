<?php

// app/Http/Controllers/JacketingController.php
namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\StockDie;

class JacketingController extends Controller
{
    public function index()
    {
        $materials   = Material::orderBy('name')->get(['id', 'name']);
        $processes   = $this->getProcesses(); // atau array statis sesuai punyamu
        $jakartaDate = now('Asia/Jakarta')->toDateString();
        $form2       = session('form2') ?? [];

        return view('jacketing', compact('materials', 'processes', 'jakartaDate', 'form2'));
    }

    // contoh helper (opsional)
    private function getProcesses(): array
    {
        return ['extrusion', 'jacketing', 'inspection']; // sesuaikan
    }
    private function recommendDies(
        float $outputDiameter,
        int $materialId,
        string $crossheadType,
        string $diesType // 'compress' | 'semi-tubing' | 'tubing'
    ): array {
        $material = Material::findOrFail($materialId);
        $ddrTarget = $material->ddr_target; // accessor di atas
        $dbrTarget = $material->dbr_target;

        // hitung diameter target otomatis
        $coreTarget = $ddrTarget ? $outputDiameter / $ddrTarget : null;
        $ringTarget = $dbrTarget ? $outputDiameter * $dbrTarget : null;

        // cari top-3 stok terdekat untuk core & ring
        $coreOptions = $coreTarget ? StockDie::query()
            ->where('crosshead_type', $crossheadType)
            ->where('dies_type', $diesType)
            ->where('position', 'core')
            ->orderByRaw('ABS(dies_diameter - ?) ASC', [$coreTarget])
            ->limit(3)->get() : collect();

        $ringOptions = $ringTarget ? StockDie::query()
            ->where('crosshead_type', $crossheadType)
            ->where('dies_type', $diesType)
            ->where('position', 'ring')
            ->orderByRaw('ABS(dies_diameter - ?) ASC', [$ringTarget])
            ->limit(3)->get() : collect();

        return [
            'targets' => [
                'ddr' => $ddrTarget,
                'dbr' => $dbrTarget,
                'core_diameter' => $coreTarget,
                'ring_diameter' => $ringTarget,
            ],
            'core_options' => $coreOptions,
            'ring_options' => $ringOptions,
        ];
    }
}
