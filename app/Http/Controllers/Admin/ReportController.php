<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;

class ReportController extends Controller
{
    /**
     * Display the report generation form.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get the current date and 30 days ago for default date range
        $endDate = Carbon::now()->format('Y-m-d');
        $startDate = Carbon::now()->subDays(30)->format('Y-m-d');
        
        return view('admin.reports.generate-report', compact('startDate', 'endDate'));
    }

    /**
     * Get appointment data for the report.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAppointmentData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'startDate' => 'required|date_format:Y-m-d',
            'endDate' => 'required|date_format:Y-m-d|after_or_equal:startDate',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Format dates for query
        $startDate = Carbon::parse($request->startDate)->startOfDay();
        $endDate = Carbon::parse($request->endDate)->endOfDay();

        // Get appointments within the date range
        $appointments = Appointment::with('user')
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date')
            ->get()
            ->map(function ($appointment) {
                return [
                    'id' => $appointment->id,
                    'patient_name' => $appointment->user->name,
                    'date' => Carbon::parse($appointment->date)->format('d/m/Y'),
                    'subject' => $appointment->subject,
                    'status' => $appointment->status,
                    'modality' => $appointment->modality,
                    'price' => number_format($appointment->price, 2)
                ];
            });

        // Calculate statistics
        $totalAppointments = $appointments->count();
        $completedAppointments = Appointment::whereBetween('date', [$startDate, $endDate])
            ->where('status', 'Completado')
            ->count();
        $canceledAppointments = Appointment::whereBetween('date', [$startDate, $endDate])
            ->where('status', 'Cancelado')
            ->count();
        $pendingAppointments = Appointment::whereBetween('date', [$startDate, $endDate])
            ->where('status', 'Agendado')
            ->count();
        $totalIncome = Appointment::whereBetween('date', [$startDate, $endDate])
            ->sum('price');

        return response()->json([
            'success' => true,
            'data' => [
                'appointments' => $appointments,
                'stats' => [
                    'total' => $totalAppointments,
                    'completed' => $completedAppointments,
                    'canceled' => $canceledAppointments,
                    'pending' => $pendingAppointments,
                    'totalIncome' => number_format($totalIncome, 2)
                ],
                'dateRange' => [
                    'start' => $startDate->format('d/m/Y'),
                    'end' => $endDate->format('d/m/Y')
                ]
            ]
        ]);
    }



}
