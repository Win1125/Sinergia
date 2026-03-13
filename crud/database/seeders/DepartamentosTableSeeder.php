<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartamentosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departamentos = [
            'Antioquia', 'Cundinamarca', 'Valle del Cauca', 'Atlántico', 'Santander'
        ];

        foreach ($departamentos as $depto) {
            DB::table('departamentos')->insert([
                'nombre' => $depto,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
