@extends('layouts.app')

@section('titulo', $cfg['titulo'])

@section('contenido')
    <div class="encabezado">
        <h1>{{ $cfg['titulo'] }}</h1>
        <a class="btn naranja" href="{{ route($cfg['ruta'] . '.create') }}">+ Nuevo</a>
    </div>

    <table>
        <thead>
            <tr>
                @foreach($cfg['columnas'] as $col)
                    <th>{{ $col['label'] }}</th>
                @endforeach
                <th></th>
            </tr>
        </thead>
        <tbody>
        @forelse($filas as $fila)
            <tr>
                @foreach($cfg['columnas'] as $col)
                    @php
                        $v = data_get($fila, $col['attr']);
                        $f = $col['formato'] ?? 'texto';
                    @endphp
                    <td>
                        @if($f === 'fecha')
                            {{ $v ? $v->format('d/m/Y') : '—' }}
                        @elseif($f === 'fechahora')
                            {{ $v ? $v->format('d/m/Y h:i A') : '—' }}
                        @elseif($f === 'dinero')
                            RD$ {{ number_format((float) $v, 2) }}
                        @elseif($f === 'bool')
                            {{ $v ? 'Sí' : 'No' }}
                        @elseif($f === 'estado')
                            <span class="chip {{ $v }}">{{ ucfirst((string) $v) }}</span>
                        @else
                            {{ $v ?? '—' }}
                        @endif
                    </td>
                @endforeach
                <td class="acciones">
                    <a class="btn chico gris" href="{{ route($cfg['ruta'] . '.edit', $fila->getKey()) }}">Editar</a>
                    <form method="POST" action="{{ route($cfg['ruta'] . '.destroy', $fila->getKey()) }}"
                          onsubmit="return confirm('¿Seguro que deseas eliminar este registro?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn chico peligro">Eliminar</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="{{ count($cfg['columnas']) + 1 }}" class="vacio">No hay registros todavía.</td></tr>
        @endforelse
        </tbody>
    </table>

    @if($filas->lastPage() > 1)
        <div class="paginacion">
            <span>Página {{ $filas->currentPage() }} de {{ $filas->lastPage() }} · {{ $filas->total() }} registros</span>
            <span>
                @if($filas->previousPageUrl())
                    <a class="btn chico gris" href="{{ $filas->previousPageUrl() }}">← Anterior</a>
                @endif
                @if($filas->nextPageUrl())
                    <a class="btn chico gris" href="{{ $filas->nextPageUrl() }}">Siguiente →</a>
                @endif
            </span>
        </div>
    @endif
@endsection
