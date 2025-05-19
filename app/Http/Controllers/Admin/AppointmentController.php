<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Helpers\TimeHelper;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the appointments.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = auth()->user();
        
        Log::info('Consultando citas con usuario: ' . $user->name . ', rol: ' . $user->role);
        
        // Si el usuario es un administrador, mostrar TODAS las citas sin filtros
        if ($user->role === 'admin') {
            Log::info('Iniciando consulta para administrador...');
            
            // Para depurar, primero verificamos cuántas citas existen en total
            $totalAppointments = Appointment::count();
            Log::info('Total de citas en la base de datos: ' . $totalAppointments);
            
            // Verificar cuántos usuarios tipo paciente hay
            $patientCount = User::where('role', 'paciente')->count();
            Log::info('Total de usuarios con rol paciente: ' . $patientCount);
            
            // Consulta simplificada para administrador: mostrar todas las citas
            $query = Appointment::with('user')
                ->orderBy('date', 'desc');
                
            $appointments = $query->get();
            
            Log::info('Admin: mostrando todas las citas. Total: ' . $appointments->count());
        } 
        // Si el usuario es un doctor, solo mostrar sus citas (donde es el doctor asignado)
        else if ($user->role === 'doctor') {
            Log::info('Doctor ID: ' . $user->id);
            
            // Primero obtenemos los grupos de citas donde este doctor está asignado
            $doctorAppointmentGroups = Appointment::where('user_id', $user->id)
                ->whereNotNull('appointment_group_id')
                ->pluck('appointment_group_id')
                ->toArray();
                
            Log::info('Grupos de citas del doctor: ', $doctorAppointmentGroups);
            
            // Ahora obtenemos las citas de pacientes que pertenecen a estos grupos
            $query = Appointment::with(['user', 'relatedAppointments'])
                ->whereIn('appointment_group_id', $doctorAppointmentGroups)
                ->whereHas('user', function($query) {
                    $query->where('role', 'paciente');
                })
                ->orderBy('date', 'desc');
                
            $appointments = $query->get();
            
            Log::info('Doctor: mostrando solo citas asignadas. Total: ' . $appointments->count());
        }
        // Para otros roles (pacientes), no mostrar citas
        else {
            $appointments = collect([]);
            Log::info('Otro rol: no mostrando citas');
        }
        
        // Transformar los registros para añadir el timeToHuman
        $appointments->transform(function ($appointment) {
            $appointment->timeToHuman = TimeHelper::timeToHuman($appointment->date);
            return $appointment;
        });

        // Obtener la lista de doctores para el formulario de edición incluyendo al admin
        $doctors = User::where(function($query) {
            $query->where('role', 'doctor')
                  ->orWhere('role', 'admin'); // Incluir al admin como doctor elegible
        })->get();
        
        return view('admin.appointments.all-appointments', compact('appointments', 'doctors'));
    }
    
    /**
     * Nueva función que muestra solo las citas de pacientes, evitando duplicados.
     * Solo muestra las citas donde el user_id corresponde a un usuario con rol paciente.
     *
     * @return \Illuminate\View\View
     */
    public function patientOnlyAppointments()
    {
        $user = auth()->user();
        
        Log::info('Consultando citas de pacientes con usuario: ' . $user->name . ', rol: ' . $user->role);
        
        // Si el usuario es un administrador, mostrar todas las citas de PACIENTES y aquellas donde él es el doctor asignado
        if ($user->role === 'admin') {
            Log::info('Iniciando consulta para administrador...');
            
            // Obtenemos primero los grupos de citas donde el admin es el doctor asignado
            $adminDoctorAppointmentGroups = Appointment::where('user_id', $user->id)
                ->whereNotNull('appointment_group_id')
                ->pluck('appointment_group_id')
                ->toArray();
                
            Log::info('Grupos de citas donde el admin es doctor: ', ['count' => count($adminDoctorAppointmentGroups)]);
            
            // Consulta filtrada para mostrar:
            // 1. Citas donde el usuario es un paciente
            // 2. O citas de pacientes donde el admin es el doctor asignado (mediante el grupo)
            $query = Appointment::with(['user', 'relatedAppointments'])
                ->where(function($query) use ($adminDoctorAppointmentGroups) {
                    $query->whereHas('user', function($subquery) {
                        $subquery->where('role', 'paciente');
                    });
                    
                    // Incluir citas de pacientes donde el admin es el doctor
                    if (!empty($adminDoctorAppointmentGroups)) {
                        $query->orWhere(function($subquery) use ($adminDoctorAppointmentGroups) {
                            $subquery->whereIn('appointment_group_id', $adminDoctorAppointmentGroups)
                                    ->whereHas('user', function($userQuery) {
                                        $userQuery->where('role', 'paciente');
                                    });
                        });
                    }
                })
                ->orderBy('date', 'desc');
                
            $appointments = $query->get();
            
            Log::info('Admin: mostrando citas de pacientes y propias. Total: ' . $appointments->count());
        } 
        // Si el usuario es un doctor, solo mostrar sus citas (donde es el doctor asignado)
        else if ($user->role === 'doctor') {
            Log::info('Doctor ID: ' . $user->id);
            
            // Primero obtenemos los grupos de citas donde este doctor está asignado
            $doctorAppointmentGroups = Appointment::where('user_id', $user->id)
                ->whereNotNull('appointment_group_id')
                ->pluck('appointment_group_id')
                ->toArray();
                
            Log::info('Grupos de citas del doctor: ', $doctorAppointmentGroups);
            
            // Ahora obtenemos las citas de pacientes que pertenecen a estos grupos
            $query = Appointment::with(['user', 'relatedAppointments'])
                ->whereIn('appointment_group_id', $doctorAppointmentGroups)
                ->whereHas('user', function($query) {
                    $query->where('role', 'paciente');
                })
                ->orderBy('date', 'desc');
                
            $appointments = $query->get();
            
            Log::info('Doctor: mostrando solo citas asignadas (de pacientes). Total: ' . $appointments->count());
        }
        // Para otros roles (pacientes), no mostrar citas
        else {
            $appointments = collect([]);
            Log::info('Otro rol: no mostrando citas');
        }
        
        // Transformar los registros para añadir el timeToHuman
        $appointments->transform(function ($appointment) {
            $appointment->timeToHuman = TimeHelper::timeToHuman($appointment->date);
            return $appointment;
        });

        // Obtener la lista de doctores para el formulario de edición incluyendo al admin
        $doctors = User::where(function($query) {
            $query->where('role', 'doctor')
                  ->orWhere('role', 'admin'); // Incluir al admin como doctor elegible
        })->get();
        
        return view('admin.appointments.all-appointments', compact('appointments', 'doctors'));
    }

    /**
     * Show the form for creating a new appointment.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $doctors = User::where('role', 'doctor')->get();
        $patients = User::where('role', 'paciente')->get();
        
        return view('admin.appointments.create', compact('doctors', 'patients'));
    }
    
    /**
     * Obtener los datos de una cita para el modal de inicio.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showStartData($id)
    {
        try {
            // Buscar la cita con el usuario asociado
            $appointment = Appointment::with('user')->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'appointment' => $appointment
            ]);
        } catch (\Exception $e) {
            Log::error('Error al obtener detalles de la cita: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar los datos de la cita: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created appointment in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'user_id' => 'required|exists:users,id',
            'subject' => 'required|string|max:255',
            'status' => 'required|in:Solicitado,Agendado,Completado,Cancelado',
            'modality' => 'required|in:Consultorio,Domicilio',
            'price' => 'required|numeric|min:0',
            'diagnosis' => 'nullable|string',
            'referred_by' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $appointment = Appointment::create([
            'date' => $request->date,
            'user_id' => $request->user_id,
            'subject' => $request->subject,
            'status' => $request->status,
            'modality' => $request->modality,
            'price' => $request->price,
            'diagnosis' => $request->diagnosis,
            'referred_by' => $request->referred_by,
        ]);

        return redirect()->route('admin.appointments.index')
            ->with('success', 'Cita creada exitosamente.');
    }

    /**
     * Display the specified appointment.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $appointment = Appointment::with('user')->findOrFail($id);
        return view('admin.appointments.show', compact('appointment'));
    }

    /**
     * Show the form for editing the specified appointment.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $appointment = Appointment::findOrFail($id);
        $patients = User::where('role', 'paciente')->where('status', 'active')->get();
        return view('admin.appointments.edit', compact('appointment', 'patients'));
    }

    /**
     * Update the specified appointment in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);
        $isAjax = $request->ajax() || $request->wantsJson();
        
        // Si es una solicitud para completar la cita (desde la página de detalles)
        if ($request->has('status') && $request->status === 'Completado') {
            $validator = Validator::make($request->all(), [
                'diagnosis' => 'nullable|string',
                'notes' => 'nullable|string',
                'price' => 'nullable|numeric|min:0',
                'status' => 'required|in:Solicitado,Agendado,Completado,Cancelado',
            ]);
        } else {
            // Validación para una actualización completa de cita
            $validator = Validator::make($request->all(), [
                'date' => 'nullable|date',
                'user_id' => 'nullable|exists:users,id',
                'subject' => 'nullable|string|max:255',
                'status' => 'nullable|in:Solicitado,Agendado,Completado,Cancelado',
                'modality' => 'nullable|in:Consultorio,Domicilio',
                'price' => 'nullable|numeric|min:0',
                'diagnosis' => 'nullable|string',
                'notes' => 'nullable|string',
                'referred_by' => 'nullable|string|max:255',
            ]);
        }

        if ($validator->fails()) {
            // Si es una solicitud AJAX, devolver errores en formato JSON
            if ($isAjax) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Actualizar solo los campos proporcionados en la solicitud
        $fieldsToUpdate = $request->only([
            'date', 'user_id', 'subject', 'status', 'modality', 'price', 
            'diagnosis', 'notes', 'referred_by'
        ]);
        
        // Filtrar solo los campos que tienen valores (no null)
        $fieldsToUpdate = array_filter($fieldsToUpdate, function ($value) {
            return $value !== null;
        });
        
        $appointment->update($fieldsToUpdate);
        
        // Log de la actualización
        Log::info('Cita actualizada', [
            'appointment_id' => $appointment->id,
            'fields_updated' => array_keys($fieldsToUpdate),
            'new_status' => $appointment->status
        ]);

        // Si es una solicitud AJAX, devolver respuesta JSON
        if ($isAjax) {
            return response()->json([
                'success' => true,
                'message' => 'Cita actualizada exitosamente',
                'appointment' => $appointment
            ]);
        }

        return redirect()->route('admin.appointments.index')
            ->with('success', 'Cita actualizada exitosamente.');
    }

    /**
     * Remove the specified appointment from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->delete();

        return redirect()->route('admin.appointments.index')
            ->with('success', 'Cita eliminada exitosamente.');
    }

    /**
     * Display the appointment history.
     *
     * @return \Illuminate\View\View
     */
    public function history()
    {
        $user = auth()->user();
        
        // Query base para citas pasadas
        $query = Appointment::with('user')
            ->where('date', '<', Carbon::today())
            ->orderBy('date', 'desc');
            
        // Si es doctor, filtrar solo sus propias citas
        if ($user->role === 'doctor') {
            $query->where('user_id', $user->id);
        }
        
        $appointments = $query->get();
        
        // Agregar el tiempo en formato legible
        $appointments->transform(function ($appointment) {
            $appointment->timeToHuman = TimeHelper::timeToHuman($appointment->date);
            return $appointment;
        });
            
        return view('admin.appointments.history', compact('appointments'));
    }

    /**
     * Display the patients in follow-up.
     *
     * @return \Illuminate\View\View
     */
    public function patientsInFollowUp()
    {
        $user = auth()->user();
        
        if ($user->role === 'doctor') {
            // Para doctores, buscar pacientes que tengan seguimiento con este doctor
            $followUpGroups = FollowUp::where('status', 'active')
                ->byUser($user->id)
                ->pluck('follow_up_group_id');
                
            // Encontrar pacientes que estén en esos grupos de seguimiento
            $patientFollowUps = FollowUp::whereIn('follow_up_group_id', $followUpGroups)
                ->byUserRole('paciente')
                ->with('user')
                ->get()
                ->pluck('user_id')
                ->unique();
                
            $patients = User::whereIn('id', $patientFollowUps)->get();
        } else {
            // Para administradores, mostrar todos los pacientes en seguimiento
            $patientFollowUps = FollowUp::where('status', 'active')
                ->byUserRole('paciente')
                ->with('user')
                ->get()
                ->pluck('user_id')
                ->unique();
                
            $patients = User::whereIn('id', $patientFollowUps)->get();
        }
        
        // Enriquecemos la información con las citas asociadas a estos pacientes
        $patients->load(['appointments' => function($query) {
            $query->where('status', 'Completado')
                  ->orderBy('date', 'desc');
        }]);
        
        return view('admin.appointments.patients-followup', compact('patients'));
    }

    /**
     * Create a clinic appointment.
     *
     * @return \Illuminate\View\View
     */
    public function createClinicAppointment()
    {
        $patients = User::where('role', 'paciente')->where('status', 'active')->get();
        return view('admin.appointments.create-clinic', compact('patients'));
    }

    /**
     * Create a home appointment.
     *
     * @return \Illuminate\View\View
     */
    public function createHomeAppointment()
    {
        $patients = User::where('role', 'paciente')->where('status', 'active')->get();
        return view('admin.appointments.create-home', compact('patients'));
    }
    
    /**
     * Get appointments data for AJAX requests.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAppointmentsData()
    {
        $user = auth()->user();
        
        // Consulta base para citas
        $query = Appointment::with('user')
            ->orderBy('date', 'desc');
            
        // Si el usuario es un doctor, filtrar solo sus citas
        if ($user->role === 'doctor') {
            $query->where('user_id', $user->id);
        }
        
        $appointments = $query->get()
            ->map(function ($appointment) {
                return [
                    'id' => $appointment->id,
                    'patient_name' => $appointment->user->name,
                    'date' => Carbon::parse($appointment->date)->format('d M, Y'),
                    'timeToHuman' => TimeHelper::timeToHuman($appointment->date),
                    'subject' => $appointment->subject,
                    'status' => $appointment->status,
                    'modality' => $appointment->modality,
                    'price' => $appointment->price,
                ];
            });
        
        return response()->json([
            'appointments' => $appointments
        ]);
    }

    /**
     * Accept an appointment.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function accept($id)
    {
        DB::beginTransaction();
        try {
            $appointment = Appointment::findOrFail($id);
            
            // Solo permitir aceptar citas que estén en estado 'Solicitado'
            if ($appointment->status !== 'Solicitado') {
                return response()->json([
                    'message' => 'Solo se pueden aceptar citas en estado Solicitado'
                ], 400);
            }
            
            // Obtener el grupo de citas
            $groupId = $appointment->appointment_group_id;
            
            if (!$groupId) {
                return response()->json([
                    'message' => 'La cita no tiene un grupo asignado'
                ], 400);
            }
            
            // Actualizar el estado de todas las citas en el mismo grupo
            Appointment::where('appointment_group_id', $groupId)
                ->update(['status' => 'Agendado']);
            
            DB::commit();
            
            // Recargar la cita para obtener el estado actualizado
            $appointment->refresh();
            
            return response()->json([
                'message' => 'Cita aceptada exitosamente',
                'appointment' => $appointment
            ]);
            
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error al aceptar la cita: ' . $e->getMessage());
            
            return response()->json([
                'message' => 'Error al aceptar la cita: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cancel an appointment.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancel($id)
    {
        try {
            $appointment = Appointment::findOrFail($id);
            
            if (!$appointment->canBeCancelled()) {
                return response()->json([
                    'message' => 'Solo se pueden cancelar citas en estado ' . 
                                Appointment::STATUS_REQUESTED . ' o ' . 
                                Appointment::STATUS_SCHEDULED
                ], 400);
            }
            
            if ($appointment->cancel()) {
                return response()->json([
                    'message' => 'Cita cancelada exitosamente',
                    'appointment' => $appointment
                ]);
            } else {
                return response()->json([
                    'message' => 'No se pudo cancelar la cita'
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al cancelar la cita: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Update appointment from the appointments view.
     * This method handles PUT requests to /admin/tablero/citas-todas
     * Implementa la lógica de negocio donde:
     * - Cuando se asigna un doctor a una cita sin doctor, se crea un nuevo registro para el doctor
     * - Cuando se cambia el doctor de una cita, se actualiza el registro existente del doctor
     * - Cuando se elimina el doctor de una cita, se elimina el registro del doctor
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateFromView(Request $request)
    {
        // Iniciar transacción para garantizar consistencia de datos
        DB::beginTransaction();
        
        try {
            Log::info('Iniciando actualización de cita desde el buzón', [
                'request_data' => $request->all()
            ]);
            
            // Obtener el ID de la cita desde la solicitud
            $appointmentId = $request->input('appointment_id');
            
            if (!$appointmentId) {
                return response()->json([
                    'message' => 'Se requiere el ID de la cita'
                ], 400);
            }
            
            // Buscar la cita del paciente
            $patientAppointment = Appointment::with('user')->findOrFail($appointmentId);
            
            // Validar los datos recibidos - incluir todos los campos editables
            $validator = Validator::make($request->all(), [
                'date' => 'required|date', // Validar que sea una fecha válida en formato ISO
                'doctor_id' => 'nullable|exists:users,id',
                'subject' => 'nullable|string|max:255',
                'modality' => 'nullable|in:Consultorio,Domicilio',
                'price' => 'nullable|numeric|min:0',
            ]);

            if ($validator->fails()) {
                Log::error('Error de validación en actualización de cita', [
                    'errors' => $validator->errors()->toArray()
                ]);
                
                return response()->json([
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            // Confirmar si es una cita de paciente (importante para la lógica)
            $isPatientAppointment = $patientAppointment->user->role === 'paciente';
            
            if (!$isPatientAppointment) {
                // Si intentamos actualizar la cita del doctor directamente, encontrar la cita del paciente
                $patientAppointment = Appointment::where('appointment_group_id', $patientAppointment->appointment_group_id)
                    ->whereHas('user', function($query) {
                        $query->where('role', 'paciente');
                    })->first();
                
                if (!$patientAppointment) {
                    return response()->json([
                        'message' => 'No se encontró la cita del paciente asociada'
                    ], 404);
                }
            }
            
            // Guardar información original para referencia
            $oldGroupId = $patientAppointment->appointment_group_id;
            
            // ID del doctor seleccionado en el formulario (puede ser null o cadena vacía si se eliminó la asignación)
            $selectedDoctorId = $request->doctor_id;
            
            // Buscar si hay una cita de doctor asociada actualmente
            $currentDoctorAppointment = null;
            if ($oldGroupId) {
                $currentDoctorAppointment = Appointment::where('appointment_group_id', $oldGroupId)
                    ->whereHas('user', function($query) {
                        $query->whereIn('role', ['doctor', 'admin']);
                    })->first();
            }
            
            // Actualizar la cita del paciente con todos los campos editables
            // Asegurarse de que la fecha incluya la hora
            $date = Carbon::parse($request->date);
            if (!$date->hour && !$date->minute) {
                Log::warning('La fecha no incluye hora, usando la hora actual como respaldo');
                $date->setTime(now()->hour, now()->minute);
            }
            $patientAppointment->date = $date;
            
            // Actualizar campos opcionales si están presentes
            if ($request->has('subject')) {
                $patientAppointment->subject = $request->subject;
            }
            
            if ($request->has('modality')) {
                $patientAppointment->modality = $request->modality;
            }
            
            if ($request->has('price')) {
                $patientAppointment->price = $request->price;
            }
            
            // CASO 1: Si no existe un grupo de citas y hay un doctor seleccionado, crear uno nuevo
            if (!$oldGroupId && $selectedDoctorId) {
                // Crear un nuevo grupo de citas
                $appointmentGroup = new \App\Models\AppointmentGroup;
                $appointmentGroup->save();
                
                $patientAppointment->appointment_group_id = $appointmentGroup->id;
                Log::info('Creando nuevo grupo de citas para nuevo doctor', [
                    'group_id' => $appointmentGroup->id,
                    'doctor_id' => $selectedDoctorId
                ]);
            }
            
            // Guardar los cambios en la cita del paciente
            $patientAppointment->save();
            
            // CASO 2: Si estamos quitando la asignación de doctor
            if ($currentDoctorAppointment && empty($selectedDoctorId)) {
                Log::info('Eliminando la asignación de doctor', [
                    'doctor_appointment_id' => $currentDoctorAppointment->id,
                    'doctor_id' => $currentDoctorAppointment->user_id
                ]);
                
                // Eliminar la cita del doctor
                $currentDoctorAppointment->delete();
            }
            // CASO 3: Si hay un doctor seleccionado, actualizar o crear su cita
            else if ($selectedDoctorId) {
                $currentGroupId = $patientAppointment->appointment_group_id;
                
                // CASO 3A: Si estamos cambiando de doctor y existe una cita para el doctor anterior
                if ($currentDoctorAppointment && $currentDoctorAppointment->user_id != $selectedDoctorId) {
                    Log::info('Cambiando el doctor asignado', [
                        'old_doctor_id' => $currentDoctorAppointment->user_id,
                        'new_doctor_id' => $selectedDoctorId
                    ]);
                    
                    // Eliminar la cita del doctor anterior
                    $currentDoctorAppointment->delete();
                    $currentDoctorAppointment = null; // Marcar como eliminado para crear nuevo
                }
                
                // Verificar si ya existe una cita para este doctor en el mismo grupo de citas
                $doctorAppointment = null;
                
                if ($currentGroupId) {
                    $doctorAppointment = Appointment::where('appointment_group_id', $currentGroupId)
                        ->where('user_id', $selectedDoctorId)
                        ->first();
                }
                
                if ($doctorAppointment) {
                    // CASO 3B: Actualizar cita existente del doctor
                    $doctorAppointment->date = $request->date;
                    
                    // Actualizar campos opcionales si están presentes
                    if ($request->has('subject')) {
                        $doctorAppointment->subject = $request->subject;
                    }
                    
                    if ($request->has('modality')) {
                        $doctorAppointment->modality = $request->modality;
                    }
                    
                    if ($request->has('price')) {
                        $doctorAppointment->price = $request->price;
                    }
                    
                    $doctorAppointment->save();
                    
                    Log::info('Se actualizó la cita existente del doctor', [
                        'patient_appointment_id' => $patientAppointment->id,
                        'doctor_appointment_id' => $doctorAppointment->id,
                        'doctor_id' => $selectedDoctorId,
                        'group_id' => $currentGroupId
                    ]);
                } else {
                    // CASO 3C: Crear una nueva cita para el doctor
                    $doctorAppointment = new Appointment();
                    $doctorAppointment->date = $request->date;
                    $doctorAppointment->user_id = $selectedDoctorId; // El doctor es el usuario de esta cita
                    $doctorAppointment->subject = $request->has('subject') ? $request->subject : $patientAppointment->subject;
                    $doctorAppointment->status = $patientAppointment->status;
                    $doctorAppointment->modality = $request->has('modality') ? $request->modality : $patientAppointment->modality;
                    $doctorAppointment->price = $request->has('price') ? $request->price : $patientAppointment->price;
                    $doctorAppointment->appointment_group_id = $currentGroupId; // Asignar al mismo grupo
                    $doctorAppointment->save();
                    
                    Log::info('Se creó una nueva cita para el doctor', [
                        'patient_appointment_id' => $patientAppointment->id,
                        'doctor_appointment_id' => $doctorAppointment->id,
                        'doctor_id' => $selectedDoctorId,
                        'group_id' => $currentGroupId
                    ]);
                }
            }
            
            DB::commit();
            
            return response()->json([
                'message' => 'Cita actualizada exitosamente',
                'appointment' => $patientAppointment
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            
            Log::error('Error al actualizar la cita: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'message' => 'Error al actualizar la cita: ' . $e->getMessage()
            ], 500);
        }
    }
}
