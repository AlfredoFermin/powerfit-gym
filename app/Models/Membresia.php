<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Membresia extends Model
{
    protected $table = 'membresias';
    protected $primaryKey = 'id_membresia';
    public $timestamps = false;
    protected $guarded = [];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    public function tipo()
    {
        return $this->belongsTo(TipoMembresia::class, 'id_tipo', 'id_tipo');
    }
}
