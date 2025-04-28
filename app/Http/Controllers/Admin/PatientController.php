<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;

class PatientController extends Controller
{
    /**
     * Display a listing of the patients.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $patients = User::where('role', 'paciente')->get();
        return view('admin.patients.index', compact('patients'));
    }

    /**
     * Show the form for creating a new patient.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.patients.create');
    }

    /**
     * Store a newly created patient in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'age' => 'required|integer',
            'phoneNumber' => 'required|string|max:20',
            'adress' => 'nullable|string|max:255',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'emergency_contact_relationship' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $patient = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'age' => $request->age,
            'role' => 'paciente',
            'phoneNumber' => $request->phoneNumber,
            'adress' => $request->adress,
            'status' => 'active',
            'emergency_contact_name' => $request->emergency_contact_name,
            'emergency_contact_phone' => $request->emergency_contact_phone,
            'emergency_contact_relationship' => $request->emergency_contact_relationship,
        ]);

        return redirect()->route('admin.patients.index')
            ->with('success', 'Paciente creado exitosamente.');
    }

    /**
     * Display the specified patient.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $patient = User::findOrFail($id);
        $appointments = Appointment::where('user_id', $id)
            ->orderBy('date', 'desc')
            ->get();
            
        return view('admin.patients.show', compact('patient', 'appointments'));
    }

    /**
     * Show the form for editing the specified patient.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $patient = User::findOrFail($id);
        return view('admin.patients.edit', compact('patient'));
    }

    /**
     * Update the specified patient in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $patient = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $patient->id,
            'age' => 'required|integer',
            'phoneNumber' => 'required|string|max:20',
            'adress' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'emergency_contact_relationship' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $patient->update([
            'name' => $request->name,
            'email' => $request->email,
            'age' => $request->age,
            'phoneNumber' => $request->phoneNumber,
            'adress' => $request->adress,
            'status' => $request->status,
            'emergency_contact_name' => $request->emergency_contact_name,
            'emergency_contact_phone' => $request->emergency_contact_phone,
            'emergency_contact_relationship' => $request->emergency_contact_relationship,
        ]);

        // Update password if provided
        if ($request->filled('password')) {
            $patient->update([
                'password' => Hash::make($request->password),
            ]);
        }

        return redirect()->route('admin.patients.index')
            ->with('success', 'Paciente actualizado exitosamente.');
    }

    /**
     * Remove the specified patient from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $patient = User::findOrFail($id);
        $patient->delete();

        return redirect()->route('admin.patients.index')
            ->with('success', 'Paciente eliminado exitosamente.');
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
            
        return view('admin.patients.followup', compact('patients'));
    }

    /**
     * Add profile information for a patient.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function addProfileInfo($id)
    {
        $patient = User::findOrFail($id);
        return view('admin.patients.add-profile-info', compact('patient'));
    }

    /**
     * Update profile information for a patient.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProfileInfo(Request $request, $id)
    {
        $patient = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'emergency_contact_relationship' => 'nullable|string|max:100',
            'adress' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $patient->update([
            'emergency_contact_name' => $request->emergency_contact_name,
            'emergency_contact_phone' => $request->emergency_contact_phone,
            'emergency_contact_relationship' => $request->emergency_contact_relationship,
            'adress' => $request->adress,
        ]);

        return redirect()->route('admin.patients.show', $patient->id)
            ->with('success', 'Información de perfil actualizada exitosamente.');
    }

    /**
     * Get patients data for AJAX requests.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPatientsData()
    {
        $patients = User::where('role', 'paciente')
            ->get()
            ->map(function ($patient) {
                return [
                    'id' => $patient->id,
                    'name' => $patient->name,
                    'email' => $patient->email,
                    'age' => $patient->age,
                    'phoneNumber' => $patient->phoneNumber,
                    'status' => $patient->status,
                    'created_at' => Carbon::parse($patient->created_at)->format('d M, Y'),
                ];
            });
        
        return response()->json([
            'patients' => $patients
        ]);
    }
}
