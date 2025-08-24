<?php
// helper tampilan core/ring (stock vs calc)
$core = $h->coreDie;
$ring = $h->ringDie;

$coreText = $core
? ($core->dies_code . ' · ' . number_format((float)$core->dies_diameter, 2) . ' mm')
: ($h->core_dia_mm ? number_format((float)$h->core_dia_mm, 2).' mm ('.($h->core_dia_src ?? 'calc').')' : '-');

$ringText = $ring
? ($ring->dies_code . ' · ' . number_format((float)$ring->dies_diameter, 2) . ' mm')
: ($h->ring_dia_mm ? number_format((float)$h->ring_dia_mm, 2).' mm ('.($h->ring_dia_src ?? 'calc').')' : '-');

$fmt = fn($v,$d=2)=> $v!==null ? number_format((float)$v,$d) : '-';
?>
<!DOCTYPE html>
<html lang="en">
<title>Jacketing Kabel</title>

<head>
    <meta charset="utf-8">
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 12px;
            color: #0f172a;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 12px;
        }

        .title {
            font-size: 18px;
            font-weight: 700;
        }

        .meta {
            font-size: 11px;
            color: #475569;
        }

        .card {
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 12px;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 8px 10px;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: top;
        }

        th {
            text-align: left;
            background: #f8fafc;
            font-weight: 600;
        }

        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .right {
            text-align: right;
        }

        .muted {
            color: #64748b;
        }

        .small {
            font-size: 11px;
        }

        .mt8 {
            margin-top: 8px
        }

        .mt12 {
            margin-top: 12px
        }

        .badge {
            display: inline-block;
            padding: 2px 6px;
            border: 1px solid #cbd5e1;
            border-radius: 999px;
            font-size: 10px;
            color: #475569;
        }

        footer {
            position: fixed;
            bottom: 20px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 11px;
            color: #64748b;
        }
    </style>
</head>

<body>

    <div class="header">
        <div style="display:flex; align-items:center; gap:10px;">
            <img src="<?php echo e(public_path('images/prysmian-logo.png')); ?>" alt="Prysmian" style="height:40px;">
            <div>
                <div class="title">Production Report</div>
                <div class="meta">Generated: <?php echo e(now('Asia/Jakarta')->format('Y-m-d H:i')); ?> WIB</div>
            </div>
        </div>
        <div class="meta" style="text-align:right;">
            Machine: <b><?php echo e($h->machine); ?></b><br>
            Crosshead: <b><?php echo e($h->crosshead_type); ?></b><br>
            Dies Type: <b><?php echo e($h->dies_type); ?></b>
        </div>
    </div>

    <div class="card grid">
        <div>
            <table>
                <tr>
                    <th colspan="2">Process & Material</th>
                </tr>
                <tr>
                    <td>Process</td>
                    <td><?php echo e($h->process); ?></td>
                </tr>
                <tr>
                    <td>Material</td>
                    <td><?php echo e(optional($h->material)->name ?? '-'); ?></td>
                </tr>
                <tr>
                    <td>Production Date</td>
                    <td><?php echo e(optional($h->production_date)->format('Y-m-d')); ?></td>
                </tr>
                <tr>
                    <td>Creator</td>
                    <td><?php echo e($h->creator); ?></td>
                </tr>
                <tr>
                    <td>Order By</td>
                    <td><?php echo e($h->order_by); ?></td>
                </tr>
                <tr>
                    <td>Cable Type</td>
                    <td><?php echo e($h->cable_type); ?></td>
                </tr>
                <tr>
                    <td>SAP Number</td>
                    <td><?php echo e($h->sap_number); ?></td>
                </tr>
            </table>
        </div>
        <div>
            <table>
                <tr>
                    <th colspan="2">Dimensions</th>
                </tr>
                <tr>
                    <td>Input Ø</td>
                    <td><?php echo e($fmt($h->input_diameter)); ?> mm</td>
                </tr>
                <tr>
                    <td>Working Thickness</td>
                    <td><?php echo e($fmt($h->working_thickness)); ?> mm</td>
                </tr>
                <tr>
                    <td>Output Ø</td>
                    <td><?php echo e($fmt($h->output_diameter)); ?> mm</td>
                </tr>
                <tr>
                    <td>DDR</td>
                    <td><?php echo e($fmt($h->ddr, 3)); ?></td>
                </tr>
                <tr>
                    <td>DBR</td>
                    <td><?php echo e($fmt($h->dbr, 3)); ?></td>
                </tr>
            </table>
            <div class="small muted mt8">
                * Nilai DDR/DBR berdasarkan pilihan core/ring saat submit (stock/calc).
            </div>
        </div>
    </div>

    <div class="card">
        <table>
            <tr>
                <th style="width:25%">Core Die</th>
                <th style="width:25%">Ring Die</th>
                <th class="right">Notes</th>
            </tr>
            <tr>
                <td>
                    <?php echo $coreText; ?>

                    <?php if($h->core_die_id): ?><div class="mt8"><span class="badge">stock</span></div><?php endif; ?>
                </td>
                <td>
                    <?php echo $ringText; ?>

                    <?php if($h->ring_die_id): ?><div class="mt8"><span class="badge">stock</span></div><?php endif; ?>
                </td>
                <td class="right small muted">
                    Report ID: #<?php echo e($h->id); ?><br>
                    Created: <?php echo e(optional($h->created_at)->format('Y-m-d H:i')); ?>

                </td>
            </tr>
        </table>
    </div>

    <footer>R&D Jacketing • Auto-generated PDF</footer>
</body>

</html><?php /**PATH D:\cablejacketing\jacketing-kabel\resources\views/reports/production.blade.php ENDPATH**/ ?>