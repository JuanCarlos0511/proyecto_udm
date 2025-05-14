<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    /**
     * Mostrar el formulario de inicio de sesión
     */
    public function showLoginForm()
    {
        return view('login');
    }

    /**
     * Manejar una solicitud de inicio de sesión
     */
    public function login(Request $request)
    {
        // Validar los datos del formulario
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Intentar iniciar sesión
        if (Auth::attempt($credentials)) {
            // Regenerar la sesión para evitar ataques de fijación de sesión
            $request->session()->regenerate();

            // Guardar un mensaje de bienvenida
            session()->flash('success', '¡Bienvenido de nuevo! Has iniciado sesión correctamente.');

            // Obtener el usuario autenticado
            $user = Auth::user();

            // Redireccionar según el rol del usuario
            if ($user->role === 'doctor' || $user->role === 'admin') {
                // Tanto doctores como administradores van al tablero de administración
                return redirect('/admin/tablero');
            } else {
                // Para usuarios con otros roles (pacientes, etc.)
                return redirect()->intended('/');
            }
        }

        // Si la autenticación falla, regresar con un mensaje de error
        return back()
            ->withInput($request->only('email'))
            ->withErrors([
                'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
            ]);
    }

    /**
     * Cerrar la sesión del usuario
     */
    public function logout(Request $request)
    {
        Auth::logout();

        // Invalidar la sesión
        $request->session()->invalidate();

        // Regenerar el token CSRF
        $request->session()->regenerateToken();

        // Redireccionar a la página de inicio
        return redirect('/');
    }
}
