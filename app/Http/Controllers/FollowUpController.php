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
            $followUps = FollowUp::where('doctor_id', $user->id)->with('patient')->get();
        } elseif ($user->role === 'admin') {
            $followUps = FollowUp::with(['doctor', 'patient'])->get();
        } else {
            $followUps = FollowUp::where('patient_id', $user->id)->with('doctor')->get();
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
        
        $followUps = FollowUp::where('patient_id', $user->id)
            ->where('status', 'active')
            ->with('doctor')
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
        $existingFollowUp = FollowUp::where('doctor_id', $doctorId)
            ->where('patient_id', $request->patient_id)
            ->where('status', 'active')
            ->first();

        if ($existingFollowUp) {
            return response()->json([
                'error' => 'Ya existe un seguimiento activo para este paciente con el doctor seleccionado'
            ], 422);
        }

        // Crear el nuevo seguimiento
        $followUp = new FollowUp();
        $followUp->doctor_id = $doctorId;
        $followUp->patient_id = $request->patient_id;
        $followUp->notes = $request->notes;
        $followUp->status = $request->status ?? 'active';
        $followUp->start_date = $request->start_date;
        $followUp->end_date = $request->end_date;
        $followUp->save();
        
        // Si se proporcionó una fecha para la próxima cita, crear una cita
        if ($request->has('next_appointment') && !empty($request->next_appointment)) {
            try {
                $appointment = new \App\Models\Appointment();
                $appointment->patient_id = $request->patient_id;
                $appointment->doctor_id = $doctorId;
                $appointment->date = $request->next_appointment;
                $appointment->time = '09:00:00'; // Hora predeterminada
                $appointment->modality = 'consultorio';
                $appointment->status = 'scheduled';
                $appointment->subject = 'Seguimiento: ' . $request->notes;
                $appointment->save();
            } catch (\Exception $e) {
                // Registrar el error pero continuar
                \Log::error('Error al crear cita de seguimiento: ' . $e->getMessage());
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Seguimiento creado correctamente',
            'followUp' => $followUp->load(['patient', 'doctor']),
            'admin_id' => $user->id,
            'doctor_id' => $doctorId,
            'patient_id' => $request->patient_id
        ]);
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
    public function edit($id)
    {
        $followUp = FollowUp::findOrFail($id);

        // Verificar si el usuario es el doctor que creó el seguimiento
        if (Auth::user()->role !== 'doctor' || $followUp->doctor_id !== Auth::id()) {
            return redirect()->back()->with('error', 'No tienes permiso para editar este seguimiento');
        }

        return view('admin.follow-ups.edit', compact('followUp'));
    }

    /**
     * Actualizar un seguimiento
     */
    public function update(Request $request, $id)
    {
        $followUp = FollowUp::findOrFail($id);

        // Verificar si el usuario es el doctor que creó el seguimiento
        if (Auth::user()->role !== 'doctor' || $followUp->doctor_id !== Auth::id()) {
            return response()->json(['error' => 'Acceso denegado'], 403);
        }

        $validator = Validator::make($request->all(), [
            'notes' => 'nullable|string|max:1000',
            'status' => 'required|in:active,inactive,completed',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $followUp->notes = $request->notes;
        $followUp->status = $request->status;
        $followUp->end_date = $request->end_date;
        $followUp->save();

        return response()->json([
            'success' => true,
            'message' => 'Seguimiento actualizado correctamente',
            'followUp' => $followUp->load('patient')
        ]);
    }

    /**
     * Eliminar un seguimiento
     */
    public function destroy($id)
    {
        $followUp = FollowUp::findOrFail($id);

        // Verificar si el usuario es el doctor que creó el seguimiento
        if (Auth::user()->role !== 'doctor' || $followUp->doctor_id !== Auth::id()) {
            return response()->json(['error' => 'Acceso denegado'], 403);
        }

        $followUp->delete();

        return response()->json([
            'success' => true,
            'message' => 'Seguimiento eliminado correctamente'
        ]);
    }

    /**
     * Obtener los seguimientos para la API
     */
    public function getFollowUpsForDoctor()
    {
        // Verificar si el usuario es un doctor
        if (Auth::user()->role !== 'doctor') {
            return response()->json(['error' => 'Acceso denegado'], 403);
        }

        $followUps = FollowUp::with('patient')
            ->byDoctor(Auth::id())
            ->orderBy('status')
            ->orderBy('start_date', 'desc')
            ->get();

        return response()->json(['data' => $followUps]);
    }

    /**
     * Obtener los doctores que siguen al paciente para la API
     */
    public function getMyDoctors()
    {
        $followUps = FollowUp::with('doctor')
            ->byPatient(Auth::id())
            ->active()
            ->orderBy('start_date', 'desc')
            ->get();

        return response()->json(['data' => $followUps]);
    }

    /**
     * Obtener las citas filtradas por doctores que siguen al paciente
     */
    public function getAppointmentsWithFollowUpDoctors()
    {
        // Obtener los IDs de los doctores que siguen al paciente
        $doctorIds = FollowUp::where('patient_id', Auth::id())
            ->where('status', 'active')
            ->pluck('doctor_id')
            ->toArray();

        // Obtener las citas con esos doctores
        $appointments = Auth::user()->appointments()
            ->whereIn('doctor_id', $doctorIds)
            ->with('doctor')
            ->orderBy('date', 'desc')
            ->get();

        return response()->json(['data' => $appointments]);
    }
}
