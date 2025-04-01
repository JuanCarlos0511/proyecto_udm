<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function index()
    {
        $doctors = Doctor::all();
        return response()->json($doctors);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'specialty' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $doctor = Doctor::create($request->all());
        return response()->json($doctor, 201);
    }

    public function show(Doctor $doctor)
    {
        return response()->json($doctor);
    }

    public function update(Request $request, Doctor $doctor)
    {
        $request->validate([
            'name' => 'string|max:255',
            'specialty' => 'string|max:255',
            'phone' => 'string|max:20',
            'email' => 'email|max:255',
            'status' => 'in:active,inactive',
        ]);

        $doctor->update($request->all());
        return response()->json($doctor);
    }

    public function destroy(Doctor $doctor)
    {
        $doctor->delete();
        return response()->json(null, 204);
    }
}