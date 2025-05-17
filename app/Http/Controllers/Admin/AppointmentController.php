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
        
        // Obtener solo las citas de los pacientes (no las de los doctores)
        // Verificamos que los registros pertenezcan a usuarios con role = 'paciente'
        $appointments = Appointment::with('user')
            ->whereHas('user', function($query) {
                $query->where('role', 'paciente');
            })
            ->orderBy('date', 'desc')
            ->get();
            
        Log::info('Citas encontradas: ' . $appointments->count());
        
        // Transformar los registros para añadir el timeToHuman
        $appointments->transform(function ($appointment) {
            $appointment->timeToHuman = TimeHelper::timeToHuman($appointment->date);
            return $appointment;
        });

        // Obtener la lista de doctores para el formulario de edición
        $doctors = User::where('role', 'doctor')->get();
        
        return view('admin.appointments.all-appointments', compact('appointments', 'doctors'));
    }

    /**
     * Show the form for creating a new appointment.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $patients = User::where('role', 'paciente')->where('status', 'active')->get();
        return view('admin.appointments.create', compact('patients'));
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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);

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

        $appointment->update([
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
        
        // Si es administrador, mostrar todas las citas pasadas
        // Si es doctor, mostrar todas las citas pasadas (agenda general)
        if ($user->role === 'administrador') {
            $appointments = Appointment::with('user')
                ->where('date', '<', Carbon::today())
                ->orderBy('date', 'desc')
                ->get();
        } else { // doctor
            $appointments = Appointment::with('user')
                ->where('date', '<', Carbon::today())
                ->orderBy('date', 'desc')
                ->get();
        }
        
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
        $patients = User::where('role', 'paciente')
            ->whereHas('appointments', function ($query) {
                $query->where('date', '<=', Carbon::now())
                      ->where('status', 'Completado');
            })
            ->with(['appointments' => function ($query) {
                $query->where('status', 'Completado')
                      ->orderBy('date', 'desc');
            }])
            ->get();
            
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
        $appointments = Appointment::with('user')
            ->orderBy('date', 'desc')
            ->get()
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
        try {
            $appointment = Appointment::findOrFail($id);
            
            if (!$appointment->canBeAccepted()) {
                return response()->json([
                    'message' => 'Solo se pueden aceptar citas en estado ' . Appointment::STATUS_REQUESTED
                ], 400);
            }
            
            if ($appointment->accept()) {
                return response()->json([
                    'message' => 'Cita aceptada exitosamente',
                    'appointment' => $appointment
                ]);
            } else {
                return response()->json([
                    'message' => 'No se pudo aceptar la cita'
                ], 500);
            }
        } catch (\Exception $e) {
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
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateFromView(Request $request)
    {
        // Iniciar transacción para garantizar consistencia de datos
        DB::beginTransaction();
        
        try {
            // Obtener el ID de la cita desde la solicitud
            $appointmentId = $request->input('appointment_id');
            
            if (!$appointmentId) {
                return response()->json([
                    'message' => 'Se requiere el ID de la cita'
                ], 400);
            }
            
            // Buscar la cita del paciente
            $patientAppointment = Appointment::with('user')->findOrFail($appointmentId);
            
            // Validar los datos recibidos
            $validator = Validator::make($request->all(), [
                'date' => 'required|string',
                'doctor_id' => 'nullable|exists:users,id',
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            // Obtener los IDs relevantes
            $oldDate = $patientAppointment->date;
            $selectedDoctorId = $request->doctor_id; // ID del doctor seleccionado en el formulario
            
            // Actualizar la fecha de la cita del paciente
            $patientAppointment->date = $request->date;
            $patientAppointment->save();
            
            // Verificar si hay un doctor asignado en el formulario
            if ($selectedDoctorId) {
                // Verificar si ya existe una cita para este doctor en la misma fecha
                $doctorAppointment = Appointment::where('date', $oldDate)
                    ->where('user_id', $selectedDoctorId)
                    ->first();
                
                if ($doctorAppointment) {
                    // Actualizar la fecha de la cita existente del doctor
                    $doctorAppointment->date = $request->date;
                    $doctorAppointment->save();
                    
                    Log::info('Se actualizó la cita existente del doctor', [
                        'patient_appointment_id' => $patientAppointment->id,
                        'doctor_appointment_id' => $doctorAppointment->id,
                        'doctor_id' => $selectedDoctorId,
                        'new_date' => $request->date
                    ]);
                } else {
                    // Crear una nueva cita para el doctor
                    $doctorAppointment = new Appointment();
                    $doctorAppointment->date = $request->date;
                    $doctorAppointment->user_id = $selectedDoctorId; // El doctor es el usuario de esta cita
                    $doctorAppointment->subject = $patientAppointment->subject;
                    $doctorAppointment->status = $patientAppointment->status;
                    $doctorAppointment->modality = $patientAppointment->modality;
                    $doctorAppointment->price = $patientAppointment->price;
                    $doctorAppointment->save();
                    
                    Log::info('Se creó una nueva cita para el doctor', [
                        'patient_appointment_id' => $patientAppointment->id,
                        'doctor_appointment_id' => $doctorAppointment->id,
                        'doctor_id' => $selectedDoctorId
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
