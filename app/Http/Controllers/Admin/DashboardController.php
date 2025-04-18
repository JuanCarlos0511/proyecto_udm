<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

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
        
        return view('admin.dashboard', $data);
    }
    
    /**
     * Get dashboard data
     *
     * @return array
     */
    private function getDashboardData()
    {
        return [
            // Stats for the stat cards
            'incomeValue' => 24850,
            'incomeChange' => 15,
            'appointmentsCount' => 78,
            'appointmentsChange' => 12,
            'patientsCount' => 42,
            'patientsChange' => 8,
            'treatmentsCount' => 35,
            'treatmentsChange' => 5,
            
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
        // This would normally come from the database
        return [
            'week' => [4200, 5100, 4800, 6300, 7200, 8500, 9100],
            'month' => [18500, 21300, 19800, 24850],
            'quarter' => [58000, 64500, 72000, 89500],
            'year' => [210000, 245000, 280000, 320000]
        ];
    }
    
    /**
     * Get appointments data for the chart
     *
     * @return array
     */
    private function getAppointmentsData()
    {
        // This would normally come from the database
        return [
            'week' => [12, 15, 10, 18, 14, 20, 16],
            'month' => [45, 52, 63, 78],
            'quarter' => [145, 162, 178, 195],
            'year' => [580, 645, 720, 850]
        ];
    }
    
    /**
     * Get patients data for the chart
     *
     * @return array
     */
    private function getPatientsData()
    {
        // This would normally come from the database
        return [
            'week' => [8, 10, 7, 12, 9, 14, 11],
            'month' => [32, 38, 42, 45],
            'quarter' => [95, 110, 125, 140],
            'year' => [380, 420, 460, 510]
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
        // This would normally come from the database
        return [
            (object) [
                'id' => 1,
                'patient_name' => 'Carlos Rodríguez',
                'formatted_date' => '19 Abr, 2025',
                'doctor_name' => 'Dra. María González',
                'status_class' => 'scheduled',
                'status_text' => 'Agendada'
            ],
            (object) [
                'id' => 2,
                'patient_name' => 'Ana Martínez',
                'formatted_date' => '20 Abr, 2025',
                'doctor_name' => 'Dr. Juan Pérez',
                'status_class' => 'scheduled',
                'status_text' => 'Agendada'
            ],
            (object) [
                'id' => 3,
                'patient_name' => 'Luis Hernández',
                'formatted_date' => '21 Abr, 2025',
                'doctor_name' => 'Dr. Roberto Sánchez',
                'status_class' => 'scheduled',
                'status_text' => 'Agendada'
            ]
        ];
    }
    
    /**
     * Get patients in follow-up
     *
     * @return array
     */
    private function getPatientsInFollowUp()
    {
        // This would normally come from the database
        return [
            (object) [
                'id' => 1,
                'name' => 'Carlos Rodríguez',
                'initials' => 'CR',
                'last_visit_date' => '10 Abr, 2025',
                'treatment' => 'Fisioterapia',
                'status' => 'active',
                'status_text' => 'Activo'
            ],
            (object) [
                'id' => 2,
                'name' => 'Ana Martínez',
                'initials' => 'AM',
                'last_visit_date' => '12 Abr, 2025',
                'treatment' => 'Dermatología',
                'status' => 'active',
                'status_text' => 'Activo'
            ],
            (object) [
                'id' => 3,
                'name' => 'Luis Hernández',
                'initials' => 'LH',
                'last_visit_date' => '8 Abr, 2025',
                'treatment' => 'Cardiología',
                'status' => 'pending',
                'status_text' => 'Pendiente'
            ],
            (object) [
                'id' => 4,
                'name' => 'Elena Gómez',
                'initials' => 'EG',
                'last_visit_date' => '5 Abr, 2025',
                'treatment' => 'Nutrición',
                'status' => 'active',
                'status_text' => 'Activo'
            ],
            (object) [
                'id' => 5,
                'name' => 'Miguel Torres',
                'initials' => 'MT',
                'last_visit_date' => '15 Abr, 2025',
                'treatment' => 'Terapia Respiratoria',
                'status' => 'active',
                'status_text' => 'Activo'
            ]
        ];
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
