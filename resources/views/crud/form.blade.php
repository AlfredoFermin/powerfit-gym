@extends('layouts.app')

@section('titulo', $cfg['titulo'])

@section('contenido')
    <div class="encabezado">
        <h1>{{ $registro ? 'Editar' : 'Nuevo registro' }} · {{ $cfg['titulo'] }}</h1>
    </div>

    <div class="tarjeta formulario">
        <form method="POST" action="{{ $url }}">
            @csrf
            @if($metodo !== 'POST')
                @method($metodo)
            @endif

            @foreach($campos as $c)
                @php
                    $n = $c['name'];
                    $tipo = $c['type'] ?? 'text';
                    // Al editar se usa el valor guardado; 'valor' solo es el valor inicial de un registro nuevo.
                    $v = old($n, $registro ? data_get($registro, $n) : ($c['valor'] ?? null));
                    if ($tipo === 'password') { $v = ''; }
                    if ($v instanceof \DateTimeInterface) {
                        $v = $v->format($tipo === 'datetime-local' ? 'Y-m-d\TH:i' : 'Y-m-d');
                    }
                    if (is_bool($v)) { $v = (int) $v; }
                @endphp

                <div class="campo">
                    <label for="{{ $n }}">{{ $c['label'] }}</label>

                    @if($tipo === 'select')
                        <select id="{{ $n }}" name="{{ $n }}">
                            <option value="">— Seleccione —</option>
                            @foreach($c['options'] as $valor => $texto)
                                <option value="{{ $valor }}" @selected((string) $v === (string) $valor)>{{ $texto }}</option>
                            @endforeach
                        </select>
                    @elseif($tipo === 'textarea')
                        <textarea id="{{ $n }}" name="{{ $n }}" rows="4">{{ $v }}</textarea>
                    @else
                        <input type="{{ $tipo }}" id="{{ $n }}" name="{{ $n }}" value="{{ $v }}"
                               @if($tipo === 'number') step="{{ $c['step'] ?? 1 }}" @endif
                               @if($tipo === 'password') autocomplete="new-password" @endif>
                    @endif

                    @if(! empty($c['ayuda']))
                        <small>{{ $c['ayuda'] }}</small>
                    @endif
                    @error($n)
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            @endforeach

            <button type="submit" class="btn">Guardar</button>
            <a class="btn gris" href="{{ route($cfg['ruta'] . '.index') }}">Cancelar</a>
        </form>
    </div>
@endsection
