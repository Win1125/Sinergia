<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TiposDocumentoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tipos = ['CC', 'TI'];

        foreach ($tipos as $tipo) {
            DB::table('tipos_documento')->insert([
                'nombre' => $tipo,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
