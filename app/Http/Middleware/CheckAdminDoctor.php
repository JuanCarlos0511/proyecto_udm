<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminDoctor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar si el usuario está autenticado
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Debes iniciar sesión para acceder a esta página');
        }

        // Verificar si el usuario tiene el rol de administrador o doctor
        $user = Auth::user();
        if ($user->role !== 'administrador' && $user->role !== 'doctor') {
            return redirect()->route('home')
                ->with('error', 'No tienes permiso para acceder a esta página');
        }

        // Si el usuario tiene el rol requerido, continuar con la solicitud
        return $next($request);
    }
}
