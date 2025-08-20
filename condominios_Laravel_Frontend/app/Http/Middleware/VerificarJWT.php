<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Log; // <-- Importar Log

class VerificarJWT
{
    public function handle(Request $request, Closure $next)
    {
        $token = session('jwt_token');
        if (!$token) {
            return redirect()->route('login.residente');
        }

        try {
            $payload = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));
            $request->merge(['user_jwt' => (array)$payload]);
        } catch (\Exception $e) {
            return redirect()->route('login.residente')->withErrors(['error' => 'Residente No autorizado, Token inválido o expirado, por favor inicia sesión. RESIDENTE']);
        }

        //Log::info('Middleware verificar.jwt pasó');

        return $next($request);
    }
}
