<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    public function getDdrTargetAttribute(): ?float
    {
        return $this->ddr_normal ?? ($this->ddr_min && $this->ddr_max
            ? round(($this->ddr_min + $this->ddr_max) / 2, 2) : null);
    }
    public function getDbrTargetAttribute(): ?float
    {
        return ($this->drb_min && $this->drb_max)
            ? round(($this->drb_min + $this->drb_max) / 2, 2) : null;
    }
    public function stockDies()
    {
        return $this->hasMany(StockDie::class, 'material_id');
    }
}
