<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments = Appointment::with('user')->get();
        return response()->json($appointments);
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'user_id' => 'required|exists:users,id',
            'subject' => 'required|string|max:255',
            'status' => 'required|in:Solicitado,Agendado,Completado,Cancelado',
            'modality' => 'required|in:Consultorio,Domicilio',
            'price' => 'required|numeric|min:0'
        ]);

        $appointment = Appointment::create($request->all());
        return response()->json($appointment, 201);
    }

    public function show(Appointment $appointment)
    {
        return response()->json($appointment->load('user'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        $request->validate([
            'date' => 'date',
            'user_id' => 'exists:users,id',
            'subject' => 'string|max:255',
            'status' => 'in:Solicitado,Agendado,Completado,Cancelado',
            'modality' => 'in:Consultorio,Domicilio',
            'price' => 'numeric|min:0'
        ]);

        $appointment->update($request->all());
        return response()->json($appointment);
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();
        return response()->json(null, 204);
    }
}