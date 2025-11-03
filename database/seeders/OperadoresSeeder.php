<?php

namespace Database\Seeders;

use App\Models\Operador;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OperadoresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Operador::create([
            'nombre' => 'Jonathan',
        ]);
    }
}
