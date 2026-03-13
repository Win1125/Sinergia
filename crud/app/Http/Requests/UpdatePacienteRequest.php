<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePacienteRequest extends FormRequest
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
        $pacienteId = $this->route('paciente');

        return [
            'tipo_documento_id' => 'sometimes|exists:tipos_documento,id',
            'numero_documento' => [
                'sometimes',
                'string',
                'max:20',
                Rule::unique('pacientes')->ignore($pacienteId)
            ],
            'nombre1' => 'sometimes|string|max:50',
            'nombre2' => 'nullable|string|max:50',
            'apellido1' => 'sometimes|string|max:50',
            'apellido2' => 'nullable|string|max:50',
            'genero_id' => 'sometimes|exists:generos,id',
            'departamento_id' => 'sometimes|exists:departamentos,id',
            'municipio_id' => 'sometimes|exists:municipios,id',
            'correo' => [
                'sometimes',
                'email',
                'max:100',
                Rule::unique('pacientes')->ignore($pacienteId)
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'numero_documento.unique' => 'Este número de documento ya está registrado',
            'correo.unique' => 'Este correo ya está registrado',
            'correo.email' => 'Ingrese un correo válido',
        ];
    }
}
