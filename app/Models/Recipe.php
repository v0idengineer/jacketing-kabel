<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    protected $table = 'recipes';

    protected $fillable = [
        'machine_id',
        'crosshead_type',
        'dies_type',
        'material_id',
        'input_diameter',
        'working_thickness',
        'output_diameter',
        'creator',
        'cable_type',
        'sap_number',
        'order_by',
        'process',
        'date',
        'selected_core_source',
        'selected_ring_source',
        'selected_core_dia',
        'selected_ring_dia',
        'core_die_id',
        'ring_die_id',
    ];

    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}
