<?php
namespace Database\Factories;

use App\Models\Paciente;
use App\Models\TipoDocumento;
use App\Models\Genero;
use App\Models\Departamento;
use App\Models\Municipio;
use Illuminate\Database\Eloquent\Factories\Factory;

class PacienteFactory extends Factory
{
    protected $model = Paciente::class;

    public function definition()
    {
        return [
            'tipo_documento_id' => TipoDocumento::factory(),
            'numero_documento' => $this->faker->unique()->numerify('########'),
            'nombre1' => $this->faker->firstName(),
            'nombre2' => $this->faker->optional()->firstName(),
            'apellido1' => $this->faker->lastName(),
            'apellido2' => $this->faker->optional()->lastName(),
            'genero_id' => Genero::factory(),
            'departamento_id' => Departamento::factory(),
            'municipio_id' => Municipio::factory(),
            'correo' => $this->faker->unique()->safeEmail(),
        ];
    }
}