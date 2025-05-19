<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Helpers\TimeHelper;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        try {
            // Obtener el ID del usuario autenticado
            $userId = Auth::id();
            
            // Si no hay usuario autenticado, devolver error
            if (!$userId) {
                return response()->json([
                    'message' => 'Usuario no autenticado',
                    'data' => []
                ], 401);
            }
            
            // Filtrar citas por el usuario autenticado
            $query = Appointment::with('user')
                ->where('user_id', $userId);
            
            // Aplicar filtros adicionales si se proporcionan
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }
            
            $appointments = $query->get();
            
            // Registrar para depuración
            Log::info('Citas filtradas por usuario:', [
                'user_id' => $userId,
                'count' => $appointments->count()
            ]);
            
            // Agregar el tiempo en formato legible
            $appointments->transform(function ($appointment) {
                $appointment->timeToHuman = TimeHelper::timeToHuman($appointment->date);
                return $appointment;
            });

            return response()->json([
                'message' => 'Citas cargadas exitosamente',
                'data' => $appointments
            ]);

        } catch (\Exception $e) {
            Log::error('Error al cargar citas: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error al cargar las citas',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function create()
    {
        $user = Auth::user();
        
        // Fetch doctors (users with role 'doctor' or 'administrador')
        $doctors = User::whereIn('role', ['doctor', 'admin'])
                      ->where('status', 'active')
                      ->get(['id', 'name']);
        
        return view('appointment-clinic', compact('user', 'doctors'));
    }

    /**
     * Procesa el campo de fecha para asegurar que se guarde con el formato correcto incluyendo la hora
     * 
     * @param array $data Los datos de la cita
     * @return array Datos procesados con la fecha en formato correcto
     */
    private function processDateField($data)
    {
        if (isset($data['date'])) {
            // Verificar si la fecha ya tiene formato válido de datetime
            try {
                $parsedDate = \Carbon\Carbon::parse($data['date']);
                
                // Si la hora es 00:00:00, probablemente es porque solo se proporcionó una fecha sin hora
                // En ese caso, verificamos si hay campos de hora separados
                if ($parsedDate->format('H:i:s') === '00:00:00' && isset($data['time'])) {
                    // Extraer partes de fecha y hora
                    $date = $parsedDate->format('Y-m-d');
                    $time = $data['time']; // Formato esperado: HH:MM o HH:MM:SS
                    
                    // Combinar fecha y hora
                    $data['date'] = $date . ' ' . $time;
                } else if ($parsedDate->format('H:i:s') === '00:00:00' && isset($data['hour']) && isset($data['minute'])) {
                    // Alternativa: campos de hora y minuto separados
                    $date = $parsedDate->format('Y-m-d');
                    $time = sprintf('%02d:%02d:00', $data['hour'], $data['minute']);
                    
                    // Combinar fecha y hora
                    $data['date'] = $date . ' ' . $time;
                }
                
                // Registrar para depuración
                \Illuminate\Support\Facades\Log::info('Fecha procesada:', [
                    'original' => $data['date'],
                    'procesada' => \Carbon\Carbon::parse($data['date'])->format('Y-m-d H:i:s')
                ]);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Error al procesar fecha:', [
                    'error' => $e->getMessage(),
                    'fecha_original' => $data['date']
                ]);
            }
        }
        
        return $data;
    }
    
    public function store(Request $request)
    {
        DB::beginTransaction();
        
        try {
            // Log para depuración
            Log::info('Datos recibidos:', $request->all());
            
            // Validate user data
            $userData = $request->validate([
                'user.name' => 'required|string|max:255',
                'user.age' => 'required|integer|min:0',
                'user.email' => 'required|email|max:255',
                'user.phoneNumber' => 'required|string|max:20',
                'user.emergency_contact_name' => 'required|string|max:255',
                'user.emergency_contact_phone' => 'required|string|max:20',
                'user.emergency_contact_relationship' => 'required|string|max:255'
            ]);
            
            Log::info('Datos de usuario validados correctamente');

            // If user is authenticated, use their ID
            if (Auth::check()) {
                $user = Auth::user();
                Log::info('Usuario autenticado:', ['id' => $user->id]);
                // Update user's emergency contact info if provided
                $user->update([
                    'emergency_contact_name' => $userData['user']['emergency_contact_name'],
                    'emergency_contact_phone' => $userData['user']['emergency_contact_phone'],
                    'emergency_contact_relationship' => $userData['user']['emergency_contact_relationship']
                ]);
            } else {
                // Check if user exists
                $user = User::where('email', $userData['user']['email'])->first();
                Log::info('Buscando usuario por email:', ['email' => $userData['user']['email'], 'encontrado' => (bool)$user]);

                if (!$user) {
                    // Create new user
                    $user = User::create([
                        'name' => $userData['user']['name'],
                        'age' => $userData['user']['age'],
                        'email' => $userData['user']['email'],
                        'phoneNumber' => $userData['user']['phoneNumber'],
                        'emergency_contact_name' => $userData['user']['emergency_contact_name'],
                        'emergency_contact_phone' => $userData['user']['emergency_contact_phone'],
                        'emergency_contact_relationship' => $userData['user']['emergency_contact_relationship'],
                        'password' => Hash::make('temporal' . rand(1000, 9999)), // Temporary password
                        'role' => 'paciente',
                        'status' => 'active'
                    ]);
                    Log::info('Nuevo usuario creado:', ['id' => $user->id]);
                }
            }

            // Validate appointment data
            try {
                $appointmentData = $request->validate([
                    'date' => 'required|string',
                    'subject' => 'required|string|max:255',
                    'status' => 'required|in:Solicitado,Agendado,Completado,Cancelado',
                    'modality' => 'required|in:Consultorio,Domicilio',
                    'price' => 'required|numeric|min:0',
                    'diagnosis' => 'nullable|string|max:255',
                    'referred_by' => 'nullable|string|max:255',
                    'doctor_id' => 'nullable|exists:users,id'
                ]);
                Log::info('Datos de cita validados correctamente');
            } catch (\Illuminate\Validation\ValidationException $e) {
                Log::error('Error de validación de cita:', ['errors' => $e->errors()]);
                throw $e;
            }

            // Primero crear un grupo de citas si hay un doctor seleccionado
            $appointmentGroup = null;
            if (isset($appointmentData['doctor_id']) && !empty($appointmentData['doctor_id'])) {
                $appointmentGroup = new \App\Models\AppointmentGroup();
                $appointmentGroup->save();
                Log::info('Grupo de citas creado:', ['id' => $appointmentGroup->id]);
            }
            
            // Procesar la fecha correctamente
            $appointmentData = $this->processDateField($appointmentData);
            
            // Create appointment for the patient
            $appointment = new Appointment($appointmentData);
            $appointment->user_id = $user->id;
            
            // Asignar el grupo de citas si existe
            if ($appointmentGroup) {
                $appointment->appointment_group_id = $appointmentGroup->id;
            }
            
            $appointment->save();
            Log::info('Cita del paciente guardada correctamente:', ['id' => $appointment->id, 'grupo' => $appointment->appointment_group_id]);
            
            // If a doctor is selected, create a duplicate appointment for the doctor
            if (isset($appointmentData['doctor_id']) && !empty($appointmentData['doctor_id'])) {
                // Aseguramos que estamos usando los datos procesados correctamente
                $doctorAppointment = new Appointment($appointmentData);
                $doctorAppointment->user_id = $appointmentData['doctor_id']; // Use doctor's user_id
                $doctorAppointment->appointment_group_id = $appointmentGroup->id; // Asociar al mismo grupo
                $doctorAppointment->save();
                Log::info('Cita del doctor guardada correctamente:', ['id' => $doctorAppointment->id, 'grupo' => $doctorAppointment->appointment_group_id]);
            }

            DB::commit();

            return response()->json([
                'message' => 'Cita agendada exitosamente',
                'appointment' => $appointment->load('user')
            ], 201);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error al agendar cita:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json([
                'message' => 'Error al agendar la cita',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(Appointment $appointment)
    {
        return response()->json($appointment->load('user'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        $request->validate([
            'date' => 'string',
            'subject' => 'string|max:255',
            'status' => 'in:Solicitado,Agendado,Completado,Cancelado',
            'modality' => 'in:Consultorio,Domicilio',
            'price' => 'numeric|min:0',
            'diagnosis' => 'nullable|string|max:255',
            'referred_by' => 'nullable|string|max:255'
        ]);

        $appointment->update($request->all());
        return response()->json($appointment);
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();
        return response()->json(null, 204);
    }

    public function getByUser(User $user)
    {
        $appointments = $user->appointments;
        
        // Agregar el tiempo en formato legible
        $appointments->transform(function ($appointment) {
            $appointment->timeToHuman = TimeHelper::timeToHuman($appointment->date);
            return $appointment;
        });
        return response()->json($appointments);
    }

    public function getByStatus($status)
    {
        $appointments = Appointment::where('status', $status)->with('user')->get();
        
        // Agregar el tiempo en formato legible
        $appointments->transform(function ($appointment) {
            $appointment->timeToHuman = TimeHelper::timeToHuman($appointment->date);
            return $appointment;
        });
        return response()->json($appointments);
    }
}