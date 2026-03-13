<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePacienteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'tipo_documento_id' => 'required|exists:tipos_documento,id',
            'numero_documento' => 'required|string|max:20|unique:pacientes,numero_documento',
            'nombre1' => 'required|string|max:50',
            'nombre2' => 'nullable|string|max:50',
            'apellido1' => 'required|string|max:50',
            'apellido2' => 'nullable|string|max:50',
            'genero_id' => 'required|exists:generos,id',
            'departamento_id' => 'required|exists:departamentos,id',
            'municipio_id' => 'required|exists:municipios,id',
            'correo' => 'required|email|max:100|unique:pacientes,correo',
        ];
    }

     /**
     * Mensajes de error personalizados
     */
    public function messages(): array
    {
        return [
            'tipo_documento_id.required' => 'El tipo de documento es obligatorio',
            'tipo_documento_id.exists' => 'El tipo de documento seleccionado no existe',
            'numero_documento.required' => 'El número de documento es obligatorio',
            'numero_documento.unique' => 'Este número de documento ya está registrado',
            'nombre1.required' => 'El primer nombre es obligatorio',
            'apellido1.required' => 'El primer apellido es obligatorio',
            'genero_id.required' => 'El género es obligatorio',
            'genero_id.exists' => 'El género seleccionado no existe',
            'departamento_id.required' => 'El departamento es obligatorio',
            'departamento_id.exists' => 'El departamento seleccionado no existe',
            'municipio_id.required' => 'El municipio es obligatorio',
            'municipio_id.exists' => 'El municipio seleccionado no existe',
            'correo.required' => 'El correo es obligatorio',
            'correo.email' => 'Ingrese un correo válido',
            'correo.unique' => 'Este correo ya está registrado',
        ];
    }
}
