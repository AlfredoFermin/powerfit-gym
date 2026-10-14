<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Clase extends Model
{
    protected $table = 'clases';
    protected $primaryKey = 'id_clase';
    public $timestamps = false;
    protected $guarded = [];

    public function entrenador()
    {
        return $this->belongsTo(Usuario::class, 'id_entrenador', 'id_usuario');
    }
}
