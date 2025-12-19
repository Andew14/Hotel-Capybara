<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $role = Session::get('role');
        
        if ($role !== 'Administrador') {
            return redirect()->route('dashboard')->withErrors(['message' => 'Acceso no autorizado. Se requieren permisos de Administrador.']);
        }

        return $next($request);
    }
}
