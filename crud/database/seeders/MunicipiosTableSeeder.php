<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MunicipiosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $municipios = [
            // Antioquia
            ['departamento_id' => 1, 'nombre' => 'Medellín'],
            ['departamento_id' => 1, 'nombre' => 'Envigado'],
            // Cundinamarca
            ['departamento_id' => 2, 'nombre' => 'Bogotá'],
            ['departamento_id' => 2, 'nombre' => 'Soacha'],
            // Valle
            ['departamento_id' => 3, 'nombre' => 'Cali'],
            ['departamento_id' => 3, 'nombre' => 'Palmira'],
            // Atlántico
            ['departamento_id' => 4, 'nombre' => 'Barranquilla'],
            ['departamento_id' => 4, 'nombre' => 'Soledad'],
            // Santander
            ['departamento_id' => 5, 'nombre' => 'Bucaramanga'],
            ['departamento_id' => 5, 'nombre' => 'Floridablanca'],
        ];

        foreach ($municipios as $municipio) {
            DB::table('municipios')->insert([
                'departamento_id' => $municipio['departamento_id'],
                'nombre' => $municipio['nombre'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
