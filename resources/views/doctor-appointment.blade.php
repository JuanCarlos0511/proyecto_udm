@extends('layouts.app')

@section('title', 'Agendar Cita - Doctor')

@section('container-class', 'doctor-appointment-modal')

@section('content')
    <div class="modal-content">
        <h2>AGENDAR CITA</h2>
        
        <form id="doctorAppointmentForm">
            <div class="form-group">
                <label for="patientName">Nombre del paciente:</label>
                <input type="text" id="patientName" name="patientName" required>
            </div>

            <div class="form-group">
                <label for="patientPhone">Teléfono:</label>
                <input type="tel" id="patientPhone" name="patientPhone" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="patientAge">Edad:</label>
                    <input type="number" id="patientAge" name="patientAge" required>
                </div>
                <div class="form-group">
                    <label for="patientAddress">Dirección</label>
                    <input type="text" id="patientAddress" name="patientAddress" required>
                </div>
            </div>

            <div class="form-group">
                <label>Seleccione una fecha y hora:</label>
                <div class="calendar-container">
                    <div class="calendar" id="doctorCalendar">
                        <!-- Calendar will be populated by JavaScript -->
                    </div>
                    <div id="timeSlotsPicker" class="time-slots-picker">
                        <!-- Time slots will be populated by JavaScript -->
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="button" id="cancelAppointment" class="cancel-btn">Cancelar</button>
                <button type="submit" id="submitAppointment" class="solicitar-btn">Solicitar</button>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/doctorAppointmentController.js') }}"></script>
@endsection
