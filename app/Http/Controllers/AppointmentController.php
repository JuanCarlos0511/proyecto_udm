<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $appointments = Appointment::with('user')->get();
        return response()->json($appointments);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'user_id' => 'required|exists:users,id',
            'subject' => 'required|string|max:255',
            'status' => 'required|in:Solicitado,Agendado,Completado,Cancelado',
            'modality' => 'required|in:Consultorio,Domicilio',
            'price' => 'required|numeric|min:0',
        ]);

        $appointment = Appointment::create($validated);
        return response()->json($appointment, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Appointment $appointment)
    {
        return response()->json($appointment->load('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'date' => 'date',
            'user_id' => 'exists:users,id',
            'subject' => 'string|max:255',
            'status' => 'in:Solicitado,Agendado,Completado,Cancelado',
            'modality' => 'in:Consultorio,Domicilio',
            'price' => 'numeric|min:0',
        ]);

        $appointment->update($validated);
        return response()->json($appointment);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appointment $appointment)
    {
        $appointment->delete();
        return response()->json(null, 204);
    }

    /**
     * Get appointments for a specific user.
     */
    public function getByUser(User $user)
    {
        $appointments = Appointment::where('user_id', $user->id)->with('user')->get();
        return response()->json($appointments);
    }

    /**
     * Get appointments by status.
     */
    public function getByStatus($status)
    {
        $appointments = Appointment::where('status', $status)->with('user')->get();
        return response()->json($appointments);
    }
}