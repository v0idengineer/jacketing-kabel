<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockDie extends Model
{
    use HasFactory;

    protected $table = 'stock_dies';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int'; // BIGINT UNSIGNED ok

    protected $fillable = [
        'dies_code',
        'dies_diameter',
        'dies_type',
        'position',
        'crosshead_type',
        'material_id',
        'material_dies',
        'ordered_by',
        'supplier',
        'arrival_date',
        'condition',
        'checked_by',
        'checked_date',
    ];

    protected $casts = [
        'dies_diameter' => 'decimal:2',
        'arrival_date'  => 'date',
        'checked_date'  => 'date',
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
    ];

    public function material()
    {
        return $this->belongsTo(Material::class, 'material_id');
    }

    /** ===== Scopes bantu filter (opsional) ===== */
    public function scopeCore($q)
    {
        return $q->where('position', 'core');
    }

    public function scopeRing($q)
    {
        return $q->where('position', 'ring');
    }

    public function scopeCrosshead($q, string $t)
    {
        return $q->where('crosshead_type', $t);
    }

    public function scopeDiesType($q, string $t)
    {
        return $q->where('dies_type', $t);
    }
}
