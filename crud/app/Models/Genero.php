<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Genero extends Model
{
    protected $table = 'generos';

    protected $fillable = ['nombre'];

    public function pacientes()
    {
        return $this->hasMany(Paciente::class, 'genero_id');
    }
}
