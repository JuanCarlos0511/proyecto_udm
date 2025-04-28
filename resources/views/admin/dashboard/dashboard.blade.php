@extends('layouts.admin')

@section('title', 'Dashboard')

@section('breadcrumb')
<span class="breadcrumb-separator">/</span>
    <span>Tablero</span>
@endsection

@section('page-title', 'Dashboard')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/dashboard.css') }}">
@endsection

@section('page-actions')
    <div class="date-filter">
        <i class="fas fa-calendar-alt"></i>
        <span>{{ date('d M, Y') }}</span>
    </div>
@endsection

@section('content')
    <!-- Stats Cards Component -->
    @include('components.admin.stat-cards', [
        'incomeValue' => $incomeValue,
        'incomeChange' => $incomeChange,
        'appointmentsCount' => $appointmentsCount,
        'appointmentsChange' => $appointmentsChange,
        'patientsCount' => $patientsCount,
        'patientsChange' => $patientsChange,
        'treatmentsCount' => $treatmentsCount,
        'treatmentsChange' => $treatmentsChange
    ])
    
    <!-- Trends Chart Component -->
    @include('components.admin.trends-chart', [
        'incomeData' => $incomeData,
        'appointmentsData' => $appointmentsData,
        'patientsData' => $patientsData,
        'chartLabels' => $chartLabels
    ])
    
    <div class="dashboard-row">
        <!-- Appointments Component -->
        @include('components.admin.upcoming-appointments', ['appointments' => $appointments])
        
        <!-- Patients in Follow-up Component -->
        @include('components.admin.patients-follow-up', ['patients' => $patients])
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('js/admin/dashboard.js') }}"></script>
@endsection

@push('scripts')
    <!-- Chart.js scripts are included in the trends-chart component -->
@endpush
