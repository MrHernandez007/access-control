<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\Administrador;
use Tymon\JWTAuth\Contracts\Providers\JWT as ProvidersJWT;
use Tymon\JWTAuth\JWT as JWTAuthJWT;

class AdminAuthController extends Controller
{


    public function showLoginForm()
{
    return view('auth.login_admin');
}

    public function login(Request $request)
    {
        $admin = Administrador::where('usuario', $request->usuario)->first();

        if (!$admin || !Hash::check($request->contrasena, $admin->contrasena)) {
            return back()->withErrors(['error' => 'Credenciales incorrectas']);
        }

        $payload = [
            'sub' => $admin->_id,
            'rol' => 'admin',
            'nombre' => $admin->nombre, // agrega esta línea
            'exp' => time() + 3600
        ];

        $token = JWT::encode($payload, env('JWT_SECRET'), 'HS256');

        session(['jwt_token' => $token, 'rol' => 'admin']);
        return redirect()->route('admin.dashboard');
    }

    public function logout(Request $request)
    {
        // Limpiar el token JWT de la sesión
        $request->session()->forget('jwt_token');
        $request->session()->forget('rol');

        // Opcional: invalidar toda la sesión
        // $request->session()->invalidate();

        // Redirigir al login (puedes cambiar la ruta a la que quieras)
        return redirect()->route('login.admin')->with('success', 'Admin, Sesión cerrada correctamente.');
    }
}
