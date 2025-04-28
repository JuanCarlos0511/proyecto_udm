<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class RegisterController extends Controller
{
    /**
     * Mostrar el formulario de registro
     */
    public function showRegistrationForm()
    {
        return view('register');
    }

    /**
     * Procesar el formulario de registro
     */
    public function register(Request $request)
    {
        // Verificar el token CSRF manualmente para evitar errores 419
        if ($request->session()->token() !== $request->_token) {
            // Regenerar el token CSRF
            $request->session()->regenerateToken();
        }

        // Verificar primero si el correo ya existe
        $existingUser = User::where('email', $request->email)->first();
        if ($existingUser) {
            // Si el correo ya existe, devolver un error con un mensaje personalizado
            return redirect()->back()
                ->withInput($request->except('password'))
                ->withErrors(['email' => 'Este correo electrónico ya está registrado. Por favor, utiliza otro.']);
        }

        // Validar los datos del formulario
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'required|min:8',
            'age' => 'nullable|numeric|min:0|max:120',
            'phoneNumber' => 'nullable|string|size:10',
            'colonia' => 'nullable|string|max:255',
            'calle' => 'nullable|string|max:255',
            'numExterior' => 'nullable|string|max:20',
            'numInterior' => 'nullable|string|max:20',
            'codigoPostal' => 'nullable|string|size:5|regex:/^[0-9]{5}$/',
        ]);
        
        // Establecer valores por defecto
        $numInterior = $request->numInterior;
        if (empty($numInterior)) {
            $numInterior = 'S/N';
        }

        // Preparar la dirección si se proporcionaron los campos
        $address = null;
        if ($request->colonia || $request->calle || $request->numExterior || $request->numInterior || $request->codigoPostal) {
            $addressParts = [];
            
            if ($request->colonia) {
                $addressParts[] = 'Col. ' . $request->colonia;
            }
            
            if ($request->calle) {
                $addressParts[] = $request->calle;
            }
            
            if ($request->numExterior) {
                $addressParts[] = 'No. Ext: ' . $request->numExterior;
            }
            
            // Usar el valor por defecto S/N si no se proporcionó
            $numInterior = $request->numInterior ?? 'S/N';
            $addressParts[] = 'No. Int: ' . $numInterior;
            
            if ($request->codigoPostal) {
                $addressParts[] = 'C.P. ' . $request->codigoPostal;
            }
            
            $address = implode(', ', $addressParts);
        }
        
        // Crear el usuario directamente
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'email_verified_at' => now(), // Marcamos el email como verificado automáticamente
            'age' => $request->age ?? 0,
            'phoneNumber' => $request->phoneNumber ?? '0000000000',
            'adress' => $address, // Usamos el campo 'adress' que ya existe en la base de datos
            'role' => 'user', // Asignamos el rol de usuario por defecto
            'status' => 'active', // Establecemos el estado como activo
        ]);

        // Iniciar sesión automáticamente
        Auth::login($user);

        // Guardar un mensaje de éxito en la sesión
        session()->flash('success', '¡Registro completado! Por favor, complete su perfil.');

        // Redirigir a la página de perfil usando una redirección absoluta
        // Esto debería funcionar independientemente de la configuración de rutas
        return response()->redirectTo(url('/perfil'));
    }
}
