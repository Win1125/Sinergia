<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Paciente;

class PacientesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pacientes = [
            [
                'tipo_documento_id' => 1,
                'numero_documento' => '12345678',
                'nombre1' => 'Juan',
                'nombre2' => 'Carlos',
                'apellido1' => 'Pérez',
                'apellido2' => 'Gómez',
                'genero_id' => 1,
                'departamento_id' => 1,
                'municipio_id' => 1,
                'correo' => 'juan.perez@email.com'
            ],
            [
                'tipo_documento_id' => 1,
                'numero_documento' => '87654321',
                'nombre1' => 'María',
                'nombre2' => 'José',
                'apellido1' => 'López',
                'apellido2' => 'Martínez',
                'genero_id' => 2,
                'departamento_id' => 2,
                'municipio_id' => 3,
                'correo' => 'maria.lopez@email.com'
            ],
            [
                'tipo_documento_id' => 2,
                'numero_documento' => '98765432',
                'nombre1' => 'Pedro',
                'nombre2' => null,
                'apellido1' => 'Ramírez',
                'apellido2' => 'Hernández',
                'genero_id' => 1,
                'departamento_id' => 3,
                'municipio_id' => 5,
                'correo' => 'pedro.ramirez@email.com'
            ],
            [
                'tipo_documento_id' => 1,
                'numero_documento' => '11223344',
                'nombre1' => 'Ana',
                'nombre2' => 'Sofía',
                'apellido1' => 'García',
                'apellido2' => 'Rodríguez',
                'genero_id' => 2,
                'departamento_id' => 4,
                'municipio_id' => 7,
                'correo' => 'ana.garcia@email.com'
            ],
            [
                'tipo_documento_id' => 2,
                'numero_documento' => '44332211',
                'nombre1' => 'Carlos',
                'nombre2' => 'Andrés',
                'apellido1' => 'Sánchez',
                'apellido2' => 'Díaz',
                'genero_id' => 1,
                'departamento_id' => 5,
                'municipio_id' => 9,
                'correo' => 'carlos.sanchez@email.com'
            ]
        ];

        foreach ($pacientes as $paciente) {
            Paciente::create($paciente);
        }
    }
}
