@extends('layouts.app')

@section('title', 'Seleccionar Horario')

@section('content')
    <h1>SELECCIONE FECHA Y HORA</h1>
    
    <div class="calendar-container">
        <div class="calendar" id="calendar">
            <!-- Calendar will be dynamically populated by JavaScript -->
        </div>
        
        <div class="time-slots" id="timeSlots">
            <!-- Time slots will be dynamically populated based on selected date -->
        </div>
    </div>

    <div class="legend">
        <div class="legend-item">
            <span class="color-box available"></span>
            <span>Disponible</span>
        </div>
        <div class="legend-item">
            <span class="color-box occupied"></span>
            <span>Ya ocupada</span>
        </div>
    </div>

    <button id="confirmAppointment" class="confirm-btn">Solicitar</button>
@endsection

@section('scripts')
    <script src="{{ asset('js/scheduleController.js') }}"></script>
@endsection
