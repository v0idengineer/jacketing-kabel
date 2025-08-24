<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MaterialSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('materials')->insert([
            [
                'name' => 'AIR BAG',
                'ddr_normal' => 0.40,
                'ddr_clearance' => 1.05,
                'ddr_min' => 0.40,
                'ddr_max' => 0.65,
                'drb_min' => 0.90,
                'drb_max' => 1.10,
                'note' => 'STD 1.01 - 1.05, Best 1.02 - 1.03'
            ],
            [
                'name' => 'HDPE',
                'ddr_normal' => 2.20,
                'ddr_clearance' => 1.10,
                'ddr_min' => 2.00,
                'ddr_max' => 2.40,
                'drb_min' => 1.00,
                'drb_max' => 1.08,
                'note' => null
            ],
            [
                'name' => 'LLDPE',
                'ddr_normal' => 2.20,
                'ddr_clearance' => 1.10,
                'ddr_min' => 2.00,
                'ddr_max' => 2.90,
                'drb_min' => 1.00,
                'drb_max' => 1.05,
                'note' => null
            ],
            [
                'name' => 'LSOH',
                'ddr_normal' => 2.00,
                'ddr_clearance' => 1.10,
                'ddr_min' => 1.70,
                'ddr_max' => 2.15,
                'drb_min' => 1.00,
                'drb_max' => 1.04,
                'note' => null
            ],
            [
                'name' => 'NYLON',
                'ddr_normal' => 6.50,
                'ddr_clearance' => 1.25,
                'ddr_min' => 5.50,
                'ddr_max' => 8.00,
                'drb_min' => 1.00,
                'drb_max' => 1.20,
                'note' => 'STD 1.01 - 1.05'
            ],
            [
                'name' => 'PVC Filler Bedding',
                'ddr_normal' => 0.75,
                'ddr_clearance' => 1.04,
                'ddr_min' => 0.50,
                'ddr_max' => 1.00,
                'drb_min' => 0.90,
                'drb_max' => 1.01,
                'note' => 'Best 1.02 - 1.03'
            ],
            [
                'name' => 'PVC ST2',
                'ddr_normal' => 2.00,
                'ddr_clearance' => 1.10,
                'ddr_min' => 1.80,
                'ddr_max' => 2.25,
                'drb_min' => 1.00,
                'drb_max' => 1.04,
                'note' => null
            ],
            [
                'name' => 'PVC ST2 Bedding',
                'ddr_normal' => 0.35,
                'ddr_clearance' => 1.04,
                'ddr_min' => 0.10,
                'ddr_max' => 0.50,
                'drb_min' => 0.90,
                'drb_max' => 1.10,
                'note' => null
            ],
            [
                'name' => 'PE',
                'ddr_normal' => null,
                'ddr_clearance' => null,
                'ddr_min' => null,
                'ddr_max' => null,
                'drb_min' => null,
                'drb_max' => null,
                'note' => null
            ],
            [
                'name' => 'XLPE',
                'ddr_normal' => null,
                'ddr_clearance' => null,
                'ddr_min' => null,
                'ddr_max' => null,
                'drb_min' => null,
                'drb_max' => null,
                'note' => null
            ]
        ]);
    }
}
