<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AppointmentHistoryController extends Controller
{
    /**
     * Get appointment data for the history view via API.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAppointmentData(Request $request)
    {
        try {
            $user = auth()->user();
            
            // Verificar que el usuario tenga permisos (doctor o admin)
            if (!$user || !in_array($user->role, ['doctor', 'admin'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tiene permisos para acceder a esta funcionalidad. Se requiere rol de doctor o administrador.'
                ], 403);
            }
            
            // Valores por defecto para las fechas
            $defaultStartDate = $request->filled('date_from') ? $request->date_from : '2025-01-01';
            $defaultEndDate = $request->filled('date_to') ? $request->date_to : '2025-12-31';
            
            // Preparar las fechas para la consulta
            $startDate = Carbon::parse($defaultStartDate)->startOfDay();
            $endDate = Carbon::parse($defaultEndDate)->endOfDay();
            
            Log::info('API: Filtrando citas desde ' . $startDate->format('Y-m-d') . ' hasta ' . $endDate->format('Y-m-d'));
            
            // Para admin: mostrar todas las citas de pacientes (no los registros duplicados de doctores)
            if ($user->role === 'admin') {
                Log::info('API: Consultando todas las citas para admin');
                $query = Appointment::with(['user'])
                    ->where('status', '!=', 'Doctor Asignado')  // Filtrar registros de doctores
                    ->whereHas('user', function($q) {
                        $q->where('role', 'paciente');  // Solo citas donde el user_id es de un paciente
                    })
                    ->whereBetween('date', [$startDate, $endDate])
                    ->orderBy('date', 'desc');
            } 
            // Para doctor: solo mostrar sus citas
            elseif ($user->role === 'doctor') {
                Log::info('API: Consultando citas para doctor ID: ' . $user->id);
                
                // Obtener IDs de grupos de citas donde este doctor está asignado
                $doctorAppointmentGroups = Appointment::where('user_id', $user->id)
                    ->whereNotNull('appointment_group_id')
                    ->pluck('appointment_group_id')
                    ->toArray();
                
                Log::info('Grupos de citas del doctor: ' . json_encode($doctorAppointmentGroups));
                
                if (empty($doctorAppointmentGroups)) {
                    return response()->json([
                        'success' => true,
                        'data' => [
                            'appointments' => [],
                            'stats' => [
                                'total_month' => 0,
                                'pending' => 0
                            ]
                        ]
                    ]);
                }
                
                // Mostrar solo citas de pacientes relacionadas con este doctor
                $query = Appointment::with(['user'])
                    ->where('status', '!=', 'Doctor Asignado')  // Filtrar registros de doctores
                    ->whereHas('user', function($q) {
                        $q->where('role', 'paciente');  // Solo citas donde el user_id es de un paciente
                    })
                    ->whereBetween('date', [$startDate, $endDate])
                    ->whereIn('appointment_group_id', $doctorAppointmentGroups)
                    ->orderBy('date', 'desc');
            }
            
            // Ejecutar la consulta
            $allAppointments = $query->get();
            
            // Formatear las citas para la respuesta JSON
            $formattedAppointments = $allAppointments->map(function ($appointment) {
                return [
                    'id' => $appointment->id,
                    'patient_name' => $appointment->user ? $appointment->user->name : 'N/A',
                    'date' => Carbon::parse($appointment->date)->format('d/m/Y'),
                    'subject' => $appointment->subject ?: 'N/A',
                    'status' => $appointment->status,
                    'modality' => $appointment->modality ?: 'Consultorio',
                    'price' => number_format($appointment->price, 2),
                ];
            });
            
            // Calcular estadísticas
            $currentMonth = Carbon::now();
            $totalMonthAppointments = $allAppointments->filter(function ($appointment) use ($currentMonth) {
                $appointmentDate = Carbon::parse($appointment->date);
                return $appointmentDate->month == $currentMonth->month && 
                       $appointmentDate->year == $currentMonth->year;
            })->count();
            
            $pendingAppointments = $allAppointments->filter(function ($appointment) {
                return in_array($appointment->status, ['Solicitado', 'Agendado']);
            })->count();
            
            return response()->json([
                'success' => true,
                'data' => [
                    'appointments' => $formattedAppointments,
                    'stats' => [
                        'total_month' => $totalMonthAppointments,
                        'pending' => $pendingAppointments
                    ],
                    'dateRange' => [
                        'start' => $startDate->format('Y-m-d'),
                        'end' => $endDate->format('Y-m-d')
                    ]
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error al obtener datos de citas: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar datos de citas',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
