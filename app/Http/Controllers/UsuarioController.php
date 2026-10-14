<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use App\Models\Usuario;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

/** Módulo: Usuarios (miembros, entrenadores, recepcionistas y administradores). RF-01, RF-02, RF-08, RF-12. */
class UsuarioController extends CrudController
{
    protected function modelo(): string
    {
        return Usuario::class;
    }

    protected function ruta(): string
    {
        return 'usuarios';
    }

    protected function titulo(): string
    {
        return 'Usuarios y miembros';
    }

    protected function relaciones(): array
    {
        return ['rol'];
    }

    protected function columnas(): array
    {
        return [
            ['label' => 'Nombre', 'attr' => 'nombre_completo'],
            ['label' => 'Correo', 'attr' => 'email'],
            ['label' => 'Teléfono', 'attr' => 'telefono'],
            ['label' => 'Rol', 'attr' => 'rol.nombre_rol'],
            ['label' => 'Activo', 'attr' => 'activo', 'formato' => 'bool'],
        ];
    }

    protected function campos(?Model $registro): array
    {
        return [
            ['name' => 'nombre', 'label' => 'Nombre', 'type' => 'text', 'rules' => 'required|string|max:60'],
            ['name' => 'apellido', 'label' => 'Apellido', 'type' => 'text', 'rules' => 'required|string|max:60'],
            [
                'name' => 'email', 'label' => 'Correo', 'type' => 'email',
                'rules' => ['required', 'email', 'max:120', Rule::unique('usuarios', 'email')->ignore($registro?->getKey(), 'id_usuario')],
            ],
            ['name' => 'telefono', 'label' => 'Teléfono', 'type' => 'text', 'rules' => 'nullable|string|max:20'],
            [
                'name' => 'password', 'label' => 'Contraseña', 'type' => 'password',
                'rules' => $registro ? 'nullable|string|min:6|max:72' : 'required|string|min:6|max:72',
                'ayuda' => $registro ? 'Déjala vacía para conservar la actual.' : 'Mínimo 6 caracteres.',
            ],
            [
                'name' => 'id_rol', 'label' => 'Rol', 'type' => 'select',
                'options' => Rol::orderBy('nombre_rol')->pluck('nombre_rol', 'id_rol')->all(),
                'rules' => 'required|exists:roles,id_rol',
            ],
            [
                'name' => 'activo', 'label' => 'Activo', 'type' => 'select',
                'options' => [1 => 'Sí', 0 => 'No'], 'valor' => 1,
                'rules' => 'required|in:0,1',
            ],
        ];
    }

    protected function preparar(array $datos, ?Model $registro): array
    {
        if (! empty($datos['password'])) {
            $datos['contrasena_hash'] = Hash::make($datos['password']);
        }
        unset($datos['password']);

        return $datos;
    }

    protected function motivoNoEliminar(Model $registro): ?string
    {
        if ($registro->getKey() == auth()->id()) {
            return 'No puedes eliminar tu propio usuario.';
        }
        return null;
    }
}
