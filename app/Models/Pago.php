<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $table = 'pagos';
    protected $primaryKey = 'id_pago';
    public $timestamps = false;
    protected $guarded = [];

    protected $casts = [
        'fecha_pago' => 'date',
        'monto' => 'decimal:2',
    ];

    public function membresia()
    {
        return $this->belongsTo(Membresia::class, 'id_membresia', 'id_membresia');
    }
}
