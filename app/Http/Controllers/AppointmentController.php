<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments = Appointment::with(['patient', 'doctor'])->get();
        return response()->json($appointments);
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'subject' => 'required|string|max:255',
            'status' => 'required|in:scheduled,completed,cancelled',
            'modality' => 'required|in:in-person,home-visit',
        ]);

        $appointment = Appointment::create($request->all());
        return response()->json($appointment, 201);
    }

    public function show(Appointment $appointment)
    {
        return response()->json($appointment->load(['patient', 'doctor']));
    }

    public function update(Request $request, Appointment $appointment)
    {
        $request->validate([
            'date' => 'date',
            'patient_id' => 'exists:patients,id',
            'doctor_id' => 'exists:doctors,id',
            'subject' => 'string|max:255',
            'status' => 'in:scheduled,completed,cancelled',
            'modality' => 'in:in-person,home-visit',
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