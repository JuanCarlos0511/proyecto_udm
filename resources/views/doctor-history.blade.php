@extends('layouts.app')

@section('title', 'Historial de Pacientes - Doctor')

@section('container-class', 'doctor-history')

@section('content')
    <h1>Historial de citas</h1>
    
    <div class="history-table">
        <table>
            <thead>
                <tr>
                    <th>No. Cita</th>
                    <th>Paciente</th>
                    <th>Fecha de cita</th>
                    <th>Hora inicio</th>
                    <th>Hora final</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody id="patientHistory">
                <!-- Will be populated by JavaScript -->
            </tbody>
        </table>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/doctorHistoryController.js') }}"></script>
@endsection
