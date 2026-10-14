<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/** Inicio y cierre de sesión (RF-12, RNF-02). */
class AuthController extends Controller
{
    public function mostrarLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credenciales = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'Escribe tu correo.',
            'email.email' => 'El correo no es válido.',
            'password.required' => 'Escribe tu contraseña.',
        ]);

        // Solo pueden entrar usuarios activos.
        $credenciales['activo'] = 1;

        if (Auth::attempt($credenciales)) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        return back()
            ->withErrors(['email' => 'Credenciales incorrectas o usuario inactivo.'])
            ->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
