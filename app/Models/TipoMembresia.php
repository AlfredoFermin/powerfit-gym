<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoMembresia extends Model
{
    protected $table = 'tipos_membresia';
    protected $primaryKey = 'id_tipo';
    public $timestamps = false;
    protected $guarded = [];
}
