<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
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
        
        // Si es administrador, mostrar todas las citas
        // Si es doctor, mostrar solo las citas asociadas a pacientes que ha atendido
        if ($user->role === 'administrador') {
            $appointments = Appointment::with(['user', 'doctor'])->orderBy('date', 'desc')->get();
            $appointments->transform(function ($appointment) {
                $appointment->timeToHuman = TimeHelper::timeToHuman($appointment->date);
                return $appointment;
            });
        } else { // doctor
            // Para los doctores, podemos filtrar por citas que tengan un subject o diagnosis relacionado con su especialidad
            // O simplemente mostrar todas las citas para que puedan ver la agenda general
            $appointments = Appointment::with(['user', 'doctor'])
                ->orderBy('date', 'desc')
                ->get();
            $appointments->transform(function ($appointment) {
                $appointment->timeToHuman = TimeHelper::timeToHuman($appointment->date);
                return $appointment;
            });
        }

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
}
