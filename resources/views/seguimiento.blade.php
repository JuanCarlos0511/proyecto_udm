@extends('layouts.app')

@section('title', 'Plan de Seguimiento')

@section('container-class', 'seguimiento-panel')

@section('content')
    <div class="seguimiento-layout">
        <!-- Left Column - Appointments with follow-up -->
        <div class="seguimiento-column">
            <div class="seguimiento-appointments">
                <h3>Citas con seguimiento</h3>
                <div class="seguimiento-items" id="seguimientoAppointments">
                    <!-- Will be populated by JavaScript -->
                </div>
            </div>
        </div>

        <!-- Middle Column - Calendar/Date information -->
        <div class="seguimiento-column">
            <div class="date-info">
                <div class="date-header">
                    <span>Nombre</span>
                    <span>Día de cita</span>
                </div>
                <div class="date-content" id="appointmentDateInfo">
                    <!-- Will be populated by JavaScript when an appointment is selected -->
                </div>
            </div>
        </div>

        <!-- Right Column - Notes -->
        <div class="seguimiento-column">
            <div class="notes-section">
                <h3>Notas</h3>
                <textarea id="appointmentNotes" class="notes-textarea" rows="10"></textarea>
            </div>
        </div>
    </div>
    
    <!-- Schedule Button at Bottom -->
    <div class="schedule-button-container">
        <button id="scheduleNewAppointment" class="schedule-btn-large">AGENDAR CITA</button>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/seguimientoController.js') }}"></script>
@endsection
