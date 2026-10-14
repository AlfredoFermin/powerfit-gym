@extends('layouts.app')

@section('titulo', 'Iniciar sesión')

@section('contenido')
    <div class="login tarjeta">
        <h1 style="margin-bottom: 4px;">PowerFit Gym</h1>
        <p style="color:#6b7280; margin-top:0;">Sistema de gestión · Inicia sesión</p>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="campo">
                <label for="email">Correo</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
                @error('email')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="campo">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required>
                @error('password')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn" style="width:100%;">Entrar</button>
        </form>
    </div>
@endsection
