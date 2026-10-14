<?php

namespace App\Http\Controllers;

use App\Models\Membresia;
use App\Models\Pago;
use Illuminate\Database\Eloquent\Model;

/** Módulo: Pagos de las membresías, con historial. RF-05, RF-06. */
class PagoController extends CrudController
{
    protected function modelo(): string
    {
        return Pago::class;
    }

    protected function ruta(): string
    {
        return 'pagos';
    }

    protected function titulo(): string
    {
        return 'Pagos';
    }

    protected function relaciones(): array
    {
        return ['membresia.usuario', 'membresia.tipo'];
    }

    protected function columnas(): array
    {
        return [
            ['label' => 'Miembro', 'attr' => 'membresia.usuario.nombre_completo'],
            ['label' => 'Membresía', 'attr' => 'membresia.tipo.nombre'],
            ['label' => 'Monto', 'attr' => 'monto', 'formato' => 'dinero'],
            ['label' => 'Fecha', 'attr' => 'fecha_pago', 'formato' => 'fecha'],
            ['label' => 'Método', 'attr' => 'metodo_pago'],
        ];
    }

    protected function campos(?Model $registro): array
    {
        $membresias = Membresia::with(['usuario', 'tipo'])->orderByDesc('id_membresia')->get()
            ->mapWithKeys(fn ($m) => [
                $m->id_membresia => '#' . $m->id_membresia . ' · ' . ($m->usuario?->nombre_completo ?? '?') . ' · ' . ($m->tipo?->nombre ?? '?'),
            ])->all();

        return [
            ['name' => 'id_membresia', 'label' => 'Membresía', 'type' => 'select', 'options' => $membresias, 'rules' => 'required|exists:membresias,id_membresia'],
            ['name' => 'monto', 'label' => 'Monto (RD$)', 'type' => 'number', 'step' => '0.01', 'rules' => 'required|numeric|min:0|max:99999999'],
            ['name' => 'fecha_pago', 'label' => 'Fecha de pago', 'type' => 'date', 'valor' => today()->toDateString(), 'rules' => 'required|date'],
            [
                'name' => 'metodo_pago', 'label' => 'Método de pago', 'type' => 'select',
                'options' => ['efectivo' => 'Efectivo', 'tarjeta' => 'Tarjeta', 'transferencia' => 'Transferencia'],
                'rules' => 'required|in:efectivo,tarjeta,transferencia',
            ],
        ];
    }
}
