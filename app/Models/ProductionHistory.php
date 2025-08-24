<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductionHistory extends Model
{
    protected $table = 'production_histories';

    // app/Models/ProductionHistory.php
    protected $fillable = [
        'machine',
        'crosshead_type',
        'dies_type',
        'process',
        'core_die_id',
        'core_dia_mm',
        'core_dia_src',
        'ring_die_id',
        'ring_dia_mm',
        'ring_dia_src',
        'input_diameter',
        'working_thickness',
        'output_diameter',
        'ddr',
        'dbr',
        'creator',
        'cable_type',
        'sap_number',
        'order_by',
        'production_date',
        'material_id',
        'core_dia_mm',
        'ring_dia_mm',
        'core_dia_src',
        'ring_dia_src',
    ];


    protected $casts = [
        'production_date'   => 'date',
        'input_diameter'    => 'decimal:2',
        'working_thickness' => 'decimal:2',
        'output_diameter'   => 'decimal:2',
        'ddr'               => 'decimal:3',
        'dbr'               => 'decimal:3',
        'core_dia_mm'       => 'decimal:2',
        'ring_dia_mm'       => 'decimal:2',
    ];

    /* =========================
     |          RELASI
     |=========================*/
    public function coreDie()
    {
        // pastikan nama model & kolom sesuai tabel stock_dies
        return $this->belongsTo(\App\Models\StockDie::class, 'core_die_id');
    }

    public function ringDie()
    {
        return $this->belongsTo(\App\Models\StockDie::class, 'ring_die_id');
    }

    public function material()
    {
        return $this->belongsTo(\App\Models\Material::class, 'material_id');
    }

    /* =========================
     |          SCOPE
     |=========================*/
    public function scopeRecent($query)
    {
        return $query->orderByDesc('id');
    }
}
