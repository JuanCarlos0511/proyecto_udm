<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\User;
use App\Mail\VerificationCode;
use Illuminate\Support\Facades\Session;

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
        // Validar los datos del formulario
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'age' => 'nullable|numeric|min:0|max:120',
            'phoneNumber' => 'nullable|string|size:10',
            'colonia' => 'nullable|string|max:255',
            'calle' => 'nullable|string|max:255',
            'numExterior' => 'nullable|string|max:20',
            'numInterior' => 'nullable|string|max:20',
            'codigoPostal' => 'nullable|string|size:5',
        ]);

        // Generar un código de verificación de 6 dígitos
        $verificationCode = rand(100000, 999999);

        // Preparar la dirección si se proporcionaron los campos
        $address = null;
        if ($request->colonia || $request->calle || $request->numExterior) {
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
            
            if ($request->numInterior) {
                $addressParts[] = 'No. Int: ' . $request->numInterior;
            }
            
            if ($request->codigoPostal) {
                $addressParts[] = 'C.P. ' . $request->codigoPostal;
            }
            
            $address = implode(', ', $addressParts);
        }
        
        // Almacenar los datos temporalmente en la sesión
        Session::put('registration_data', [
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'age' => $request->age ?? 0,
            'phoneNumber' => $request->phoneNumber ?? '0000000000',
            'address' => $address,
            'verification_code' => $verificationCode,
            'expires_at' => now()->addMinutes(30), // El código expira en 30 minutos
        ]);

        // Enviar el correo con el código de verificación
        Mail::to($request->email)->send(new VerificationCode($verificationCode));

        // Redirigir a la página de verificación
        return redirect()->route('verification.show');
    }

    /**
     * Mostrar el formulario de verificación
     */
    public function showVerificationForm()
    {
        // Verificar si hay datos de registro en la sesión
        if (!Session::has('registration_data')) {
            return redirect()->route('register');
        }

        return view('auth.verify');
    }

    /**
     * Verificar el código ingresado
     */
    public function verify(Request $request)
    {
        // Validar el código ingresado
        $request->validate([
            'verification_code' => 'required|numeric',
        ]);

        // Obtener los datos de registro de la sesión
        $registrationData = Session::get('registration_data');

        // Verificar si los datos existen y no han expirado
        if (!$registrationData || now()->isAfter($registrationData['expires_at'])) {
            Session::forget('registration_data');
            return redirect()->route('register')
                ->with('error', 'El código de verificación ha expirado. Por favor, regístrese nuevamente.');
        }

        // Verificar si el código es correcto
        if ($request->verification_code != $registrationData['verification_code']) {
            return back()->with('error', 'El código de verificación es incorrecto.');
        }

        // Crear el usuario
        $user = User::create([
            'name' => $registrationData['name'],
            'email' => $registrationData['email'],
            'password' => Hash::make($registrationData['password']),
            'email_verified_at' => now(),
            'age' => $registrationData['age'],
            'phoneNumber' => $registrationData['phoneNumber'],
            'address' => $registrationData['address'],
        ]);

        // Eliminar los datos de la sesión
        Session::forget('registration_data');

        // Iniciar sesión automáticamente
        \Illuminate\Support\Facades\Auth::login($user);

        // Redirigir al usuario a la página de perfil para completar sus datos
        return redirect()->route('profile')
            ->with('success', '¡Registro completado! Por favor, complete su perfil.');
    }

    /**
     * Reenviar el código de verificación
     */
    public function resendCode()
    {
        // Obtener los datos de registro de la sesión
        $registrationData = Session::get('registration_data');

        // Verificar si los datos existen
        if (!$registrationData) {
            return redirect()->route('register');
        }

        // Generar un nuevo código de verificación
        $verificationCode = rand(100000, 999999);

        // Actualizar el código en la sesión
        $registrationData['verification_code'] = $verificationCode;
        $registrationData['expires_at'] = now()->addMinutes(30);
        Session::put('registration_data', $registrationData);

        // Enviar el correo con el nuevo código
        Mail::to($registrationData['email'])->send(new VerificationCode($verificationCode));

        // Redirigir de vuelta a la página de verificación
        return back()->with('success', 'Se ha enviado un nuevo código de verificación a su correo electrónico.');
    }
}
