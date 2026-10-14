<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Tabla `usuarios` de la base powerfit_gym (ya existente, sin migraciones).
 * Sirve tanto para miembros como para entrenadores, recepcionistas y administradores.
 */
class Usuario extends Authenticatable
{
    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';
    public $timestamps = false;
    protected $guarded = [];
    protected $hidden = ['contrasena_hash'];

    protected $casts = [
        'activo' => 'boolean',
    ];

    // La contraseña vive en la columna `contrasena_hash`, no en `password`.
    public function getAuthPassword()
    {
        return $this->contrasena_hash;
    }

    public function getAuthPasswordName()
    {
        return 'contrasena_hash';
    }

    // La tabla no tiene columna remember_token.
    public function getRememberTokenName()
    {
        return '';
    }

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'id_rol', 'id_rol');
    }

    public function getNombreCompletoAttribute(): string
    {
        return $this->nombre . ' ' . $this->apellido;
    }

    public function tieneRol(string ...$roles): bool
    {
        return in_array($this->rol?->nombre_rol, $roles, true);
    }
}
