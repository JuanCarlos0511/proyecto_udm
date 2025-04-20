<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments = Appointment::with('user')->get();
        return response()->json($appointments);
    }

    public function create()
    {
        $user = Auth::user();
        return view('appointment-clinic', compact('user'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        
        try {
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

            // If user is authenticated, use their ID
            if (Auth::check()) {
                $user = Auth::user();
                // Update user's emergency contact info if provided
                $user->update([
                    'emergency_contact_name' => $userData['user']['emergency_contact_name'],
                    'emergency_contact_phone' => $userData['user']['emergency_contact_phone'],
                    'emergency_contact_relationship' => $userData['user']['emergency_contact_relationship']
                ]);
            } else {
                // Check if user exists
                $user = User::where('email', $userData['user']['email'])->first();

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
                }
            }

            // Validate appointment data
            $appointmentData = $request->validate([
                'date' => 'required|date',
                'subject' => 'required|string|max:255',
                'status' => 'required|in:Solicitado,Agendado,Completado,Cancelado',
                'modality' => 'required|in:Consultorio,Domicilio',
                'price' => 'required|numeric|min:0',
                'diagnosis' => 'nullable|string|max:255',
                'referred_by' => 'nullable|string|max:255'
            ]);

            // Create appointment
            $appointment = new Appointment($appointmentData);
            $appointment->user_id = $user->id;
            $appointment->save();

            DB::commit();

            return response()->json([
                'message' => 'Cita agendada exitosamente',
                'appointment' => $appointment->load('user')
            ], 201);

        } catch (\Exception $e) {
            DB::rollback();
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
            'date' => 'date',
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
        return response()->json($appointments);
    }

    public function getByStatus($status)
    {
        $appointments = Appointment::where('status', $status)->with('user')->get();
        return response()->json($appointments);
    }
}