<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Models\User;
use Illuminate\Http\Request;

class BillingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'rfc' => 'required|string|max:13',
            'codigo_postal' => 'required|string|max:5',
            'regimen_fiscal' => 'required|string',
            'cfdi' => 'required|string',
            'cuenta_con_seguro' => 'boolean'
        ]);

        // Crear la factura en la base de datos
        $bill = Bill::create([
            'user_id' => $request->user_id,
            'rfc' => $request->rfc,
            'codigo_postal' => $request->codigo_postal,
            'regimen_fiscal' => $request->regimen_fiscal,
            'cfdi' => $request->cfdi,
            'cuenta_con_seguro' => $request->cuenta_con_seguro ?? false,
            'status' => 'pendiente'
        ]);

        // Obtener información del paciente para referencia
        $patient = User::findOrFail($request->user_id);
        
        // La factura ya se guardó con status 'pendiente' por defecto
        // El cambio a 'realizada' se hará mediante un botón en la interfaz

        return response()->json([
            'success' => true,
            'message' => 'Factura registrada correctamente',
            'bill_id' => $bill->id
        ]);
    }
    
    /**
     * Actualiza el estado de una factura a 'realizada'
     */
    public function markAsCompleted(Request $request, $id)
    {
        $bill = Bill::findOrFail($id);
        $bill->update(['status' => 'realizada']);
        
        return response()->json([
            'success' => true,
            'message' => 'Estado de factura actualizado a realizada',
            'bill_id' => $bill->id
        ]);
    }
}
