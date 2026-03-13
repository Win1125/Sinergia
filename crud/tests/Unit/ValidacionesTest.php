<?php
namespace Tests\Unit;

use App\Http\Requests\StorePacienteRequest;
use App\Http\Requests\UpdatePacienteRequest;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class ValidacionesTest extends TestCase
{
    /** @test */
    public function validacion_de_campos_obligatorios_en_store_request()
    {
        $request = new StorePacienteRequest();
        
        $rules = $request->rules();
        
        $camposObligatorios = [
            'tipo_documento_id',
            'numero_documento',
            'nombre1',
            'apellido1',
            'genero_id',
            'departamento_id',
            'municipio_id',
            'correo'
        ];

        foreach ($camposObligatorios as $campo) {
            $this->assertArrayHasKey($campo, $rules, "El campo {$campo} debería tener reglas de validación");
            
            $validator = Validator::make([$campo => null], [$campo => $rules[$campo]]);
            $this->assertTrue($validator->fails(), "El campo {$campo} debería fallar cuando está vacío");
        }
    }
}