<?php

namespace App\Http\Controllers;

use App\Models\TipoMembresia;
use Illuminate\Database\Eloquent\Model;

/** Módulo: Tipos de membresía (duración y precio). RF-03. */
class TipoMembresiaController extends CrudController
{
    protected function modelo(): string
    {
        return TipoMembresia::class;
    }

    protected function ruta(): string
    {
        return 'tipos';
    }

    protected function titulo(): string
    {
        return 'Tipos de membresía';
    }

    protected function columnas(): array
    {
        return [
            ['label' => 'Nombre', 'attr' => 'nombre'],
            ['label' => 'Duración (días)', 'attr' => 'duracion_dias'],
            ['label' => 'Precio', 'attr' => 'precio', 'formato' => 'dinero'],
        ];
    }

    protected function campos(?Model $registro): array
    {
        return [
            ['name' => 'nombre', 'label' => 'Nombre', 'type' => 'text', 'rules' => 'required|string|max:60'],
            ['name' => 'duracion_dias', 'label' => 'Duración (días)', 'type' => 'number', 'rules' => 'required|integer|min:1|max:3650'],
            ['name' => 'precio', 'label' => 'Precio (RD$)', 'type' => 'number', 'step' => '0.01', 'rules' => 'required|numeric|min:0|max:99999999'],
        ];
    }
}
