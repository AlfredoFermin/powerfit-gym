<?php

namespace App\Http\Controllers;

use App\Models\Membresia;
use App\Models\TipoMembresia;
use App\Models\Usuario;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/** Módulo: Membresías asignadas a los miembros, con estado y vencimiento. RF-04. */
class MembresiaController extends CrudController
{
    protected function modelo(): string
    {
        return Membresia::class;
    }

    protected function ruta(): string
    {
        return 'membresias';
    }

    protected function titulo(): string
    {
        return 'Membresías';
    }

    protected function relaciones(): array
    {
        return ['usuario', 'tipo'];
    }

    protected function columnas(): array
    {
        return [
            ['label' => 'Miembro', 'attr' => 'usuario.nombre_completo'],
            ['label' => 'Tipo', 'attr' => 'tipo.nombre'],
            ['label' => 'Inicio', 'attr' => 'fecha_inicio', 'formato' => 'fecha'],
            ['label' => 'Vence', 'attr' => 'fecha_fin', 'formato' => 'fecha'],
            ['label' => 'Estado', 'attr' => 'estado', 'formato' => 'estado'],
        ];
    }

    protected function campos(?Model $registro): array
    {
        $miembros = Usuario::whereHas('rol', fn ($q) => $q->where('nombre_rol', 'Miembro'))
            ->orderBy('nombre')->orderBy('apellido')->get()
            ->mapWithKeys(fn ($u) => [$u->id_usuario => $u->nombre_completo])->all();

        $tipos = TipoMembresia::orderBy('nombre')->get()
            ->mapWithKeys(fn ($t) => [$t->id_tipo => $t->nombre . ' (' . $t->duracion_dias . ' días)'])->all();

        return [
            ['name' => 'id_usuario', 'label' => 'Miembro', 'type' => 'select', 'options' => $miembros, 'rules' => 'required|exists:usuarios,id_usuario'],
            ['name' => 'id_tipo', 'label' => 'Tipo de membresía', 'type' => 'select', 'options' => $tipos, 'rules' => 'required|exists:tipos_membresia,id_tipo'],
            ['name' => 'fecha_inicio', 'label' => 'Fecha de inicio', 'type' => 'date', 'valor' => today()->toDateString(), 'rules' => 'required|date'],
            [
                'name' => 'fecha_fin', 'label' => 'Fecha de vencimiento', 'type' => 'date',
                'rules' => 'nullable|date|after_or_equal:fecha_inicio',
                'ayuda' => 'Si la dejas vacía se calcula con la duración del tipo de membresía.',
            ],
            [
                'name' => 'estado', 'label' => 'Estado', 'type' => 'select', 'valor' => 'activa',
                'options' => ['activa' => 'Activa', 'vencida' => 'Vencida', 'cancelada' => 'Cancelada'],
                'rules' => 'required|in:activa,vencida,cancelada',
            ],
        ];
    }

    protected function preparar(array $datos, ?Model $registro): array
    {
        if (empty($datos['fecha_fin'])) {
            $tipo = TipoMembresia::find($datos['id_tipo']);
            $datos['fecha_fin'] = Carbon::parse($datos['fecha_inicio'])->addDays(max(0, (int) $tipo->duracion_dias - 1))->toDateString();
        }

        return $datos;
    }
}
