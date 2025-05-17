<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FollowUp;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PatientFollowUpController extends Controller
{
    /**
     * Muestra los seguimientos del paciente autenticado
     */
    public function index()
    {
        // Obtener el usuario autenticado (paciente)
        $patient = Auth::user();
        
        // Verificar que sea un paciente
        if ($patient->role !== 'paciente') {
            return redirect()->route('home')->with('error', 'Acceso no autorizado.');
        }
        
        // Obtener los seguimientos donde el paciente está involucrado
        $followUps = FollowUp::where('user_id', $patient->id)
                            ->where('status', 'active')
                            ->orderBy('start_date', 'desc')
                            ->get();
        
        // Organizar los seguimientos por grupo para mostrar la información completa
        $followUpGroups = [];
        
        foreach ($followUps as $followUp) {
            $groupId = $followUp->follow_up_group_id;
            
            // Si es la primera vez que vemos este grupo, inicializarlo
            if (!isset($followUpGroups[$groupId])) {
                // Buscar al doctor de este grupo
                $doctorFollowUp = FollowUp::where('follow_up_group_id', $groupId)
                                        ->whereHas('user', function($query) {
                                            $query->where('role', 'doctor');
                                        })
                                        ->first();
                
                // Solo agregar si hay un doctor asociado
                if ($doctorFollowUp) {
                    $followUpGroups[$groupId] = [
                        'patient_followup' => $followUp,
                        'doctor_followup' => $doctorFollowUp,
                        'doctor' => $doctorFollowUp->user,
                        'treatment' => $followUp->notes,
                        'start_date' => Carbon::parse($followUp->start_date)->format('d/m/Y'),
                        'end_date' => $followUp->end_date ? Carbon::parse($followUp->end_date)->format('d/m/Y') : 'En curso',
                        'status' => $followUp->status,
                    ];
                    
                    // Buscar la próxima cita relacionada con este seguimiento
                    $nextAppointment = Appointment::where('user_id', $patient->id)
                                                ->whereHas('appointmentGroup', function($query) use ($followUp) {
                                                    $query->where('description', 'like', '%' . $followUp->follow_up_group_id . '%');
                                                })
                                                ->where('date', '>=', Carbon::now())
                                                ->orderBy('date', 'asc')
                                                ->first();
                    
                    if ($nextAppointment) {
                        $followUpGroups[$groupId]['next_appointment'] = [
                            'date' => Carbon::parse($nextAppointment->date)->format('d/m/Y'),
                            'time' => Carbon::parse($nextAppointment->date)->format('H:i'),
                            'modality' => $nextAppointment->modality,
                            'status' => $nextAppointment->status,
                        ];
                    } else {
                        $followUpGroups[$groupId]['next_appointment'] = null;
                    }
                }
            }
        }
        
        return view('public.patient.followups', [
            'followUpGroups' => $followUpGroups
        ]);
    }
}
