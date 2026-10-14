@extends('layouts.app')

@section('titulo', 'Panel')

@section('contenido')
    <div class="encabezado">
        <h1>Panel principal</h1>
    </div>

    @if(! $verIndicadores)
        <div class="tarjeta">
            <p>Bienvenido(a), {{ auth()->user()->nombre_completo }}. Tu rol es <strong>{{ auth()->user()->rol?->nombre_rol }}</strong>.</p>
            <p style="color:#6b7280;">Los indicadores del gimnasio solo están disponibles para administración y recepción.</p>
        </div>
    @else
        <div class="indicadores">
            @foreach($indicadores as $nombre => $valor)
                <div class="indicador">
                    <div class="valor">{{ $valor }}</div>
                    <div class="nombre">{{ $nombre }}</div>
                </div>
            @endforeach
        </div>

        <div class="dos">
            <div>
                <h3>Membresías por vencer (7 días)</h3>
                <table>
                    <thead><tr><th>Miembro</th><th>Tipo</th><th>Vence</th></tr></thead>
                    <tbody>
                    @forelse($porVencer as $m)
                        <tr>
                            <td>{{ $m->usuario?->nombre_completo }}</td>
                            <td>{{ $m->tipo?->nombre }}</td>
                            <td>{{ $m->fecha_fin->format('d/m/Y') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="vacio">No hay membresías por vencer.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div>
                <h3>Últimos pagos</h3>
                <table>
                    <thead><tr><th>Miembro</th><th>Monto</th><th>Fecha</th></tr></thead>
                    <tbody>
                    @forelse($ultimosPagos as $p)
                        <tr>
                            <td>{{ $p->membresia?->usuario?->nombre_completo }}</td>
                            <td>RD$ {{ number_format((float) $p->monto, 2) }}</td>
                            <td>{{ $p->fecha_pago->format('d/m/Y') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="vacio">Aún no hay pagos registrados.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endsection
