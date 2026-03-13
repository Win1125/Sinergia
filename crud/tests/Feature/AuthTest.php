<?php
namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Crear usuario de prueba
        $this->user = User::factory()->create([
            'username' => 'testuser',
            'password' => bcrypt('password123'),
            'activo' => true
        ]);
    }

    /** @test */
    public function un_usuario_puede_iniciar_sesion_con_credenciales_correctas()
    {
        $response = $this->postJson('/api/login', [
            'username' => 'testuser',
            'password' => 'password123'
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'access_token',
                     'token_type',
                     'expires_in',
                     'user' => [
                         'id', 'name', 'username', 'email'
                     ]
                 ]);
    }

    /** @test */
    public function un_usuario_no_puede_iniciar_sesion_con_credenciales_incorrectas()
    {
        $response = $this->postJson('/api/login', [
            'username' => 'testuser',
            'password' => 'wrongpassword'
        ]);

        $response->assertStatus(401)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Credenciales incorrectas'
                 ]);
    }
    
    /** @test */
    public function un_usuario_puede_obtener_sus_datos_con_token_valido()
    {
        // Primero hacer login
        $loginResponse = $this->postJson('/api/login', [
            'username' => 'testuser',
            'password' => 'password123'
        ]);

        $token = $loginResponse->json('access_token');

        // Obtener datos del usuario
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->getJson('/api/me');

        $response->assertStatus(200)
                 ->assertJsonPath('user.username', 'testuser');
    }

    /** @test */
    public function un_usuario_no_puede_acceder_sin_token()
    {
        $response = $this->getJson('/api/me');

        $response->assertStatus(401);
    }

    /** @test */
    public function un_usuario_puede_cerrar_sesion()
    {
        // Login
        $loginResponse = $this->postJson('/api/login', [
            'username' => 'testuser',
            'password' => 'password123'
        ]);

        $token = $loginResponse->json('access_token');

        // Logout
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->postJson('/api/logout');

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Sesión cerrada correctamente'
                 ]);

        // Verificar que el token ya no funciona
        $meResponse = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->getJson('/api/me');

        $meResponse->assertStatus(401);
    }
}