<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Http\Requests\StorePacienteRequest;
use App\Http\Requests\UpdatePacienteRequest;
use App\Http\Resources\PacienteResource;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Paciente::with(['tipoDocumento', 'genero', 'departamento', 'municipio']);

        // Búsqueda
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nombre1', 'LIKE', "%{$search}%")
                  ->orWhere('nombre2', 'LIKE', "%{$search}%")
                  ->orWhere('apellido1', 'LIKE', "%{$search}%")
                  ->orWhere('apellido2', 'LIKE', "%{$search}%")
                  ->orWhere('correo', 'LIKE', "%{$search}%")
                  ->orWhere('numero_documento', 'LIKE', "%{$search}%");
            });
        }

        // Paginación
        $perPage = $request->get('per_page', 5);
        $pacientes = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => PacienteResource::collection($pacientes),
            'pagination' => [
                'total' => $pacientes->total(),
                'per_page' => $pacientes->perPage(),
                'current_page' => $pacientes->currentPage(),
                'last_page' => $pacientes->lastPage(),
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePacienteRequest $request)
    {
        $validatedData = $request->validated();
        $paciente = Paciente::create($validatedData);
        
        return response()->json([
            'success' => true,
            'message' => 'Paciente creado exitosamente',
            'data' => new PacienteResource($paciente->load(['tipoDocumento', 'genero', 'departamento', 'municipio']))
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Paciente $paciente)
    {
        return response()->json([
            'success' => true,
            'data' => new PacienteResource($paciente->load(['tipoDocumento', 'genero', 'departamento', 'municipio']))
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePacienteRequest $request, Paciente $paciente)
    {
        $validatedData = $request->validated();
        $paciente->update($validatedData);

        
        return response()->json([
            'success' => true,
            'message' => 'Paciente actualizado exitosamente',
            'data' => new PacienteResource($paciente->fresh(['tipoDocumento', 'genero', 'departamento', 'municipio']))
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Paciente $paciente)
    {
        $paciente->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Paciente eliminado exitosamente'
        ]);
    }

    public function getFormData()
    {
        return response()->json([
            'success' => true,
            'data' => [
                'tipos_documento' => \App\Models\TipoDocumento::all(['id', 'nombre']),
                'generos' => \App\Models\Genero::all(['id', 'nombre']),
                'departamentos' => \App\Models\Departamento::with('municipios:id,departamento_id,nombre')->get(['id', 'nombre']),
            ]
        ]);
    }

}
