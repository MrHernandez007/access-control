<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AutorizarRol
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = $request->get('user_jwt');
        if (!$user) {
            return redirect()->route('login');
        }

        if (!in_array($user['rol'], $roles)) {
            return redirect()->route('login.admin')->withErrors(['error' => 'No autorizado admin, por favor inicia sesión. ADMIN']);
            // abort(403, 'No autorizado');
        }

        return $next($request);
    }
}
