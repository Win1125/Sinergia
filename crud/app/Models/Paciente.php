<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    protected $fillable = [
        'tipo_documento_id', 'numero_documento', 'nombre1', 'nombre2',
        'apellido1', 'apellido2', 'genero_id', 'departamento_id',
        'municipio_id', 'correo'
    ];

    protected $casts = [
        'tipo_documento_id' => 'integer',
        'genero_id' => 'integer',
        'departamento_id' => 'integer',
        'municipio_id' => 'integer',
    ];

    // Relaciones
    public function tipoDocumento()
    {
        return $this->belongsTo(TipoDocumento::class);
    }

    public function genero()
    {
        return $this->belongsTo(Genero::class);
    }

    public function departamento()
    {
        return $this->belongsTo(Departamento::class);
    }

    public function municipio()
    {
        return $this->belongsTo(Municipio::class);
    }

    public function getNombreCompletoAttribute()
    {
        return trim($this->nombre1 . ' ' . $this->nombre2 . ' ' . $this->apellido1 . ' ' . $this->apellido2);
    }
}
