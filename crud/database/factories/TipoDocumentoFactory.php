<?php
namespace Database\Factories;

use App\Models\TipoDocumento;
use Illuminate\Database\Eloquent\Factories\Factory;

class TipoDocumentoFactory extends Factory
{
    protected $model = TipoDocumento::class;

    public function definition()
    {
        return [
            'nombre' => $this->faker->randomElement(['CC', 'TI', 'CE', 'PAS']),
        ];
    }
}