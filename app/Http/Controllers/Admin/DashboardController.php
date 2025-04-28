<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Bill;
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
        
        // Calculate income
        $currentMonthIncome = Appointment::whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->sum('price');
            
        $previousMonthIncome = Appointment::whereMonth('date', $previousMonth)
            ->whereYear('date', $currentYear)
            ->sum('price');
            
        $incomeChange = $previousMonthIncome > 0 
            ? round((($currentMonthIncome - $previousMonthIncome) / $previousMonthIncome) * 100) 
            : 100;
            
        // Calculate appointments count
        $currentMonthAppointments = Appointment::whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->count();
            
        $previousMonthAppointments = Appointment::whereMonth('date', $previousMonth)
            ->whereYear('date', $currentYear)
            ->count();
            
        $appointmentsChange = $previousMonthAppointments > 0 
            ? round((($currentMonthAppointments - $previousMonthAppointments) / $previousMonthAppointments) * 100) 
            : 100;
            
        // Calculate patients count
        $currentMonthPatients = User::where('role', 'paciente')
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();
            
        $previousMonthPatients = User::where('role', 'paciente')
            ->whereMonth('created_at', $previousMonth)
            ->whereYear('created_at', $currentYear)
            ->count();
            
        $patientsChange = $previousMonthPatients > 0 
            ? round((($currentMonthPatients - $previousMonthPatients) / $previousMonthPatients) * 100) 
            : 100;
            
        // Calculate treatments (completed appointments)
        $currentMonthTreatments = Appointment::whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->where('status', 'Completado')
            ->count();
            
        $previousMonthTreatments = Appointment::whereMonth('date', $previousMonth)
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
        // Get current date
        $now = Carbon::now();
        
        // Weekly data (last 7 days)
        $weekData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = $now->copy()->subDays($i);
            $weekData[] = Appointment::whereDate('date', $date->toDateString())->sum('price');
        }
        
        // Monthly data (last 4 weeks)
        $monthData = [];
        for ($i = 3; $i >= 0; $i--) {
            $startDate = $now->copy()->subWeeks($i + 1)->addDay();
            $endDate = $now->copy()->subWeeks($i);
            $monthData[] = Appointment::whereBetween('date', [$startDate, $endDate])->sum('price');
        }
        
        // Quarterly data (last 4 months)
        $quarterData = [];
        for ($i = 3; $i >= 0; $i--) {
            $month = $now->copy()->subMonths($i);
            $quarterData[] = Appointment::whereMonth('date', $month->month)
                ->whereYear('date', $month->year)
                ->sum('price');
        }
        
        // Yearly data (last 4 quarters)
        $yearData = [];
        for ($i = 3; $i >= 0; $i--) {
            $startQuarter = $now->copy()->subMonths($i * 3 + 3);
            $endQuarter = $now->copy()->subMonths($i * 3);
            $yearData[] = Appointment::whereBetween('date', [$startQuarter, $endQuarter])->sum('price');
        }
        
        return [
            'week' => $weekData,
            'month' => $monthData,
            'quarter' => $quarterData,
            'year' => $yearData
        ];
    }
    
    /**
     * Get appointments data for the chart
     *
     * @return array
     */
    private function getAppointmentsData()
    {
        // Get current date
        $now = Carbon::now();
        
        // Weekly data (last 7 days)
        $weekData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = $now->copy()->subDays($i);
            $weekData[] = Appointment::whereDate('date', $date->toDateString())->count();
        }
        
        // Monthly data (last 4 weeks)
        $monthData = [];
        for ($i = 3; $i >= 0; $i--) {
            $startDate = $now->copy()->subWeeks($i + 1)->addDay();
            $endDate = $now->copy()->subWeeks($i);
            $monthData[] = Appointment::whereBetween('date', [$startDate, $endDate])->count();
        }
        
        // Quarterly data (last 4 months)
        $quarterData = [];
        for ($i = 3; $i >= 0; $i--) {
            $month = $now->copy()->subMonths($i);
            $quarterData[] = Appointment::whereMonth('date', $month->month)
                ->whereYear('date', $month->year)
                ->count();
        }
        
        // Yearly data (last 4 quarters)
        $yearData = [];
        for ($i = 3; $i >= 0; $i--) {
            $startQuarter = $now->copy()->subMonths($i * 3 + 3);
            $endQuarter = $now->copy()->subMonths($i * 3);
            $yearData[] = Appointment::whereBetween('date', [$startQuarter, $endQuarter])->count();
        }
        
        return [
            'week' => $weekData,
            'month' => $monthData,
            'quarter' => $quarterData,
            'year' => $yearData
        ];
    }
    
    /**
     * Get patients data for the chart
     *
     * @return array
     */
    private function getPatientsData()
    {
        // Get current date
        $now = Carbon::now();
        
        // Weekly data (last 7 days)
        $weekData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = $now->copy()->subDays($i);
            $weekData[] = User::where('role', 'paciente')
                ->whereDate('created_at', $date->toDateString())
                ->count();
        }
        
        // Monthly data (last 4 weeks)
        $monthData = [];
        for ($i = 3; $i >= 0; $i--) {
            $startDate = $now->copy()->subWeeks($i + 1)->addDay();
            $endDate = $now->copy()->subWeeks($i);
            $monthData[] = User::where('role', 'paciente')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count();
        }
        
        // Quarterly data (last 4 months)
        $quarterData = [];
        for ($i = 3; $i >= 0; $i--) {
            $month = $now->copy()->subMonths($i);
            $quarterData[] = User::where('role', 'paciente')
                ->whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->count();
        }
        
        // Yearly data (last 4 quarters)
        $yearData = [];
        for ($i = 3; $i >= 0; $i--) {
            $startQuarter = $now->copy()->subMonths($i * 3 + 3);
            $endQuarter = $now->copy()->subMonths($i * 3);
            $yearData[] = User::where('role', 'paciente')
                ->whereBetween('created_at', [$startQuarter, $endQuarter])
                ->count();
        }
        
        return [
            'week' => $weekData,
            'month' => $monthData,
            'quarter' => $quarterData,
            'year' => $yearData
        ];
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
        $appointments = Appointment::with('user')
            ->where('date', '>=', Carbon::today())
            ->where('status', '!=', 'Cancelado')
            ->orderBy('date')
            ->limit(5)
            ->get();
            
        $formattedAppointments = [];
        
        foreach ($appointments as $appointment) {
            // Get doctor information
            $doctorName = 'Doctor Asignado';
            
            // Format date
            $formattedDate = Carbon::parse($appointment->date)->format('d M, Y');
            
            // Determine status class
            $statusClass = 'scheduled';
            if ($appointment->status == 'Completado') {
                $statusClass = 'completed';
            } elseif ($appointment->status == 'Solicitado') {
                $statusClass = 'requested';
            }
            
            $formattedAppointments[] = (object) [
                'id' => $appointment->id,
                'patient_name' => $appointment->user->name,
                'formatted_date' => $formattedDate,
                'doctor_name' => $doctorName,
                'status_class' => $statusClass,
                'status_text' => $appointment->status
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
        // Get patients with recent appointments
        $patients = User::where('role', 'paciente')
            ->whereHas('appointments', function ($query) {
                $query->where('date', '<=', Carbon::now())
                      ->where('status', 'Completado');
            })
            ->with(['appointments' => function ($query) {
                $query->where('status', 'Completado')
                      ->orderBy('date', 'desc');
            }])
            ->limit(5)
            ->get();
            
        $formattedPatients = [];
        
        foreach ($patients as $patient) {
            // Get last appointment
            $lastAppointment = $patient->appointments->first();
            
            if (!$lastAppointment) {
                continue;
            }
            
            // Get initials
            $nameParts = explode(' ', $patient->name);
            $initials = '';
            foreach ($nameParts as $part) {
                if (!empty($part)) {
                    $initials .= strtoupper(substr($part, 0, 1));
                }
            }
            
            // Format date
            $formattedDate = Carbon::parse($lastAppointment->date)->format('d M, Y');
            
            // Determine status
            $status = $patient->status === 'active' ? 'active' : 'pending';
            $statusText = $patient->status === 'active' ? 'Activo' : 'Pendiente';
            
            $formattedPatients[] = (object) [
                'id' => $patient->id,
                'name' => $patient->name,
                'initials' => $initials,
                'last_visit_date' => $formattedDate,
                'treatment' => $lastAppointment->subject,
                'status' => $status,
                'status_text' => $statusText
            ];
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
