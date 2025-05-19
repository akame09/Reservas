<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $usuario = Usuario::where('email', $request->email)->first();

        // 🔓 Comparación simple SIN bcrypt
        if (!$usuario || $usuario->password !== $request->password) {
            return back()->withErrors([
                'email' => 'Credenciales inválidas',
            ])->withInput();
        }

        Auth::login($usuario);

        return redirect()->route('reservas.index');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}

