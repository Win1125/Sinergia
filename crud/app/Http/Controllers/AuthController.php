<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    /**
     * Login y retorno de token JWT
     */
    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');

        $validator = Validator::make($credentials, [
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Buscar usuario
        $user = User::where('username', $request->username)->first();
        
        if (!$user || !$user->activo) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no válido'
            ], 401);
        }

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Credenciales incorrectas'
                ], 401);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error en autenticación',
                'error' => $e->getMessage()
            ], 500);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Obtener el usuario autenticado
     */
    public function me()
    {
        return response()->json([
            'success' => true,
            'user' => Auth::user()
        ]);
    }

    /**
     * Cerrar sesión
     */
    public function logout()
    {
        Auth::logout();
        
        return response()->json([
            'success' => true,
            'message' => 'Sesión cerrada correctamente'
        ]);
    }

    /**
     * Refrescar token
     */
    public function refresh()
    {
        return $this->respondWithToken(Auth::refresh());
    }

    /**
     * Formatear respuesta con token
     */
    protected function respondWithToken($token)
    {
        $ttl = config('jwt.ttl', 60);

        return response()->json([
            'success' => true,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $ttl * 60,
            'user' => Auth::user()
        ]);
    }
}
