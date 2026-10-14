<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use App\Models\Clase;
use App\Models\Membresia;
use App\Models\Pago;
use App\Models\Usuario;

/** Panel principal con indicadores y estadísticas básicas. RF-13. */
class DashboardController extends Controller
{
    public function __invoke()
    {
        $usuario = auth()->user();
        $datos = ['verIndicadores' => $usuario->tieneRol('Administrador', 'Recepcionista')];

        if ($datos['verIndicadores']) {
            $hoy = today();

            $datos['indicadores'] = [
                'Miembros activos' => Usuario::where('activo', 1)
                    ->whereHas('rol', fn ($q) => $q->where('nombre_rol', 'Miembro'))->count(),
                'Membresías activas' => Membresia::where('estado', 'activa')->count(),
                'Ingresos del mes' => 'RD$ ' . number_format(
                    (float) Pago::whereBetween('fecha_pago', [$hoy->copy()->startOfMonth(), $hoy->copy()->endOfMonth()])->sum('monto'),
                    2
                ),
                'Asistencias de hoy' => Asistencia::whereDate('fecha_hora', $hoy)->count(),
                'Clases grupales' => Clase::count(),
            ];

            $datos['porVencer'] = Membresia::with(['usuario', 'tipo'])
                ->where('estado', 'activa')
                ->whereBetween('fecha_fin', [$hoy->copy(), $hoy->copy()->addDays(7)])
                ->orderBy('fecha_fin')
                ->get();

            $datos['ultimosPagos'] = Pago::with('membresia.usuario')
                ->orderByDesc('id_pago')
                ->limit(5)
                ->get();
        }

        return view('dashboard', $datos);
    }
}
