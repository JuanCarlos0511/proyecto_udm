<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class DoctorController extends Controller
{
    /**
     * Display a listing of the doctors.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $doctors = User::where('role', 'doctor')->get();
        return view('admin.doctors.doctors-list', compact('doctors'));
    }

    /**
     * Show the form for creating a new doctor.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.doctors.create');
    }

    /**
     * Store a newly created doctor in storage.
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
            'age' => 'required|integer|min:18',
            'phoneNumber' => 'required|string|max:20',
            'adress' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $doctor = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'age' => $request->age,
            'role' => 'doctor',
            'phoneNumber' => $request->phoneNumber,
            'adress' => $request->adress,
            'status' => 'active',
        ]);

        return redirect()->route('admin.doctors.index')
            ->with('success', 'Doctor creado exitosamente.');
    }

    /**
     * Display the specified doctor.
     *
     * @param  int  $id
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $doctor = User::findOrFail($id);
        
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'doctor' => $doctor
            ]);
        }
        
        return view('admin.doctors.show', compact('doctor'));
    }

    /**
     * Show the form for editing the specified doctor.
     *
     * @param  int  $id
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse
     */
    public function edit($id)
    {
        $doctor = User::findOrFail($id);
        
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'doctor' => $doctor
            ]);
        }
        
        return view('admin.doctors.edit', compact('doctor'));
    }

    /**
     * Update the specified doctor in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $doctor = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $doctor->id,
            'phoneNumber' => 'required|string|max:20',
            'status' => 'required|in:active,inactive',
            'password' => 'nullable|string|min:8',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'phoneNumber' => $request->phoneNumber,
            'status' => $request->status,
        ];
        
        // Actualizar campos opcionales si se proporcionan
        if ($request->filled('adress')) {
            $updateData['adress'] = $request->adress;
        }
        
        if ($request->filled('age')) {
            $updateData['age'] = $request->age;
        }
        
        $doctor->update($updateData);

        // Update password if provided
        if ($request->filled('password')) {
            $doctor->update([
                'password' => Hash::make($request->password),
            ]);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Doctor actualizado exitosamente.',
                'doctor' => $doctor
            ]);
        }

        return redirect()->route('admin.doctors.index')
            ->with('success', 'Doctor actualizado exitosamente.');
    }

    /**
     * Remove the specified doctor from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $doctor = User::findOrFail($id);
            $doctorName = $doctor->name;
            $doctor->delete();
            
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "Doctor {$doctorName} eliminado exitosamente."
                ]);
            }
    
            return redirect()->route('admin.doctors.index')
                ->with('success', 'Doctor eliminado exitosamente.');
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al eliminar el doctor: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->route('admin.doctors.index')
                ->with('error', 'Error al eliminar el doctor: ' . $e->getMessage());
        }
    }

    /**
     * Get doctors data for AJAX requests.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDoctorsData()
    {
        $doctors = User::where('role', 'doctor')->get();
        
        return response()->json([
            'doctors' => $doctors
        ]);
    }
}
