@extends('layouts.app')

@section('title', 'Panel de Administración')

@section('container-class', 'admin-panel')

@section('content')
    <div class="admin-layout">
        <!-- Left Column - Active Appointments -->
        <div class="admin-column">
            <div class="active-appointments">
                <h3>Citas Activas</h3>
                <div class="appointment-items" id="activeAppointments">
                    <!-- Will be populated by JavaScript -->
                </div>
            </div>
        </div>

        <!-- Right Column - Pending Appointments -->
        <div class="admin-column">
            <div class="pending-appointments">
                <h3>Citas pendientes de confirmar:</h3>
                <div class="pending-items" id="pendingAppointments">
                    <!-- Will be populated by JavaScript -->
                </div>
            </div>
        </div>
    </div>
    
    <!-- Schedule Button at Bottom -->
    <div class="schedule-button-container">
        <button id="scheduleNewAppointment" class="schedule-btn-large">AGENDAR CITA</button>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/admin.js') }}"></script>
@endsection
