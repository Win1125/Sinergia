<?php
namespace Tests\Feature;

use App\Models\User;
use App\Models\Paciente;
use App\Models\TipoDocumento;
use App\Models\Genero;
use App\Models\Departamento;
use App\Models\Municipio;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PacienteFeatureTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;
    protected $token;
    protected $tipoDocumento;
    protected $genero;
    protected $departamento;
    protected $municipio;

    protected function setUp(): void
    {
        parent::setUp();

        // Crear datos necesarios
        $this->tipoDocumento = TipoDocumento::create(['nombre' => 'CC']);
        $this->genero = Genero::create(['nombre' => 'Masculino']);
        $this->departamento = Departamento::create(['nombre' => 'Antioquia']);
        $this->municipio = Municipio::create([
            'departamento_id' => $this->departamento->id,
            'nombre' => 'Medellín'
        ]);

        // Crear usuario y obtener token
        $this->user = User::factory()->create([
            'username' => 'admin',
            'password' => bcrypt('1234567890'),
            'activo' => true
        ]);

        $loginResponse = $this->postJson('/api/login', [
            'username' => 'admin',
            'password' => '1234567890'
        ]);

        $this->token = $loginResponse->json('access_token');
    }

    /** @test */
    public function puede_crear_un_paciente()
    {
        $pacienteData = [
            'tipo_documento_id' => $this->tipoDocumento->id,
            'numero_documento' => '12345678',
            'nombre1' => 'Juan',
            'nombre2' => 'Carlos',
            'apellido1' => 'Pérez',
            'apellido2' => 'Gómez',
            'genero_id' => $this->genero->id,
            'departamento_id' => $this->departamento->id,
            'municipio_id' => $this->municipio->id,
            'correo' => 'juan.perez@test.com'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->postJson('/api/pacientes', $pacienteData);

        $response->assertStatus(201)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Paciente creado exitosamente'
                 ]);

        // Verificar en base de datos
        $this->assertDatabaseHas('pacientes', [
            'numero_documento' => '12345678',
            'correo' => 'juan.perez@test.com'
        ]);
    }

    /** @test */
    public function no_puede_crear_paciente_con_documento_duplicado()
    {
        // Crear primer paciente
        Paciente::create([
            'tipo_documento_id' => $this->tipoDocumento->id,
            'numero_documento' => '12345678',
            'nombre1' => 'Juan',
            'apellido1' => 'Pérez',
            'genero_id' => $this->genero->id,
            'departamento_id' => $this->departamento->id,
            'municipio_id' => $this->municipio->id,
            'correo' => 'juan@test.com'
        ]);

        // Intentar crear otro con el mismo documento
        $pacienteData = [
            'tipo_documento_id' => $this->tipoDocumento->id,
            'numero_documento' => '12345678', // Duplicado
            'nombre1' => 'Pedro',
            'apellido1' => 'Martínez',
            'genero_id' => $this->genero->id,
            'departamento_id' => $this->departamento->id,
            'municipio_id' => $this->municipio->id,
            'correo' => 'pedro@test.com'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->postJson('/api/pacientes', $pacienteData);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['numero_documento']);
    }

    /** @test */
    public function puede_ver_un_paciente_especifico()
    {
        $paciente = Paciente::create([
            'tipo_documento_id' => $this->tipoDocumento->id,
            'numero_documento' => '87654321',
            'nombre1' => 'María',
            'apellido1' => 'López',
            'genero_id' => $this->genero->id,
            'departamento_id' => $this->departamento->id,
            'municipio_id' => $this->municipio->id,
            'correo' => 'maria@test.com'
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->getJson('/api/pacientes/' . $paciente->id);

        $response->assertStatus(200)
                 ->assertJsonPath('data.id', $paciente->id)
                 ->assertJsonPath('data.correo', 'maria@test.com');
    }

    /** @test */
    public function puede_actualizar_un_paciente()
    {
        $paciente = Paciente::create([
            'tipo_documento_id' => $this->tipoDocumento->id,
            'numero_documento' => '11223344',
            'nombre1' => 'Carlos',
            'apellido1' => 'Ramírez',
            'genero_id' => $this->genero->id,
            'departamento_id' => $this->departamento->id,
            'municipio_id' => $this->municipio->id,
            'correo' => 'carlos@test.com'
        ]);

        $updateData = [
            'nombre1' => 'Carlos Andrés',
            'nombre2' => 'Actualizado',
            'correo' => 'carlos.actualizado@test.com'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->putJson('/api/pacientes/' . $paciente->id, $updateData);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Paciente actualizado exitosamente'
                 ]);

        $this->assertDatabaseHas('pacientes', [
            'id' => $paciente->id,
            'nombre1' => 'Carlos Andrés',
            'correo' => 'carlos.actualizado@test.com'
        ]);
    }

    /** @test */
    public function puede_buscar_pacientes_por_nombre()
    {
        // Crear pacientes de prueba
        Paciente::create([
            'tipo_documento_id' => $this->tipoDocumento->id,
            'numero_documento' => '11111111',
            'nombre1' => 'Pedro',
            'apellido1' => 'Infante',
            'genero_id' => $this->genero->id,
            'departamento_id' => $this->departamento->id,
            'municipio_id' => $this->municipio->id,
            'correo' => 'pedro@test.com'
        ]);

        Paciente::create([
            'tipo_documento_id' => $this->tipoDocumento->id,
            'numero_documento' => '22222222',
            'nombre1' => 'Pablo',
            'apellido1' => 'Neruda',
            'genero_id' => $this->genero->id,
            'departamento_id' => $this->departamento->id,
            'municipio_id' => $this->municipio->id,
            'correo' => 'pablo@test.com'
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->getJson('/api/pacientes?search=pedro');

        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data'));
        $this->assertEquals('pedro@test.com', $response->json('data.0.correo'));
    }

}