<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use App\Models\Usuario;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/** Módulo: Registro de asistencia de los miembros. RF-07. */
class AsistenciaController extends CrudController
{
    protected function modelo(): string
    {
        return Asistencia::class;
    }

    protected function ruta(): string
    {
        return 'asistencias';
    }

    protected function titulo(): string
    {
        return 'Asistencia';
    }

    protected function relaciones(): array
    {
        return ['usuario'];
    }

    protected function columnas(): array
    {
        return [
            ['label' => 'Miembro', 'attr' => 'usuario.nombre_completo'],
            ['label' => 'Fecha y hora', 'attr' => 'fecha_hora', 'formato' => 'fechahora'],
        ];
    }

    protected function campos(?Model $registro): array
    {
        $miembros = Usuario::whereHas('rol', fn ($q) => $q->where('nombre_rol', 'Miembro'))
            ->orderBy('nombre')->orderBy('apellido')->get()
            ->mapWithKeys(fn ($u) => [$u->id_usuario => $u->nombre_completo])->all();

        return [
            ['name' => 'id_usuario', 'label' => 'Miembro', 'type' => 'select', 'options' => $miembros, 'rules' => 'required|exists:usuarios,id_usuario'],
            [
                'name' => 'fecha_hora', 'label' => 'Fecha y hora', 'type' => 'datetime-local',
                'valor' => now()->format('Y-m-d\TH:i'),
                'rules' => 'nullable|date',
                'ayuda' => 'Por defecto es el momento actual.',
            ],
        ];
    }

    protected function preparar(array $datos, ?Model $registro): array
    {
        $datos['fecha_hora'] = empty($datos['fecha_hora'])
            ? now()->format('Y-m-d H:i:s')
            : Carbon::parse($datos['fecha_hora'])->format('Y-m-d H:i:s');

        return $datos;
    }
}
