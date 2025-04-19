<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Exception;

class GoogleController extends Controller
{
    /**
     * Redirecciona al usuario a la página de autenticación de Google.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }
    
    /**
     * Obtiene la información del usuario de Google después de la autenticación.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleGoogleCallback()
    {
        try {
            $user = Socialite::driver('google')->user();
            
            \Illuminate\Support\Facades\Log::info('Google user data:', [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ]);
            
            // Buscar usuario existente o crear uno nuevo
            $findUser = User::where('google_id', $user->id)->first();
            
            if ($findUser) {
                // Si el usuario ya existe, iniciar sesión
                \Illuminate\Support\Facades\Log::info('Usuario existente encontrado con google_id');
                Auth::login($findUser);
                return redirect('/')->with('success', 'Has iniciado sesión correctamente');
            } else {
                // Verificar si el email ya está registrado
                $existingUser = User::where('email', $user->email)->first();
                
                if ($existingUser) {
                    // Actualizar el usuario existente con el google_id
                    \Illuminate\Support\Facades\Log::info('Usuario existente encontrado con email, actualizando google_id');
                    $existingUser->google_id = $user->id;
                    $existingUser->save();
                    Auth::login($existingUser);
                    return redirect('/')->with('success', 'Has iniciado sesión correctamente');
                } else {
                    // Crear un nuevo usuario
                    \Illuminate\Support\Facades\Log::info('Creando nuevo usuario');
                    try {
                        $newUser = User::create([
                            'name' => $user->name,
                            'email' => $user->email,
                            'google_id' => $user->id,
                            'password' => bcrypt(\Illuminate\Support\Str::random(16)),
                            'age' => 0, // Valor por defecto para el campo requerido
                            'phoneNumber' => '000000000', // Valor por defecto para el campo requerido
                            'role' => 'paciente' // Valor por defecto
                        ]);
                        
                        \Illuminate\Support\Facades\Log::info('Nuevo usuario creado con ID: ' . $newUser->id);
                        Auth::login($newUser);
                        return redirect('/')->with('success', 'Te has registrado e iniciado sesión correctamente');
                    } catch (\Exception $createError) {
                        \Illuminate\Support\Facades\Log::error('Error al crear usuario: ' . $createError->getMessage());
                        throw $createError;
                    }
                }
            }
        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error en autenticación con Google: ' . $e->getMessage());
            return redirect('/login')->with('error', 'Ocurrió un error al intentar iniciar sesión con Google: ' . $e->getMessage());
        }
    }
}
