<?php

namespace Database\Seeders;

use App\Models\penjualan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PenjualanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        penjualan::factory()->count(20)->create();
    }
}
