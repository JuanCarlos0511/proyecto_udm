<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class PatientSearchController extends Controller
{
    /**
     * Buscar pacientes por nombre o correo electrónico
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $searchTerm = $request->input('query', '');
        
        // Consulta base para obtener pacientes (usuarios que no son admin ni doctor)
        $query = User::where('role', '!=', 'admin')
            ->where('role', '!=', 'doctor')
            ->select('id', 'name', 'email');
        
        // Si hay un término de búsqueda, filtrar por él
        if (!empty($searchTerm)) {
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('email', 'LIKE', "%{$searchTerm}%");
            });
        }
        
        // Obtener los resultados limitados a 50 para mostrar más opciones iniciales
        // pero evitando sobrecarga en caso de muchos usuarios
        $patients = $query->orderBy('name', 'asc')->limit(50)->get();
        
        return response()->json($patients);
    }
}
