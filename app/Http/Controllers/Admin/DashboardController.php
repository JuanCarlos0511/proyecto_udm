<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Bill;
use App\Models\FollowUp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the dashboard view
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get dashboard data
        $data = $this->getDashboardData();
        
        return view('admin.dashboard.dashboard', $data);
    }
    
    /**
     * Get dashboard data
     *
     * @return array
     */
    private function getDashboardData()
    {
        // Get current month and previous month for comparisons
        $currentMonth = Carbon::now()->month;
        $previousMonth = Carbon::now()->subMonth()->month;
        $currentYear = Carbon::now()->year;
        $user = auth()->user();
        
        // Preparar consultas base según el rol del usuario
        $appointmentsQuery = Appointment::query();
        
        // Si es doctor, filtrar solo las citas donde el doctor es el usuario actual
        if ($user->role === 'doctor') {
            // Para los doctores, las citas son las que tienen su user_id
            $appointmentsQuery->where('user_id', $user->id);
        }
        
        // Calculate income
        $currentMonthIncome = (clone $appointmentsQuery)
            ->whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->sum('price');
            
        $previousMonthIncome = (clone $appointmentsQuery)
            ->whereMonth('date', $previousMonth)
            ->whereYear('date', $currentYear)
            ->sum('price');
            
        $incomeChange = $previousMonthIncome > 0 
            ? round((($currentMonthIncome - $previousMonthIncome) / $previousMonthIncome) * 100) 
            : 100;
            
        // Calculate appointments count
        $currentMonthAppointments = (clone $appointmentsQuery)
            ->whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->count();
            
        $previousMonthAppointments = (clone $appointmentsQuery)
            ->whereMonth('date', $previousMonth)
            ->whereYear('date', $currentYear)
            ->count();
            
        $appointmentsChange = $previousMonthAppointments > 0 
            ? round((($currentMonthAppointments - $previousMonthAppointments) / $previousMonthAppointments) * 100) 
            : 100;
            
        // Calculate patients count - Para doctores, mostramos todos los pacientes
        $patientsQuery = User::where('role', 'paciente');
        
        // Si es doctor, no filtramos por pacientes específicos
        // Todos los doctores pueden ver todos los pacientes
        
        $currentMonthPatients = (clone $patientsQuery)
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();
            
        $previousMonthPatients = (clone $patientsQuery)
            ->whereMonth('created_at', $previousMonth)
            ->whereYear('created_at', $currentYear)
            ->count();
            
        $patientsChange = $previousMonthPatients > 0 
            ? round((($currentMonthPatients - $previousMonthPatients) / $previousMonthPatients) * 100) 
            : 100;
            
        // Calculate treatments (completed appointments)
        $currentMonthTreatments = (clone $appointmentsQuery)
            ->whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->where('status', 'Completado')
            ->count();
            
        $previousMonthTreatments = (clone $appointmentsQuery)
            ->whereMonth('date', $previousMonth)
            ->whereYear('date', $currentYear)
            ->where('status', 'Completado')
            ->count();
            
        $treatmentsChange = $previousMonthTreatments > 0 
            ? round((($currentMonthTreatments - $previousMonthTreatments) / $previousMonthTreatments) * 100) 
            : 100;
        
        return [
            // Stats for the stat cards
            'incomeValue' => $currentMonthIncome,
            'incomeChange' => $incomeChange,
            'appointmentsCount' => $currentMonthAppointments,
            'appointmentsChange' => $appointmentsChange,
            'patientsCount' => User::where('role', 'paciente')->count(),
            'patientsChange' => $patientsChange,
            'treatmentsCount' => $currentMonthTreatments,
            'treatmentsChange' => $treatmentsChange,
            
            // Chart data
            'incomeData' => $this->getIncomeData(),
            'appointmentsData' => $this->getAppointmentsData(),
            'patientsData' => $this->getPatientsData(),
            'chartLabels' => $this->getChartLabels(),
            
            // Appointments data
            'appointments' => $this->getUpcomingAppointments(),
            
            // Patients data
            'patients' => $this->getPatientsInFollowUp(),
        ];
    }
    
    /**
     * Get income data for the chart
     *
     * @return array
     */
    private function getIncomeData()
    {
        $user = auth()->user();
        $data = [
            'week' => [],
            'month' => [],
            'quarter' => [],
            'year' => []
        ];
        
        $query = Appointment::query();
        $user = auth()->user();
        
        // Si es doctor, filtrar solo sus ingresos
        if ($user->role === 'doctor') {
            $query->where('user_id', $user->id);
        }
        
        // Weekly data (last 7 days)
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $data['week'][] = (clone $query)
                ->whereDate('date', $date)
                ->sum('price');
        }
        
        // Monthly data (last 4 weeks)
        for ($i = 3; $i >= 0; $i--) {
            $startDate = Carbon::now()->subWeeks($i + 1)->addDay();
            $endDate = Carbon::now()->subWeeks($i);
            $data['month'][] = (clone $query)
                ->whereBetween('date', [$startDate, $endDate])
                ->sum('price');
        }
        
        // Quarterly data (last 4 months)
        for ($i = 3; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i)->month;
            $year = Carbon::now()->subMonths($i)->year;
            $data['quarter'][] = (clone $query)
                ->whereMonth('date', $month)
                ->whereYear('date', $year)
                ->sum('price');
        }
        
        // Yearly data (last 4 quarters)
        for ($i = 3; $i >= 0; $i--) {
            $startDate = Carbon::now()->subMonths(($i + 1) * 3)->addDay();
            $endDate = Carbon::now()->subMonths($i * 3);
            $data['year'][] = (clone $query)
                ->whereBetween('date', [$startDate, $endDate])
                ->sum('price');
        }
        
        return [            
            'chartData' => $data,
            'chartLabels' => $this->getChartLabels()
        ];
    }
    
    /**
     * Get appointments data for the chart
     *
     * @return array
     */
    private function getAppointmentsData()
    {
        $data = [
            'week' => [],
            'month' => [],
            'quarter' => [],
            'year' => []
        ];
        
        $query = Appointment::query();
        $user = auth()->user();
        
        // Si es doctor, filtrar solo sus citas
        if ($user->role === 'doctor') {
            $query->where('user_id', $user->id);
        }
        
        // Weekly data (last 7 days)
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $data['week'][] = (clone $query)
                ->whereDate('date', $date)
                ->count();
        }
        
        // Monthly data (last 4 weeks)
        for ($i = 3; $i >= 0; $i--) {
            $startDate = Carbon::now()->subWeeks($i + 1)->addDay();
            $endDate = Carbon::now()->subWeeks($i);
            $data['month'][] = (clone $query)
                ->whereBetween('date', [$startDate, $endDate])
                ->count();
        }
        
        // Quarterly data (last 4 months)
        for ($i = 3; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i)->month;
            $year = Carbon::now()->subMonths($i)->year;
            $data['quarter'][] = (clone $query)
                ->whereMonth('date', $month)
                ->whereYear('date', $year)
                ->count();
        }
        
        // Yearly data (last 4 quarters)
        for ($i = 3; $i >= 0; $i--) {
            $startDate = Carbon::now()->subMonths(($i + 1) * 3)->addDay();
            $endDate = Carbon::now()->subMonths($i * 3);
            $data['year'][] = (clone $query)
                ->whereBetween('date', [$startDate, $endDate])
                ->count();
        }
        
        return [            
            'chartData' => $data,
            'chartLabels' => $this->getChartLabels()
        ];
    }
    
    /**
     * Get patients data for the chart
     *
     * @return array
     */
    private function getPatientsData()
    {
        $user = auth()->user();
        $data = [
            'week' => [],
            'month' => [],
            'quarter' => [],
            'year' => []
        ];
        
        // Preparar consulta base según el rol del usuario
        $query = User::where('role', 'paciente');
        
        // Si es doctor, filtrar solo los pacientes que ha atendido
        if ($user->role === 'doctor') {
            $patientIds = Appointment::where('user_id', $user->id)->pluck('user_id')->unique();
            $query->whereIn('id', $patientIds);
        }
        
        // Weekly data (last 7 days)
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            
            if ($user->role === 'doctor') {
                // Para doctores, contar pacientes atendidos en esa fecha
                $data['week'][] = Appointment::where('user_id', $user->id)
                    ->whereDate('date', $date->toDateString())
                    ->distinct('user_id')
                    ->count('user_id');
            } else {
                // Para admin, contar nuevos pacientes registrados
                $data['week'][] = (clone $query)
                    ->whereDate('created_at', $date->toDateString())
                    ->count();
            }
        }
        
        // Monthly data (last 4 weeks)
        for ($i = 3; $i >= 0; $i--) {
            $startDate = Carbon::now()->subWeeks($i + 1)->addDay();
            $endDate = Carbon::now()->subWeeks($i);
            
            if ($user->role === 'doctor') {
                // Para doctores, contar pacientes atendidos en ese período
                $data['month'][] = Appointment::where('user_id', $user->id)
                    ->whereBetween('date', [$startDate, $endDate])
                    ->distinct('user_id')
                    ->count('user_id');
            } else {
                // Para admin, contar nuevos pacientes registrados
                $data['month'][] = (clone $query)
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->count();
            }
        }
        
        // Quarterly data (last 4 months)
        for ($i = 3; $i >= 0; $i--) {
            $startDate = Carbon::now()->subMonths($i + 1)->addDay();
            $endDate = Carbon::now()->subMonths($i);
            
            if ($user->role === 'doctor') {
                // Para doctores, contar pacientes atendidos en ese período
                $data['quarter'][] = Appointment::where('user_id', $user->id)
                    ->whereBetween('date', [$startDate, $endDate])
                    ->distinct('user_id')
                    ->count('user_id');
            } else {
                // Para admin, contar nuevos pacientes registrados
                $data['quarter'][] = (clone $query)
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->count();
            }
        }
        
        // Yearly data (last 4 quarters)
        for ($i = 3; $i >= 0; $i--) {
            $startDate = Carbon::now()->subMonths(($i + 1) * 3)->addDay();
            $endDate = Carbon::now()->subMonths($i * 3);
            
            if ($user->role === 'doctor') {
                // Para doctores, contar pacientes atendidos en ese período
                $data['year'][] = Appointment::where('user_id', $user->id)
                    ->whereBetween('date', [$startDate, $endDate])
                    ->distinct('user_id')
                    ->count('user_id');
            } else {
                // Para admin, contar nuevos pacientes registrados
                $data['year'][] = (clone $query)
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->count();
            }
        }
        
        return $data;
    }
    
    /**
     * Get chart labels
     *
     * @return array
     */
    private function getChartLabels()
    {
        return [
            'week' => ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'],
            'month' => ['Semana 1', 'Semana 2', 'Semana 3', 'Semana 4'],
            'quarter' => ['Enero', 'Febrero', 'Marzo', 'Abril'],
            'year' => ['Q1', 'Q2', 'Q3', 'Q4']
        ];
    }
    
    /**
     * Get upcoming appointments
     *
     * @return array
     */
    private function getUpcomingAppointments()
    {
        // Preparar consulta base para todas las citas próximas
        $query = Appointment::with('user')
            ->where('date', '>=', Carbon::today())
            ->where('status', '!=', 'Cancelado')
            ->orderBy('date');
            
        // Si es doctor, filtrar solo sus citas
        $user = auth()->user();
        if ($user->role === 'doctor') {
            $query->where('user_id', $user->id);
        }
        
        $appointments = $query->limit(5)->get();
        
        $formattedAppointments = [];
        
        foreach ($appointments as $appointment) {
            $formattedAppointments[] = (object) [
                'id' => $appointment->id,
                'formatted_date' => Carbon::parse($appointment->date)->format('d M, Y'),
                'patient_name' => $appointment->user->name,
                'treatment' => $appointment->subject,
                'status' => $appointment->status
            ];
        }
        
        return $formattedAppointments;
    }
    
    /**
     * Get patients in follow-up
     *
     * @return array
     */
    private function getPatientsInFollowUp()
    {
        $user = auth()->user();
        $formattedPatients = [];
        
        if ($user->role === 'doctor') {
            // Para doctores, buscar sus seguimientos y obtener los pacientes relacionados
            $followUps = FollowUp::with('user')
                ->where('status', 'active')
                ->byUser($user->id)  // El doctor es el usuario en seguimiento
                ->orderBy('start_date', 'desc')
                ->limit(5)
                ->get();
                
            foreach ($followUps as $followUp) {
                // Obtener los pacientes del mismo grupo (el otro lado de la relación)
                $patientData = $followUp->followUpGroupMembers()
                    ->byUserRole('paciente')
                    ->first();
                    
                if ($patientData) {
                    $formattedPatients[] = (object) [
                        'id' => $followUp->follow_up_group_id,
                        'doctor' => $user->name,
                        'patient' => $patientData->user->name,
                        'treatment' => $followUp->notes,
                        'next_appointment' => $followUp->end_date ? Carbon::parse($followUp->end_date)->format('d M, Y') : 'No definida'
                    ];
                }
            }
        } else {
            // Para administradores, obtener todos los seguimientos
            $followUps = FollowUp::with('user')
                ->where('status', 'active')
                ->byUserRole('doctor')  // Obtener solo los registros de seguimiento de doctores
                ->orderBy('start_date', 'desc')
                ->limit(5)
                ->get();
                
            foreach ($followUps as $followUp) {
                // Obtener los pacientes del mismo grupo
                $patientData = $followUp->followUpGroupMembers()
                    ->byUserRole('paciente')
                    ->first();
                    
                if ($patientData) {
                    $formattedPatients[] = (object) [
                        'id' => $followUp->follow_up_group_id,
                        'doctor' => $followUp->user->name,
                        'patient' => $patientData->user->name,
                        'treatment' => $followUp->notes,
                        'next_appointment' => $followUp->end_date ? Carbon::parse($followUp->end_date)->format('d M, Y') : 'No definida'
                    ];
                }
            }
        }
        
        return $formattedPatients;
    }
    
    /**
     * Refresh dashboard data via AJAX
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh(Request $request)
    {
        // Get dashboard data
        $data = $this->getDashboardData();
        
        return response()->json($data);
    }
}
