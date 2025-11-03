<?php

namespace Database\Seeders;

use App\Models\TipoAcceso;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipoAccesoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TipoAcceso::create([
            'nombre' => 'SALIDA',
        ]);

        TipoAcceso::create([
            'nombre' => 'ENTRADA',
        ]);
    }
}
