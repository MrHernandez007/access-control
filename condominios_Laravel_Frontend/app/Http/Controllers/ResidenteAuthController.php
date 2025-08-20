<?php

 namespace App\Http\Controllers;

 use App\Http\Controllers\Controller;
 use Illuminate\Http\Request;
 use Illuminate\Support\Facades\Auth;
 use Illuminate\Support\Facades\Hash;
 use Firebase\JWT\JWT;

use App\Models\Residente;

class ResidenteAuthController extends Controller
{


    public function showLoginForm()
{
    return view('auth.login_residente');
}

    public function login(Request $request)
    {
        $residente = Residente::where('usuario', $request->usuario)->first();

        if (!$residente || !Hash::check($request->contrasena, $residente->contrasena)) {
            return back()->withErrors(['error' => 'Credenciales incorrectas']);
        }

        $payload = [
            'sub' => $residente->_id,
            'rol' => 'residente',
            'nombre' => $residente->nombre, // agrega esta línea
            'exp' => time() + 3600
        ];

        $token = JWT::encode($payload, env('JWT_SECRET'), 'HS256');

        session(['jwt_token' => $token, 'rol' => 'residente']);
        return redirect()->route('residente.dashboard');
    }

    public function logout(Request $request)
    {
        // Limpiar el token JWT de la sesión
        $request->session()->forget('jwt_token');
        $request->session()->forget('rol');

        // Opcional: invalidar toda la sesión
        // $request->session()->invalidate();

        // Redirigir al login (puedes cambiar la ruta a la que quieras)
        return redirect()->route('login.residente')->with('success', 'Residente, Sesión cerrada correctamente.');
    }
}
