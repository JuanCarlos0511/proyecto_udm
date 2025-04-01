<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index()
    {
        $patients = Patient::all();
        return response()->json($patients);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'age' => 'required|integer|min:0|max:120',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
        ]);

        $patient = Patient::create($request->all());
        return response()->json($patient, 201);
    }

    public function show(Patient $patient)
    {
        return response()->json($patient);
    }

    public function update(Request $request, Patient $patient)
    {
        $request->validate([
            'name' => 'string|max:255',
            'age' => 'integer|min:0|max:120',
            'phone' => 'string|max:20',
            'address' => 'string|max:255',
        ]);

        $patient->update($request->all());
        return response()->json($patient);
    }

    public function destroy(Patient $patient)
    {
        $patient->delete();
        return response()->json(null, 204);
    }
}