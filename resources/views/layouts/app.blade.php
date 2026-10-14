<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('titulo', 'Panel') · PowerFit Gym</title>
    <style>
        :root { --azul: #0f1f4b; --naranja: #f28c28; --gris: #f3f4f7; --borde: #d9dce3; --texto: #1d2433; }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: system-ui, -apple-system, "Segoe UI", Roboto, Arial, sans-serif; background: var(--gris); color: var(--texto); }
        header.barra { background: var(--azul); color: #fff; display: flex; flex-wrap: wrap; align-items: center; gap: 16px; padding: 10px 24px; }
        .marca { font-weight: 700; font-size: 18px; }
        .marca span { color: var(--naranja); }
        header nav { display: flex; flex-wrap: wrap; gap: 4px; flex: 1; }
        header nav a { color: #cfd6ea; text-decoration: none; padding: 6px 12px; border-radius: 6px; font-size: 14px; }
        header nav a:hover, header nav a.activo { background: rgba(255,255,255,.14); color: #fff; }
        .sesion { display: flex; align-items: center; gap: 10px; font-size: 13px; color: #cfd6ea; }
        .sesion form { margin: 0; }
        main.contenedor { max-width: 1100px; margin: 24px auto; padding: 0 16px; }
        h1 { margin: 0; font-size: 24px; }
        .encabezado { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; gap: 12px; }
        .tarjeta { background: #fff; border: 1px solid var(--borde); border-radius: 10px; padding: 20px; }
        table { width: 100%; border-collapse: collapse; background: #fff; border: 1px solid var(--borde); border-radius: 10px; overflow: hidden; }
        th, td { padding: 10px 12px; text-align: left; border-bottom: 1px solid var(--borde); font-size: 14px; }
        th { background: #eceef4; font-size: 12px; text-transform: uppercase; letter-spacing: .03em; }
        td.acciones { white-space: nowrap; text-align: right; }
        td.acciones form { display: inline; }
        .btn { display: inline-block; background: var(--azul); color: #fff; border: 0; padding: 8px 14px; border-radius: 6px; font-size: 14px; cursor: pointer; text-decoration: none; }
        .btn:hover { opacity: .9; }
        .btn.naranja { background: var(--naranja); }
        .btn.gris { background: #6b7280; }
        .btn.peligro { background: #c0392b; }
        .btn.chico { padding: 5px 10px; font-size: 13px; }
        .alerta { padding: 12px 14px; border-radius: 8px; margin-bottom: 16px; font-size: 14px; }
        .alerta.ok { background: #e3f6e8; color: #17633a; border: 1px solid #b6e3c4; }
        .alerta.error { background: #fde8e6; color: #8a2a20; border: 1px solid #f3bcb6; }
        .campo { margin-bottom: 16px; }
        .campo label { display: block; font-weight: 600; margin-bottom: 6px; font-size: 14px; }
        .campo input, .campo select, .campo textarea { width: 100%; padding: 9px 10px; border: 1px solid var(--borde); border-radius: 6px; font-size: 14px; background: #fff; }
        .campo small { display: block; color: #6b7280; margin-top: 4px; }
        .campo .error { display: block; color: #c0392b; margin-top: 4px; font-size: 13px; }
        .formulario { max-width: 560px; }
        .chip { padding: 2px 10px; border-radius: 999px; font-size: 12px; font-weight: 600; background: #e5e7eb; }
        .chip.activa { background: #dcf5e4; color: #17633a; }
        .chip.vencida { background: #fdebd0; color: #8a5a10; }
        .chip.cancelada { background: #fde8e6; color: #8a2a20; }
        .indicadores { display: grid; grid-template-columns: repeat(auto-fit, minmax(190px, 1fr)); gap: 14px; margin-bottom: 24px; }
        .indicador { background: #fff; border: 1px solid var(--borde); border-left: 4px solid var(--naranja); border-radius: 10px; padding: 16px; }
        .indicador .valor { font-size: 26px; font-weight: 700; color: var(--azul); }
        .indicador .nombre { font-size: 13px; color: #6b7280; margin-top: 4px; }
        .dos { display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 16px; }
        .paginacion { display: flex; justify-content: space-between; align-items: center; margin-top: 14px; font-size: 14px; }
        .vacio { text-align: center; color: #6b7280; padding: 28px; }
        .login { max-width: 380px; margin: 60px auto; }
    </style>
</head>
<body>
@auth
    <header class="barra">
        <div class="marca">PowerFit <span>Gym</span></div>
        <nav>
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'activo' : '' }}">Panel</a>
            @if(auth()->user()->tieneRol('Administrador'))
                <a href="{{ route('usuarios.index') }}" class="{{ request()->routeIs('usuarios.*') ? 'activo' : '' }}">Usuarios</a>
                <a href="{{ route('tipos.index') }}" class="{{ request()->routeIs('tipos.*') ? 'activo' : '' }}">Tipos de membresía</a>
            @endif
            @if(auth()->user()->tieneRol('Administrador', 'Recepcionista'))
                <a href="{{ route('membresias.index') }}" class="{{ request()->routeIs('membresias.*') ? 'activo' : '' }}">Membresías</a>
                <a href="{{ route('pagos.index') }}" class="{{ request()->routeIs('pagos.*') ? 'activo' : '' }}">Pagos</a>
                <a href="{{ route('asistencias.index') }}" class="{{ request()->routeIs('asistencias.*') ? 'activo' : '' }}">Asistencia</a>
            @endif
            @if(auth()->user()->tieneRol('Administrador', 'Recepcionista', 'Entrenador'))
                <a href="{{ route('clases.index') }}" class="{{ request()->routeIs('clases.*') ? 'activo' : '' }}">Clases</a>
            @endif
        </nav>
        <div class="sesion">
            <span>{{ auth()->user()->nombre_completo }} · {{ auth()->user()->rol?->nombre_rol }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn chico naranja">Salir</button>
            </form>
        </div>
    </header>
@endauth

<main class="contenedor">
    @if(session('ok'))
        <div class="alerta ok">{{ session('ok') }}</div>
    @endif
    @if(session('error'))
        <div class="alerta error">{{ session('error') }}</div>
    @endif

    @yield('contenido')
</main>
</body>
</html>
