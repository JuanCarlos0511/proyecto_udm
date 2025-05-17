<?php

namespace App\Http\Controllers;

use App\Models\FollowUp;
use App\Models\User;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FollowUpController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role === 'doctor') {
            // Si es doctor, obtener solo sus seguimientos
            $followUps = FollowUp::with('user')
                            ->byUser($user->id)
                            ->get()
                            ->groupBy('follow_up_group_id');
        } elseif ($user->role === 'admin') {
            // Si es admin, obtener todos los seguimientos agrupados por grupo
            $followUps = FollowUp::with('user')
                            ->byUserRole('doctor')
                            ->get()
                            ->groupBy('follow_up_group_id');
        } else {
            // Si es paciente, obtener solo sus seguimientos
            $followUps = FollowUp::with('user')
                            ->byUser($user->id)
                            ->get()
                            ->groupBy('follow_up_group_id');
        }
        
        return view('admin.follow-ups.index', compact('followUps'));
    }
    
    /**
     * Display a listing of the doctors following the authenticated patient.
     *
     * @return \Illuminate\Http\Response
     */
    public function myDoctors()
    {
        $user = Auth::user();
        
        if ($user->role !== 'paciente') {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Solo los pacientes pueden acceder a esta sección');
        }
        
        // Obtener todos los grupos de seguimiento donde el paciente es miembro
        $patientFollowUps = FollowUp::where('user_id', $user->id)
            ->where('status', 'active')
            ->pluck('follow_up_group_id')
            ->toArray();
            
        // Obtener todos los doctores asociados a esos grupos
        $followUps = FollowUp::whereIn('follow_up_group_id', $patientFollowUps)
            ->with('user')
            ->byUserRole('doctor')
            ->get();
            
        return view('follow-ups.my-doctors', compact('followUps'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();
        
        if ($user->role !== 'doctor' && $user->role !== 'admin') {
            return redirect()->route('profile')
                ->with('error', 'No tienes permiso para crear seguimientos');
        }
        
        $patients = User::where('role', 'paciente')->get();
        
        return view('admin.follow-ups.create', compact('patients'));
    }
    
    /**
     * Show the form for creating a new follow-up for a specific patient.
     *
     * @param  int  $patientId
     * @return \Illuminate\Http\Response
     */
    public function createForPatient($patientId)
    {
        $user = Auth::user();
        
        if ($user->role !== 'doctor' && $user->role !== 'admin') {
            return redirect()->route('profile')
                ->with('error', 'No tienes permiso para crear seguimientos');
        }
        
        $patient = User::where('id', $patientId)
            ->where('role', 'paciente')
            ->firstOrFail();
        
        return view('admin.follow-ups.create-for-patient', compact('patient'));
    }

    /**
     * Almacenar un nuevo seguimiento
     */
    public function store(Request $request)
    {
        // NOTA: Temporalmente desactivada la autenticación estricta para desarrollo local
        // En producción, descomentar estas líneas para habilitar la verificación de roles
        $user = Auth::user();
        
        // Verificar si hay un usuario autenticado
        if (!$user) {
            return response()->json(['error' => 'Usuario no autenticado'], 401);
        }
        
        // Log para depuración
        \Log::info('Solicitud de creación de seguimiento:', [
            'user_id' => $user->id,
            'user_role' => $user->role,
            'request_data' => $request->all()
        ]);
        
        /* Comentado temporalmente para desarrollo local
        if ($user->role !== 'doctor' && $user->role !== 'admin') {
            return response()->json(['error' => 'Acceso denegado'], 403);
        }
        */

        $validator = Validator::make($request->all(), [
            'patient_id' => 'required|exists:users,id',
            'notes' => 'required|string|max:1000',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'doctor_id' => 'nullable|exists:users,id,role,doctor',
            'next_appointment' => 'nullable|date|after_or_equal:start_date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Determinar el doctor_id a usar
        $doctorId = $request->doctor_id;
        
        // Si no se proporcionó un doctor_id y el usuario es un doctor, usar el ID del usuario
        if (empty($doctorId) && $user->role === 'doctor') {
            $doctorId = $user->id;
        }
        
        // Si aún no tenemos un doctor_id válido, devolver error
        if (empty($doctorId)) {
            return response()->json(['error' => 'Se requiere seleccionar un doctor'], 422);
        }

        // Verificar si ya existe un seguimiento activo para este doctor y paciente
        // Necesitamos verificar si ambos pertenecen al mismo grupo de seguimiento
        $doctorGroups = FollowUp::where('user_id', $doctorId)
            ->where('status', 'active')
            ->pluck('follow_up_group_id')
            ->toArray();
        
        $existingFollowUp = false;
        if (!empty($doctorGroups)) {
            $existingFollowUp = FollowUp::whereIn('follow_up_group_id', $doctorGroups)
                ->where('user_id', $request->patient_id)
                ->where('status', 'active')
                ->exists();
        }

        if ($existingFollowUp) {
            return response()->json([
                'error' => 'Ya existe un seguimiento activo para este paciente con el doctor seleccionado'
            ], 422);
        }

        try {
            // Iniciar una transacción para asegurar que ambos registros se guardan o ninguno
            DB::beginTransaction();
            
            // Generar un nuevo ID de grupo de seguimiento (usando timestamp para garantizar unicidad)
            $groupId = time() . rand(1000, 9999);
            
            // Datos comunes para ambos registros
            $commonData = [
                'notes' => $request->notes,
                'status' => $request->status ?? 'active',
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            
            // Crear registro para el doctor
            $doctorFollowUp = new FollowUp();
            $doctorFollowUp->follow_up_group_id = $groupId;
            $doctorFollowUp->user_id = $doctorId;
            $doctorFollowUp->notes = $commonData['notes'];
            $doctorFollowUp->status = $commonData['status'];
            $doctorFollowUp->start_date = $commonData['start_date'];
            $doctorFollowUp->end_date = $commonData['end_date'];
            $doctorFollowUp->save();
            
            // Crear registro para el paciente
            $patientFollowUp = new FollowUp();
            $patientFollowUp->follow_up_group_id = $groupId;
            $patientFollowUp->user_id = $request->patient_id;
            $patientFollowUp->notes = $commonData['notes'];
            $patientFollowUp->status = $commonData['status'];
            $patientFollowUp->start_date = $commonData['start_date'];
            $patientFollowUp->end_date = $commonData['end_date'];
            $patientFollowUp->save();
            
            // Si se proporcionó una fecha para la próxima cita, crear una cita
            if ($request->has('next_appointment') && !empty($request->next_appointment)) {
                // 1. Primero crear el grupo de citas en la tabla appointment_groups
                $appointmentGroup = new \App\Models\AppointmentGroup();
                $appointmentGroup->name = 'Seguimiento de ' . $request->notes;
                $appointmentGroup->description = 'Creado automáticamente para el seguimiento con ID de grupo: ' . $groupId;
                $appointmentGroup->save();
                
                // Obtener el ID del grupo de citas recién creado
                $appointmentGroupId = $appointmentGroup->id;
                
                // Datos básicos para ambas citas
                // Combinar la fecha y la hora de la próxima cita
                $appointmentDate = $request->next_appointment;
                $appointmentTime = $request->appointment_time ?? '09:00';
                
                // Asegurar formato correcto
                $appointmentDateTime = $appointmentDate . ' ' . $appointmentTime . ':00';
                
                $appointmentData = [
                    'date' => $appointmentDateTime,
                    'subject' => 'Seguimiento: ' . $request->notes,
                    'status' => 'Agendado',
                    'modality' => 'Consultorio',
                    'appointment_group_id' => $appointmentGroupId,
                    'price' => 0.00, // Precio estándar o predeterminado
                ];
                
                // 2. Crear cita para el paciente
                $patientAppointment = new \App\Models\Appointment();
                $patientAppointment->user_id = $request->patient_id;
                $patientAppointment->date = $appointmentData['date'];
                $patientAppointment->subject = $appointmentData['subject'];
                $patientAppointment->status = $appointmentData['status'];
                $patientAppointment->modality = $appointmentData['modality'];
                $patientAppointment->appointment_group_id = $appointmentData['appointment_group_id'];
                $patientAppointment->price = $appointmentData['price'];
                $patientAppointment->save();
                
                // 3. Crear cita para el doctor
                $doctorAppointment = new \App\Models\Appointment();
                $doctorAppointment->user_id = $doctorId;
                $doctorAppointment->date = $appointmentData['date'];
                $doctorAppointment->subject = $appointmentData['subject'];
                $doctorAppointment->status = $appointmentData['status'];
                $doctorAppointment->modality = $appointmentData['modality'];
                $doctorAppointment->appointment_group_id = $appointmentData['appointment_group_id'];
                $doctorAppointment->price = $appointmentData['price'];
                $doctorAppointment->save();
            }
            
            // Confirmar la transacción
            DB::commit();
            
            // Cargar los datos de usuarios relacionados
            $doctorFollowUp->load('user');
            $patientUser = User::find($request->patient_id);
            $doctorUser = User::find($doctorId);
            
            return response()->json([
                'success' => true,
                'message' => 'Seguimiento creado correctamente',
                'followUp' => $doctorFollowUp,
                'groupId' => $groupId,
                'patient' => $patientUser->only(['id', 'name', 'email']),
                'doctor' => $doctorUser->only(['id', 'name', 'email'])
            ]);
            
        } catch (\Exception $e) {
            // Si algo falla, revertir la transacción
            DB::rollBack();
            
            \Log::error('Error al crear seguimiento: ' . $e->getMessage());
            
            return response()->json([
                'error' => 'Error al crear el seguimiento: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FollowUp  $followUp
     * @return \Illuminate\Http\Response
     */
    public function show(FollowUp $followUp)
    {
        $user = Auth::user();
        
        // Verificar que el usuario tenga permiso para ver este seguimiento
        if ($user->role === 'doctor' && $followUp->doctor_id !== $user->id) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'No tienes permiso para ver este seguimiento');
        }
        
        if ($user->role === 'paciente' && $followUp->patient_id !== $user->id) {
            return redirect('/seguimiento')
                ->with('error', 'No tienes permiso para ver este seguimiento');
        }
        
        // Obtener citas relacionadas con este seguimiento
        $appointments = Appointment::where('doctor_id', $followUp->doctor_id)
            ->where('patient_id', $followUp->patient_id)
            ->where(function($query) use ($followUp) {
                $query->whereDate('date', '>=', $followUp->start_date);
                if ($followUp->end_date) {
                    $query->whereDate('date', '<=', $followUp->end_date);
                }
            })
            ->orderBy('date', 'desc')
            ->get();
        
        return view('admin.follow-ups.show', compact('followUp', 'appointments'));
    }
    
    /**
     * Display the specified follow-up for patients in the public layout.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showPublic($id)
    {
        $user = Auth::user();
        
        if ($user->role !== 'paciente') {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Solo los pacientes pueden acceder a esta sección');
        }
        
        $followUp = FollowUp::where('id', $id)
            ->where('patient_id', $user->id)
            ->with(['doctor', 'patient'])
            ->firstOrFail();
        
        // Obtener citas relacionadas con este seguimiento
        $appointments = Appointment::where('doctor_id', $followUp->doctor_id)
            ->where('patient_id', $followUp->patient_id)
            ->where(function($query) use ($followUp) {
                $query->whereDate('date', '>=', $followUp->start_date);
                if ($followUp->end_date) {
                    $query->whereDate('date', '<=', $followUp->end_date);
                }
            })
            ->orderBy('date', 'desc')
            ->get();
        
        return view('follow-ups.show', compact('followUp', 'appointments'));
    }

    /**
     * Mostrar el formulario para editar un seguimiento
     */
    public function edit($groupId)
    {
        $user = Auth::user();
        
        // Buscar el grupo de seguimiento
        $followUps = FollowUp::where('follow_up_group_id', $groupId)->get();
        
        if ($followUps->isEmpty()) {
            return redirect()->back()->with('error', 'El grupo de seguimiento no existe');
        }
        
        // Para doctores, verificar si es miembro del grupo
        if ($user->role === 'doctor') {
            $isDoctor = $followUps->contains('user_id', $user->id);
            
            if (!$isDoctor && $user->role !== 'admin') {
                return redirect()->back()->with('error', 'No tienes permiso para editar este seguimiento');
            }
        }
        
        // Obtener el primer seguimiento como principal para mostrar datos generales
        $followUp = $followUps->first();
        $groupMembers = $followUps->pluck('user_id')->toArray();

        return view('admin.follow-ups.edit', compact('followUp', 'followUps', 'groupMembers'));
    }

    /**
     * Actualizar un seguimiento
     */
    public function update(Request $request, $groupId)
    {
        $user = Auth::user();
        
        // Obtener todos los seguimientos del grupo
        $followUps = FollowUp::where('follow_up_group_id', $groupId)->get();
        
        if ($followUps->isEmpty()) {
            return response()->json(['error' => 'Grupo de seguimiento no encontrado'], 404);
        }
        
        // Para doctores, verificar si es miembro del grupo
        if ($user->role === 'doctor') {
            $isDoctor = $followUps->contains('user_id', $user->id);
            
            if (!$isDoctor && $user->role !== 'admin') {
                return response()->json(['error' => 'Acceso denegado'], 403);
            }
        }

        $validator = Validator::make($request->all(), [
            'notes' => 'nullable|string|max:1000',
            'status' => 'required|in:active,inactive,completed',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        // Actualizar todos los seguimientos del grupo
        foreach ($followUps as $followUp) {
            $followUp->notes = $request->notes;
            $followUp->status = $request->status;
            $followUp->end_date = $request->end_date;
            $followUp->save();
        }

        // Obtener el primer elemento para la respuesta
        $mainFollowUp = $followUps->first()->load('user');

        return response()->json([
            'success' => true,
            'message' => 'Seguimiento actualizado correctamente',
            'followUp' => $mainFollowUp
        ]);
    }

    /**
     * Eliminar un seguimiento
     */
    public function destroy($groupId)
    {
        $user = Auth::user();
        
        // Obtener todos los seguimientos del grupo
        $followUps = FollowUp::where('follow_up_group_id', $groupId)->get();
        
        if ($followUps->isEmpty()) {
            return response()->json(['error' => 'Grupo de seguimiento no encontrado'], 404);
        }
        
        // Para doctores, verificar si es miembro del grupo
        if ($user->role === 'doctor') {
            $isDoctor = $followUps->contains('user_id', $user->id);
            
            if (!$isDoctor && $user->role !== 'admin') {
                return response()->json(['error' => 'Acceso denegado'], 403);
            }
        }

        // Eliminar todos los seguimientos del grupo
        FollowUp::where('follow_up_group_id', $groupId)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Grupo de seguimiento eliminado correctamente'
        ]);
    }

    /**
     * Obtener los seguimientos para la API
     */
    public function getFollowUpsForDoctor()
    {
        $user = Auth::user();
        
        // Verificar si el usuario es un doctor
        if ($user->role !== 'doctor') {
            return response()->json(['error' => 'Acceso denegado'], 403);
        }

        // Obtener los grupos de seguimiento donde el doctor es miembro
        $followUpGroups = FollowUp::where('user_id', $user->id)
            ->pluck('follow_up_group_id')
            ->toArray();
            
        // Obtener información de los pacientes asociados a estos grupos
        $patientFollowUps = FollowUp::whereIn('follow_up_group_id', $followUpGroups)
            ->with('user')
            ->byUserRole('paciente')
            ->orderBy('status')
            ->orderBy('start_date', 'desc')
            ->get()
            ->groupBy('follow_up_group_id');

        return response()->json(['data' => $patientFollowUps]);
    }

    /**
     * Obtener los doctores que siguen al paciente para la API
     */
    public function getMyDoctors()
    {
        $user = Auth::user();
        
        // Obtener los grupos de seguimiento donde el paciente es miembro
        $followUpGroups = FollowUp::where('user_id', $user->id)
            ->where('status', 'active')
            ->pluck('follow_up_group_id')
            ->toArray();
            
        // Obtener información de los doctores asociados a estos grupos
        $doctorFollowUps = FollowUp::whereIn('follow_up_group_id', $followUpGroups)
            ->with('user')
            ->byUserRole('doctor')
            ->active()
            ->orderBy('start_date', 'desc')
            ->get();

        return response()->json(['data' => $doctorFollowUps]);
    }

    /**
     * Obtener las citas filtradas por doctores que siguen al paciente
     */
    public function getAppointmentsWithFollowUpDoctors()
    {
        $user = Auth::user();
        
        // Obtener los grupos de seguimiento donde el paciente es miembro
        $followUpGroups = FollowUp::where('user_id', $user->id)
            ->where('status', 'active')
            ->pluck('follow_up_group_id')
            ->toArray();
            
        // Obtener los IDs de los doctores asociados a estos grupos
        $doctorIds = FollowUp::whereIn('follow_up_group_id', $followUpGroups)
            ->where('status', 'active')
            ->byUserRole('doctor')
            ->join('users', 'users.id', '=', 'follow_ups.user_id')
            ->pluck('users.id')
            ->unique()
            ->toArray();

        // Obtener las citas con esos doctores
        $appointments = $user->appointments()
            ->whereIn('doctor_id', $doctorIds)
            ->with('doctor')
            ->orderBy('date', 'desc')
            ->get();

        return response()->json(['data' => $appointments]);
    }
}
