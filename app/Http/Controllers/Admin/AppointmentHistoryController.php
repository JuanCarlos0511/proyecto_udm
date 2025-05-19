<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AppointmentHistoryController extends Controller
{
    /**
     * Display the appointment history view.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        try {
            // Verificar que el usuario esté autenticado
            if (!auth()->check()) {
                return redirect()->route('login')->with('error', 'Debe iniciar sesión para acceder a esta página');
            }
            
            $user = auth()->user();
            
            // Establecer fechas predeterminadas: del 1 de enero al 31 de diciembre de 2025
            $defaultStartDate = $request->filled('date_from') ? $request->date_from : '2025-01-01';
            $defaultEndDate = $request->filled('date_to') ? $request->date_to : '2025-12-31';
            
            // Verificar que el usuario tenga permisos (doctor o admin)
            if (!$user || !in_array($user->role, ['doctor', 'admin'])) {
                // Si no es doctor o admin, redirigir al inicio
                return redirect()->route('home')->with('error', 'No tiene permisos para acceder a esta página. Se requiere rol de doctor o administrador.');
            }
            
            // Inicializar variables por defecto (esto garantiza que siempre existan en la vista)
            $stats = [
                'total_month' => 0,
                'pending' => 0
            ];
            $doctors = collect([]);
            
            // Inicializar $appointments como una colección vacía con método links para evitar errores
            $appointments = new \Illuminate\Pagination\LengthAwarePaginator(
                collect([]), // items vacíos
                0,           // total items
                10,          // por página
                1            // página actual
            );
            
            // Inicializar y ejecutar la consulta principal para obtener citas
            try {
                // Primero verificar cuántas citas existen en total (para diagnóstico)
                $totalAppointments = Appointment::count();
                $totalPatients = User::where('role', 'paciente')->count();
                $totalDoctors = User::where('role', 'doctor')->count();
                
                Log::info('=== DIAGNÓSTICO HISTORIAL DE CITAS ===');
                Log::info('Total de citas en la base de datos: ' . $totalAppointments);
                Log::info('Total de pacientes: ' . $totalPatients);
                Log::info('Total de doctores: ' . $totalDoctors);
                
                // Filtrar por fechas
                $startDate = Carbon::parse($defaultStartDate)->startOfDay();
                $endDate = Carbon::parse($defaultEndDate)->endOfDay();
                
                Log::info('Filtrando citas desde ' . $startDate->format('Y-m-d') . ' hasta ' . $endDate->format('Y-m-d'));
                
                // CONSULTA SIMPLIFICADA: mostrar TODAS las citas sin filtrado por fecha
                // Para admin: mostrar todas las citas
                if ($user->role === 'admin') {
                    Log::info('Consultando TODAS las citas para admin');
                    
                    // Consulta simplificada sin filtrar por fecha inicialmente
                    $query = Appointment::with(['user'])
                        ->orderBy('date', 'desc');
                } 
                // Para doctor: solo mostrar sus citas
                elseif ($user->role === 'doctor') {
                    Log::info('Consultando TODAS las citas para doctor ID: ' . $user->id);
                    
                    // Obtener IDs de grupos de citas donde este doctor está asignado
                    $doctorAppointmentGroups = Appointment::where('user_id', $user->id)
                        ->whereNotNull('appointment_group_id')
                        ->pluck('appointment_group_id')
                        ->toArray();
                    
                    Log::info('Grupos de citas del doctor: ' . json_encode($doctorAppointmentGroups));
                    
                    if (empty($doctorAppointmentGroups)) {
                        Log::warning('No se encontraron grupos de citas para este doctor');
                        // Si el doctor no tiene grupos asignados, mostrar consulta vacía
                        $query = Appointment::where('id', 0);
                    } else {
                        // Obtener citas de pacientes que pertenecen a estos grupos
                        $query = Appointment::with(['user'])
                            ->whereIn('appointment_group_id', $doctorAppointmentGroups)
                            ->orderBy('date', 'desc');
                    }
                }
            } catch (\Exception $e) {
                Log::error('Error al construir la consulta: ' . $e->getMessage());
                // Mantenemos el $appointments vacío definido anteriormente
                $query = Appointment::where('id', 0); // Consulta vacía
            }

            // La lógica de filtrado por rol ya está implementada arriba

            // Aplicar filtros si existen
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('date_from')) {
                $query->whereDate('date', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->whereDate('date', '<=', $request->date_to);
            }

            if ($request->filled('doctor_id') && $user->role === 'admin') {
                // Solo permitir filtrar por doctor si el usuario es admin
                $doctorAppointments = Appointment::where('user_id', $request->doctor_id)
                    ->pluck('appointment_group_id');
                $query->whereIn('appointment_group_id', $doctorAppointments);
            }

            // Ejecutar la consulta de forma simplificada
            $query->limit(50); // Limitar a 50 registros para diagnóstico
            $rawAppointments = $query->get(); // Obtener sin paginación para diagnóstico
            
            // Registrar citas encontradas para diagnóstico
            Log::info('Total de citas encontradas (sin paginación): ' . $rawAppointments->count());
            
            if ($rawAppointments->count() > 0) {
                $firstAppointment = $rawAppointments->first();
                Log::info('Primera cita encontrada:');
                Log::info(' - ID: ' . $firstAppointment->id);
                Log::info(' - Fecha: ' . $firstAppointment->date);
                Log::info(' - Asunto: ' . $firstAppointment->subject);
                Log::info(' - Estado: ' . $firstAppointment->status);
                Log::info(' - Modalidad: ' . $firstAppointment->modality);
                Log::info(' - Precio: ' . $firstAppointment->price);
                
                if ($firstAppointment->user) {
                    Log::info(' - Paciente ID: ' . $firstAppointment->user->id);
                    Log::info(' - Paciente Nombre: ' . $firstAppointment->user->name);
                } else {
                    Log::warning(' - No se encontró el usuario asociado a la cita');
                }
            }
            
            // Aplicar paginación para la vista
            $appointments = $query->paginate(10);
            Log::info('Total de citas encontradas (con paginación): ' . $appointments->total());
            
            // Si no hay citas, intentar una consulta alternativa para diagnosticar el problema
            if ($appointments->total() === 0) {
                $totalCitas = Appointment::count();
                $totalPacientes = User::where('role', 'paciente')->count();
                $totalDoctores = User::where('role', 'doctor')->count();
                
                Log::info('Diagnóstico - Total general de citas: ' . $totalCitas);
                Log::info('Diagnóstico - Total de pacientes: ' . $totalPacientes);
                Log::info('Diagnóstico - Total de doctores: ' . $totalDoctores);
                
                // Si hay citas en la base de datos pero no se muestran, intentar una consulta más simple
                if ($totalCitas > 0) {
                    Log::info('Intentando consulta alternativa...');
                    
                    // Consulta más simple para admin
                    if ($user->role === 'admin') {
                        $appointments = Appointment::with('user')
                            ->orderBy('date', 'desc')
                            ->paginate(10);
                            
                        Log::info('Citas encontradas con consulta alternativa: ' . $appointments->total());
                    }
                }
            }

            try {
                // Clonar la query para las estadísticas
                $statsQuery = clone $query;
                
                // Obtener estadísticas del mes actual
                $currentMonth = Carbon::now();
                $stats['total_month'] = $statsQuery->whereMonth('date', $currentMonth->month)
                                                  ->whereYear('date', $currentMonth->year)
                                                  ->count();

                // Obtener citas pendientes
                $pendingQuery = Appointment::query();
                
                if ($user->role === 'doctor') {
                    $doctorAppointmentGroups = Appointment::where('user_id', $user->id)
                        ->whereNotNull('appointment_group_id')
                        ->pluck('appointment_group_id');
                    $pendingQuery->whereIn('appointment_group_id', $doctorAppointmentGroups);
                }

                $stats['pending'] = $pendingQuery->where(function($q) {
                    $q->where('status', 'Solicitado')
                      ->orWhere('status', 'Agendado');
                })->count();

            } catch (\Exception $e) {
                Log::error('Error calculando estadísticas: ' . $e->getMessage());
            }

            // Obtener lista de doctores para el filtro (solo si es admin)
            if ($user->role === 'admin') {
                try {
                    $doctors = User::whereIn('role', ['doctor', 'admin'])->get();
                } catch (\Exception $e) {
                    Log::error('Error cargando lista de doctores: ' . $e->getMessage());
                    $doctors = collect([]);
                }
            }

            return view('admin.appointments.appointment-history', compact('appointments', 'stats', 'doctors', 'defaultStartDate', 'defaultEndDate'));

        } catch (\Exception $e) {
            Log::error('Error en historial de citas: ' . $e->getMessage());
            return back()->with('error', 'Hubo un error al cargar el historial de citas.');
        }
    }
}
