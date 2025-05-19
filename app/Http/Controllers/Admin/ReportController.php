<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;

class ReportController extends Controller
{
    /**
     * Display the report generation form.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get the current date and 30 days ago for default date range
        $endDate = Carbon::now()->format('Y-m-d');
        $startDate = Carbon::now()->subDays(30)->format('Y-m-d');
        
        return view('admin.reports.generate-report', compact('startDate', 'endDate'));
    }

    /**
     * Get appointment data for the report.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAppointmentData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'startDate' => 'required|date_format:Y-m-d',
            'endDate' => 'required|date_format:Y-m-d|after_or_equal:startDate',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Format dates for query
        $startDate = Carbon::parse($request->startDate)->startOfDay();
        $endDate = Carbon::parse($request->endDate)->endOfDay();
        
        // Obtener el usuario actual para aplicar filtros según el rol
        $user = auth()->user();
        
        // Iniciar la consulta - solo incluir citas con estado "Completado"
        $query = Appointment::with('user')
            ->whereBetween('date', [$startDate, $endDate])
            ->where('status', 'Completado'); // Solo mostrar citas completadas
            
        // Aplicar filtros según el rol del usuario
        if ($user->role === 'doctor') {
            // Si es doctor, solo mostrar sus propias citas
            $doctorAppointmentGroups = Appointment::where('user_id', $user->id)
                ->whereNotNull('appointment_group_id')
                ->pluck('appointment_group_id')
                ->toArray();
                
            $query->whereIn('appointment_group_id', $doctorAppointmentGroups);
        }
        
        // Solo incluir citas de pacientes (evita duplicados doctor-paciente)
        $query->whereHas('user', function($q) {
            $q->where('role', 'paciente');
        });
        
        // Obtener las citas filtradas y formatearlas
        $appointments = $query->orderBy('date')->get()
            ->map(function ($appointment) {
                return [
                    'id' => $appointment->id,
                    'patient_name' => $appointment->user->name,
                    'date' => Carbon::parse($appointment->date)->format('d/m/Y'),
                    'subject' => $appointment->subject,
                    'status' => $appointment->status,
                    'modality' => $appointment->modality,
                    'price' => number_format($appointment->price, 2)
                ];
            });

        // Calculate statistics - solo citas completadas para las estadísticas
        $totalAppointments = $appointments->count();
        $totalIncome = Appointment::whereBetween('date', [$startDate, $endDate])
            ->where('status', 'Completado');
            
        // Filtrar ingresos por doctor si es necesario
        if ($user->role === 'doctor') {
            $doctorAppointmentGroups = Appointment::where('user_id', $user->id)
                ->whereNotNull('appointment_group_id')
                ->pluck('appointment_group_id')
                ->toArray();
                
            $totalIncome->whereIn('appointment_group_id', $doctorAppointmentGroups);
        }
        
        // Solo citas de pacientes para el cálculo de ingresos
        $totalIncome = $totalIncome->whereHas('user', function($q) {
            $q->where('role', 'paciente');
        })->sum('price');

        return response()->json([
            'success' => true,
            'data' => [
                'appointments' => $appointments,
                'stats' => [
                    'total' => $totalAppointments,
                    'completed' => $totalAppointments, // Todas las citas son completadas ahora
                    'canceled' => 0, // No mostramos citas canceladas
                    'pending' => 0, // No mostramos citas pendientes
                    'totalIncome' => number_format($totalIncome, 2)
                ],
                'dateRange' => [
                    'start' => $startDate->format('d/m/Y'),
                    'end' => $endDate->format('d/m/Y')
                ]
            ]
        ]);
    }



}
