<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Models\User;

class ProfileController extends Controller
{
    public function __construct()
    {
        // El constructor está vacío, la autenticación se maneja en las rutas
    }
    
    /**
     * Verificar si el perfil del usuario está completo (campos obligatorios).
     */
    public function isProfileComplete()
    {
        if (!Auth::check()) {
            return true; // Si no hay usuario autenticado, no mostrar advertencia
        }
        
        $user = Auth::user();
        
        // Verificar si los campos requeridos están completos
        // Nota: El campo de dirección (adress) no se considera para determinar si el perfil está completo
        return !empty($user->name) && 
               !empty($user->age) && 
               !empty($user->phoneNumber);
    }
    
    /**
     * Verificar si faltan campos opcionales por completar.
     */
    public function hasOptionalFieldsMissing()
    {
        if (!Auth::check()) {
            return false;
        }
        
        $user = Auth::user();
        
        // Verificar si alguno de los campos opcionales está vacío
        return empty($user->adress) || 
               empty($user->emergency_contact_name) || 
               empty($user->emergency_contact_phone) || 
               empty($user->emergency_contact_relationship);
    }
    
    /**
     * Verificar si el perfil está completamente rellenado (tanto campos obligatorios como opcionales).
     */
    public function isProfileFullyComplete()
    {
        if (!Auth::check()) {
            return true;
        }
        
        // El perfil está completamente rellenado si todos los campos obligatorios están completos
        // y no hay campos opcionales faltantes
        return $this->isProfileComplete() && !$this->hasOptionalFieldsMissing();
    }
    
    /**
     * Mostrar la vista de perfil del usuario.
     */
    public function show()
    {
        $user = Auth::user();
        $isProfileComplete = $this->isProfileComplete();
        $hasOptionalFieldsMissing = $this->hasOptionalFieldsMissing();
        $missingFields = $this->getMissingFields();
        
        return view('profile', compact('user', 'isProfileComplete', 'hasOptionalFieldsMissing', 'missingFields'));
    }
    
    /**
     * Obtener un array con los nombres de los campos que faltan por completar.
     */
    private function getMissingFields()
    {
        $user = Auth::user();
        $missingFields = [];
        
        if (empty($user->adress)) {
            $missingFields['adress'] = 'Dirección';
        }
        
        if (empty($user->emergency_contact_name)) {
            $missingFields['emergency_contact_name'] = 'Nombre del contacto de emergencia';
        }
        
        if (empty($user->emergency_contact_phone)) {
            $missingFields['emergency_contact_phone'] = 'Teléfono del contacto de emergencia';
        }
        
        if (empty($user->emergency_contact_relationship)) {
            $missingFields['emergency_contact_relationship'] = 'Relación con el contacto de emergencia';
        }
        
        return $missingFields;
    }

    /**
     * Actualizar la información del perfil del usuario.
     */
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'age' => 'required|integer|min:1|max:120',
            'phoneNumber' => 'required|digits:10',
            'adress' => 'nullable|string|max:255', // Cambiado a nullable (opcional)
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|digits:10',
            'emergency_contact_relationship' => 'nullable|string|max:100',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validación para la foto
        ]);

        // Obtener el ID del usuario autenticado
        $userId = Auth::id();
        $user = Auth::user();
        
        // Preparar los datos para actualizar
        $userData = [
            'name' => $request->name,
            'age' => $request->age,
            'phoneNumber' => $request->phoneNumber,
            'adress' => $request->adress,
            'emergency_contact_name' => $request->emergency_contact_name,
            'emergency_contact_phone' => $request->emergency_contact_phone,
            'emergency_contact_relationship' => $request->emergency_contact_relationship,
        ];
        
        // Procesar la foto si se ha subido una nueva
        if ($request->hasFile('photo')) {
            // Si ya existe una foto anterior, eliminarla
            if ($user->photo_path && file_exists(public_path($user->photo_path))) {
                unlink(public_path($user->photo_path));
            }
            
            // Guardar la nueva foto
            $photoName = time() . '_' . $userId . '.' . $request->photo->extension();
            $request->photo->move(public_path('uploads/photos'), $photoName);
            $userData['photo_path'] = 'uploads/photos/' . $photoName;
        }
        // Procesar la foto tomada con la cámara
        elseif ($request->filled('camera_photo')) {
            // Obtener la imagen en base64 y decodificarla
            $imageData = $request->input('camera_photo');
            
            // Verificar que la cadena base64 es válida
            if (preg_match('/^data:image\/([a-zA-Z]*);base64,([^\s]*)$/', $imageData, $matches)) {
                // Si ya existe una foto anterior, eliminarla
                if ($user->photo_path && file_exists(public_path($user->photo_path))) {
                    unlink(public_path($user->photo_path));
                }
                
                // Extraer el tipo de imagen y los datos
                $imageType = $matches[1];
                $imageData = base64_decode($matches[2]);
                
                // Crear un nombre único para la imagen
                $photoName = time() . '_' . $userId . '_camera.' . $imageType;
                $photoPath = 'uploads/photos/' . $photoName;
                
                // Guardar la imagen en el servidor
                file_put_contents(public_path($photoPath), $imageData);
                
                // Actualizar la ruta de la foto en la base de datos
                $userData['photo_path'] = $photoPath;
            }
        }
        
        // Actualizar los datos del usuario usando el método update
        User::where('id', $userId)->update($userData);

        return redirect()->route('profile')->with('success', 'Perfil actualizado correctamente');
    }

    /**
     * Eliminar la foto de perfil del usuario.
     */
    public function deletePhoto()
    {
        $user = Auth::user();

        // Si el usuario tiene una foto
        if ($user->photo_path) {
            // Eliminar el archivo físico
            if (file_exists(public_path($user->photo_path))) {
                unlink(public_path($user->photo_path));
            }

            // Actualizar la base de datos
            $user->update(['photo_path' => null]);

            return redirect()->route('profile')->with('success', 'Foto de perfil eliminada correctamente');
        }

        return redirect()->route('profile')->with('error', 'No hay foto de perfil para eliminar');
    }
}
