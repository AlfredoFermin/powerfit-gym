<?php

namespace App\Http\Controllers;

use App\Models\Clase;
use App\Models\Usuario;
use Illuminate\Database\Eloquent\Model;

/** Módulo: Clases grupales con entrenador, horario y capacidad. */
class ClaseController extends CrudController
{
    protected function modelo(): string
    {
        return Clase::class;
    }

    protected function ruta(): string
    {
        return 'clases';
    }

    protected function titulo(): string
    {
        return 'Clases grupales';
    }

    protected function relaciones(): array
    {
        return ['entrenador'];
    }

    protected function columnas(): array
    {
        return [
            ['label' => 'Clase', 'attr' => 'nombre'],
            ['label' => 'Entrenador', 'attr' => 'entrenador.nombre_completo'],
            ['label' => 'Horario', 'attr' => 'horario'],
            ['label' => 'Capacidad', 'attr' => 'capacidad_maxima'],
        ];
    }

    protected function campos(?Model $registro): array
    {
        $entrenadores = Usuario::whereHas('rol', fn ($q) => $q->where('nombre_rol', 'Entrenador'))
            ->orderBy('nombre')->orderBy('apellido')->get()
            ->mapWithKeys(fn ($u) => [$u->id_usuario => $u->nombre_completo])->all();

        return [
            ['name' => 'nombre', 'label' => 'Nombre de la clase', 'type' => 'text', 'rules' => 'required|string|max:80'],
            ['name' => 'id_entrenador', 'label' => 'Entrenador', 'type' => 'select', 'options' => $entrenadores, 'rules' => 'required|exists:usuarios,id_usuario'],
            ['name' => 'horario', 'label' => 'Horario', 'type' => 'text', 'rules' => 'required|string|max:60', 'ayuda' => 'Ejemplo: Lun y Mié 6:00 PM'],
            ['name' => 'capacidad_maxima', 'label' => 'Capacidad máxima', 'type' => 'number', 'rules' => 'required|integer|min:1|max:500'],
        ];
    }
}
