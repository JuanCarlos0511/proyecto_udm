<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

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

        // Obtener información del paciente
        $patient = User::findOrFail($request->user_id);

        // Generar PDF
        $pdf = PDF::loadView('admin.billing.pdf-template', [
            'bill' => $bill,
            'patient' => $patient
        ]);

        // Guardar el PDF en storage
        $pdfPath = 'bills/' . $bill->id . '.pdf';
        \Storage::put('public/' . $pdfPath, $pdf->output());

        // Actualizar la factura con la ruta del PDF
        $bill->update(['pdf_path' => $pdfPath]);

        return response()->json([
            'success' => true,
            'message' => 'Factura generada correctamente',
            'bill_id' => $bill->id,
            'pdf_url' => asset('storage/' . $pdfPath)
        ]);
    }
}
