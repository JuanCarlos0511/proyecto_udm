<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BillingHistoryController extends Controller
{
    /**
     * Obtiene los datos de las facturas para mostrar en el historial
     */
    public function getBillsData(Request $request)
    {
        try {
            // Validar entrada
            $request->validate([
                'date_from' => 'nullable|date',
                'date_to' => 'nullable|date'
            ]);
            
            // Valores por defecto para las fechas
            $defaultStartDate = $request->filled('date_from') ? $request->date_from : '2025-01-01';
            $defaultEndDate = $request->filled('date_to') ? $request->date_to : '2025-12-31';
            
            // Convertir a objetos de fecha
            $startDate = \Carbon\Carbon::parse($defaultStartDate)->startOfDay();
            $endDate = \Carbon\Carbon::parse($defaultEndDate)->endOfDay();
            
            Log::info('API: Filtrando facturas desde ' . $startDate->format('Y-m-d') . ' hasta ' . $endDate->format('Y-m-d'));
            
            // Obtener facturas
            $query = Bill::with(['user'])
                ->whereBetween('created_at', [$startDate, $endDate])
                ->orderBy('created_at', 'desc');
            
            $bills = $query->get();
            
            // Estadísticas
            $total = Bill::count();
            $pendingCount = Bill::where('status', 'pendiente')->count();
            
            // Calcular el número total de facturas de este mes
            $currentMonthStart = \Carbon\Carbon::now()->startOfMonth();
            $currentMonthEnd = \Carbon\Carbon::now()->endOfMonth();
            $monthlyBills = Bill::whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])
                ->count();
            
            // Formatear los datos para su visualización
            $formattedBills = $bills->map(function ($bill) {
                return [
                    'id' => $bill->id,
                    'invoice_number' => "#INV-" . str_pad($bill->id, 3, '0', STR_PAD_LEFT),
                    'patient' => $bill->user->name,
                    'date' => \Carbon\Carbon::parse($bill->created_at)->format('d/m/Y'),
                    'phone' => $bill->user->phone ?? 'N/A',
                    'rfc' => $bill->rfc,
                    'status' => $bill->status,
                    'cuenta_con_seguro' => $bill->cuenta_con_seguro,
                    'user_id' => $bill->user_id
                ];
            });
            
            return response()->json([
                'success' => true,
                'data' => [
                    'bills' => $formattedBills,
                    'stats' => [
                        'total' => $total,
                        'pending' => $pendingCount,
                        'monthly_bills' => $monthlyBills
                    ]
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error al cargar datos de facturas: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar datos de facturas',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
