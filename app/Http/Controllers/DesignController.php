<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use setasign\Fpdi\Fpdi;
use App\Models\Recipe;
use App\Models\StockDie;

class DesignController extends Controller
{
    /* ===================== CETAK DARI DATA SEBENARNYA ===================== */
    public function print(Recipe $recipe, Request $req)
    {
        $category  = strtolower($recipe->dies_type ?? 'compress');            // compress|semi|tubing
        $variant   = $category === 'compress'
            ? (((float)($recipe->output_diameter ?? 0) < 15.0) ? '<15' : '>15')
            : null;
        $crosshead = $recipe->crosshead_type ?? '60/70';

        [$templatePath, $pages] = $this->resolveTemplate($category, $variant);
        $specIds = $this->resolveSpecs($category, $variant);

        if (!file_exists($templatePath)) {
            return response("Template NOT FOUND: {$templatePath}", 500);
        }

        $pdf = new Fpdi();
        $pdf->SetAutoPageBreak(false);
        $pageCount = $pdf->setSourceFile($templatePath);
        if (max($pages) > $pageCount) {
            return response("Template only has {$pageCount} pages, requested " . max($pages), 500);
        }

        // Pilihan user (calc/stock)
        $pick = [
            'core' => [
                'source'   => $recipe->selected_core_source ?? $recipe->core_source ?? 'calc',
                'dia'      => (float)($recipe->selected_core_dia ?? $recipe->core_dia ?? 0),
                'stock_id' => $recipe->core_die_id ?? null,
            ],
            'ring' => [
                'source'   => $recipe->selected_ring_source ?? $recipe->ring_source ?? 'calc',
                'dia'      => (float)($recipe->selected_ring_dia ?? $recipe->ring_dia ?? 0),
                'stock_id' => $recipe->ring_die_id ?? null,
            ],
        ];

        // Nama material (opsional)
        $materialName = '';
        try {
            if (method_exists($recipe, 'material')) {
                $material = $recipe->relationLoaded('material') ? $recipe->material : $recipe->material()->first();
                $materialName = $material?->name ?? '';
            }
        } catch (\Throwable $e) {
            $materialName = $recipe->material_name ?? '';
        }

        foreach ([0, 1] as $idx) {
            $role   = $idx === 0 ? 'core' : 'ring';
            $pageNo = $pages[$idx];
            $spec   = $this->loadSpec($specIds[$idx]); // koordinat tabel kiri

            // Offset & padding (bisa override via JSON spec)
            $defaultNudge   = ['dx' => -2.0, 'dy' => 8.0];
            $defaultMaskPad = ['left' => 4.0, 'right' => 0.4, 'top' => 0.4, 'bottom' => 1.5];
            $nudge   = (array) data_get($spec, 'nudge', $defaultNudge);
            $maskPad = (array) data_get($spec, 'mask_pad', $defaultMaskPad);

            // Halaman baru + template
            $pdf->AddPage('L', [$spec['page_size']['w_mm'], $spec['page_size']['h_mm']]);
            $tplId = $pdf->importPage($pageNo);
            $pdf->useTemplate($tplId, 0, 0, $spec['page_size']['w_mm']);

            // MASK tabel kiri SAJA
            $pageW = (float) $spec['page_size']['w_mm'];
            $pageH = (float) $spec['page_size']['h_mm'];
            $m  = $this->inflateMask($spec['mask_rect'] ?? [], $maskPad);
            [$mx, $my] = $this->applyNudge($m['x'], $m['y'], $nudge);
            $mx = max(0, $mx);
            $my = max(0, $my);
            $mw = min($m['w'], $pageW - $mx);
            $mh = min($m['h'], $pageH - $my);

            $safeBottom = (float) data_get($spec, 'safe_bottom_margin_mm', 11.0); // jaga angka koordinat bawah
            $maxBottomY = $pageH - $safeBottom;
            if ($my + $mh > $maxBottomY) $mh = max(0.0, $maxBottomY - $my);

            $pdf->SetFillColor(255, 255, 255);
            if ($mw > 0 && $mh > 0) $pdf->Rect($mx, $my, $mw, $mh, 'F');

            // Gambar GRID + isi baris dari data asli
            $this->drawTableGrid($pdf, $spec + ['nudge' => $nudge]);
            $rows = $this->makeRowsFromRecipe($role, $spec, $recipe, $pick[$role], $materialName, $category);
            $this->renderTableRows($pdf, $spec + ['nudge' => $nudge], $rows);
        }

        $name = 'Design_' . $category . '_' . Str::of($crosshead)->replace('/', '-') . '_recipe-' . $recipe->id . '.pdf';
        $abs  = storage_path('app/designs/' . $name);
        if (!is_dir(dirname($abs))) mkdir(dirname($abs), 0775, true);
        $pdf->Output('F', $abs);
        return response()->download($abs)->deleteFileAfterSend(true);
    }

    /* ===================== DEMO ===================== */
    public function renderDemo(Request $req)
    {
        $category  = 'compress';
        $variant   = '<15';
        $crosshead = '60/70';

        [$templatePath, $pages] = $this->resolveTemplate($category, $variant);
        $specIds = $this->resolveSpecs($category, $variant);

        if (!file_exists($templatePath)) {
            return response("Template NOT FOUND: {$templatePath}", 500);
        }

        $pdf = new Fpdi();
        $pdf->SetAutoPageBreak(false);
        $pageCount = $pdf->setSourceFile($templatePath);
        if (max($pages) > $pageCount) {
            return response("Template only has {$pageCount} pages, requested " . max($pages), 500);
        }

        foreach ([0, 1] as $idx) {
            $pageNo = $pages[$idx];
            $spec   = $this->loadSpec($specIds[$idx]);

            $defaultNudge   = ['dx' => -2.0, 'dy' => 8.0];
            $defaultMaskPad = ['left' => 4.0, 'right' => 0.4, 'top' => 0.4, 'bottom' => 1.5];
            $nudge   = (array) data_get($spec, 'nudge', $defaultNudge);
            $maskPad = (array) data_get($spec, 'mask_pad', $defaultMaskPad);

            $pdf->AddPage('L', [$spec['page_size']['w_mm'], $spec['page_size']['h_mm']]);
            $tplId = $pdf->importPage($pageNo);
            $pdf->useTemplate($tplId, 0, 0, $spec['page_size']['w_mm']);

            $pageW = (float) $spec['page_size']['w_mm'];
            $pageH = (float) $spec['page_size']['h_mm'];

            $m = $this->inflateMask($spec['mask_rect'] ?? [], $maskPad);
            [$mx, $my] = $this->applyNudge($m['x'], $m['y'], $nudge);
            $mx = max(0, $mx);
            $my = max(0, $my);
            $mw = min($m['w'], $pageW - $mx);
            $mh = min($m['h'], $pageH - $my);

            $safeBottom = (float) data_get($spec, 'safe_bottom_margin_mm', 11.0);
            $maxBottomY = $pageH - $safeBottom;
            if ($my + $mh > $maxBottomY) $mh = max(0.0, $maxBottomY - $my);

            $pdf->SetFillColor(255, 255, 255);
            if ($mw > 0 && $mh > 0) $pdf->Rect($mx, $my, $mw, $mh, 'F');

            $this->drawTableGrid($pdf, $spec + ['nudge' => $nudge]);
            $rowsData = $this->makeDemoRowsFor($idx === 0 ? 'core' : 'ring');
            $this->renderTableRows($pdf, $spec + ['nudge' => $nudge], $rowsData);
        }

        $outName = 'Design_' . $category . '_' . Str::of($crosshead)->replace('/', '-') . '_demo.pdf';
        $outAbs  = storage_path('app/designs/' . $outName);
        if (!is_dir(dirname($outAbs))) mkdir(dirname($outAbs), 0775, true);
        $pdf->Output('F', $outAbs);
        return response()->download($outAbs)->deleteFileAfterSend(true);
    }

    /* ===================== SPEC & TEMPLATE ===================== */

    private function resolveTemplate(string $category, ?string $variant): array
    {
        $base = storage_path('app/templates');
        if ($category === 'compress') {
            $file  = $base . '/Head 60-70 Compress.pdf';
            $pages = $variant === '>15' ? [3, 4] : [1, 2]; // 1:Core<15, 2:Ring<15, 3:Core>15, 4:Ring>15
            return [$file, $pages];
        }
        if ($category === 'semi')   return [$base . '/Head 60-70 Semi-Tubing.pdf', [1, 2]];
        if ($category === 'tubing') return [$base . '/Head 60-70 Tubing.pdf', [1, 2]];
        abort(400, 'Unknown category');
    }

    private function resolveSpecs(string $category, ?string $variant): array
    {
        $dir = 'specs/';
        return match ($category) {
            'compress' => $variant === '>15'
                ? [$dir . 'compress_core_gt15.json', $dir . 'compress_ring_gt15.json']
                : [$dir . 'compress_core_lt15.json', $dir . 'compress_ring_lt15.json'],
            'semi'     => [$dir . 'semi_core.json',   $dir . 'semi_ring.json'],
            'tubing'   => [$dir . 'tubing_core.json', $dir . 'tubing_ring.json'],
            default    => abort(400, 'Unknown category'),
        };
    }

    private function loadSpec(string $path): array
    {
        $abs = storage_path('app/' . ltrim($path, '/'));
        if (!file_exists($abs)) {
            return response("Spec NOT FOUND at: {$abs}", 500)->throwResponse();
        }
        $json = file_get_contents($abs);
        $data = json_decode($json, true);
        if (json_last_error() !== JSON_ERROR_NONE || !is_array($data)) {
            return response("Spec JSON INVALID: {$abs} — " . json_last_error_msg(), 500)->throwResponse();
        }
        return $data;
    }

    /* ===================== MASK & POSITION ===================== */

    private function inflateMask(array $m, array $pad = []): array
    {
        $x = (float) ($m['x'] ?? 0);
        $y = (float) ($m['y'] ?? 0);
        $w = (float) ($m['w'] ?? 0);
        $h = (float) ($m['h'] ?? 0);

        $pl = (float) ($pad['left']   ?? ($pad['all'] ?? 1.5));
        $pr = (float) ($pad['right']  ?? ($pad['all'] ?? 1.5));
        $pt = (float) ($pad['top']    ?? ($pad['all'] ?? 1.5));
        $pb = (float) ($pad['bottom'] ?? ($pad['all'] ?? 1.5));

        return [
            'x' => $x - $pl,
            'y' => $y - $pt,
            'w' => $w + $pl + $pr,
            'h' => $h + $pt + $pb,
        ];
    }

    private function applyNudge(float $x, float $y, array $nudge = []): array
    {
        $dx = (float) ($nudge['dx'] ?? 0);
        $dy = (float) ($nudge['dy'] ?? 0);
        return [$x + $dx, $y + $dy];
    }

    /* ===================== TABLE RENDERING ===================== */

    private function drawTableGrid(Fpdi $pdf, array $spec): void
    {
        $cols = data_get($spec, 'table.columns', []);
        $rows = data_get($spec, 'table.rows', []);
        if (!$cols || !$rows) return;

        $pageH      = (float) data_get($spec, 'page_size.h_mm', 297);
        $safeBottom = (float) data_get($spec, 'safe_bottom_margin_mm', 11.0);
        $maxBottomY = $pageH - $safeBottom;

        $nud = (array) data_get($spec, 'nudge', []);
        $dx  = (float) ($nud['dx'] ?? 0);
        $dy  = (float) ($nud['dy'] ?? 0);

        $startY = (float) $rows['start_y'] + $dy;
        $rowH   = (float) $rows['row_h'];
        $count  = (int)   $rows['count'];

        // Header
        if (data_get($spec, 'table.draw_header', true)) {
            if ($startY + $rowH <= $maxBottomY) {
                $pdf->SetFont(data_get($spec, 'table.font.name', 'Arial'), 'B', (float) data_get($spec, 'table.font.size_header', 8));
                foreach ($cols as $c) {
                    $x = (float) $c['x'] + $dx;
                    $w = (float) $c['w'];
                    $pdf->SetXY($x, $startY);
                    $pdf->Cell($w, $rowH, (string)($c['label'] ?? ''), 1, 0, $c['align'] ?? 'C');
                }
                $startY += $rowH;
            } else {
                return; // ruang tidak cukup
            }
        }

        // Hitung kapasitas aman
        $room    = max(0.0, $maxBottomY - $startY);
        $slots   = (int) floor($room / max(0.1, $rowH));
        $visible = max(0, min($count, $slots));
        if ($visible <= 0) return;

        // Grid body
        $pdf->SetFont(data_get($spec, 'table.font.name', 'Arial'), '', (float) data_get($spec, 'table.font.size_body', 8));
        for ($r = 0; $r < $visible; $r++) {
            foreach ($cols as $c) {
                $x = (float) $c['x'] + $dx;
                $w = (float) $c['w'];
                $pdf->SetXY($x, $startY + $r * $rowH);
                $pdf->Cell($w, $rowH, '', 1, 0, $c['align'] ?? 'L');
            }
        }
    }

    private function renderTableRows(Fpdi $pdf, array $spec, array $rowsData): void
    {
        $cols = data_get($spec, 'table.columns', []);
        $rows = data_get($spec, 'table.rows', []);
        if (!$cols || !$rows || !$rowsData) return;

        $pageH      = (float) data_get($spec, 'page_size.h_mm', 297);
        $safeBottom = (float) data_get($spec, 'safe_bottom_margin_mm', 11.0);
        $maxBottomY = $pageH - $safeBottom;

        $nud = (array) data_get($spec, 'nudge', []);
        $dx  = (float) ($nud['dx'] ?? 0);
        $dy  = (float) ($nud['dy'] ?? 0);

        $startY = (float) $rows['start_y'] + $dy;
        $rowH   = (float) $rows['row_h'];
        $count  = (int)   $rows['count'];

        if (data_get($spec, 'table.draw_header', true)) {
            $startY += $rowH;
        }

        $room    = max(0.0, $maxBottomY - $startY);
        $slots   = (int) floor($room / max(0.1, $rowH));
        $visible = max(0, min($count, $slots, count($rowsData)));
        if ($visible <= 0) return;

        $pdf->SetFont(data_get($spec, 'table.font.name', 'Arial'), '', (float) data_get($spec, 'table.font.size_body', 8));
        $pdf->SetTextColor(0, 0, 0);

        for ($i = 0; $i < $visible; $i++) {
            $row = $rowsData[$i];
            foreach ($cols as $c) {
                $key = $c['key'];
                $x = (float) $c['x'] + $dx;
                $w = (float) $c['w'];
                $val = isset($row[$key]) ? (string) $row[$key] : '';
                $pdf->SetXY($x + 1.0, $startY + $i * $rowH + 0.8);
                $pdf->Cell($w - 2.0, $rowH - 1.6, $val, 0, 0, $c['align'] ?? 'L');
            }
        }
    }

    /* ===================== DATA MAPPING ===================== */

    private function makeRowsFromRecipe(string $role, array $spec, Recipe $recipe, array $pick, string $materialName, string $category): array
    {
        $cols = collect(data_get($spec, 'table.columns', []))->pluck('key')->all();

        $row = function (array $data) use ($cols) {
            $r = [];
            foreach ($cols as $k) $r[$k] = isset($data[$k]) ? (string)$data[$k] : '';
            return $r;
        };

        $source  = ($pick['source'] ?? 'calc') === 'stock' ? 'stock' : 'calc';
        $diaSel  = (float) ($pick['dia'] ?? 0.0);
        $outE    = (float) ($recipe->output_diameter ?? 0.0);
        $qty     = 1;

        $stock = null;
        if ($source === 'stock' && !empty($pick['stock_id'])) {
            try {
                $stock = StockDie::find($pick['stock_id']);
            } catch (\Throwable $e) {
            }
        }

        $od  = $diaSel > 0 ? number_format($diaSel, 2, '.', '') : '';
        $od1 = $outE   > 0 ? number_format($outE,   2, '.', '') : '';

        $L    = property_exists($recipe, 'length_L') ? (string)$recipe->length_L : '';
        $alfa = property_exists($recipe, 'alpha_deg') ? (string)$recipe->alpha_deg . '°' : '';

        $desc1 = ucfirst($role) . ' Die ' . ($source === 'stock' ? '(stock' . ($stock ? " #{$stock->code}" : '') . ')' : '(calc)');
        if ($stock && empty($od) && isset($stock->dies_diameter)) {
            $od = number_format((float)$stock->dies_diameter, 2, '.', '');
        }

        $rows = [];
        $rows[] = $row([
            'no'    => '1',
            'desc'  => $desc1,
            'od'    => $od,
            'od1'   => $od1,
            'l'     => $L,
            'alpha' => $alfa,
            'qty'   => (string)$qty,
        ]);

        $desc2 = trim('Material: ' . ($materialName ?: '-'));
        if (!empty($recipe->cable_type)) {
            $desc2 .= ' | Cable: ' . $recipe->cable_type;
        }
        $rows[] = $row([
            'no'   => '2',
            'desc' => $desc2,
            'qty'  => '',
        ]);

        if ($outE > 0 && $diaSel > 0) {
            $ratio = $diaSel / $outE;
            $rows[] = $row([
                'no'   => '3',
                'desc' => ($role === 'core' ? 'DDR' : 'DBR') . ' actual: ' . number_format($ratio, 3),
            ]);
        }

        if (!empty($recipe->process) && in_array('desc', $cols, true)) {
            $rows[] = $row([
                'no'   => '4',
                'desc' => 'Process: ' . str_replace('_', ' ', (string)$recipe->process),
            ]);
        }

        return $rows;
    }

    /* ===================== DEMO ROWS (opsional) ===================== */
    private function makeDemoRowsFor(string $role): array
    {
        return [
            ['no' => '1', 'desc' => ucfirst($role) . ' Die Ø calc', 'od' => '2.00', 'od1' => '3.00', 'l' => '15', 'alpha' => '24°', 'qty' => '1'],
            ['no' => '2', 'desc' => 'Material: AIR BAG',         'od' => '',     'od1' => '',    'l' => '',   'alpha' => '',    'qty' => ''],
        ];
    }
}
