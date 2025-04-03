@extends('layouts.app')

@section('title', 'Historial')

@section('container-class', 'history-view')

@section('content')
    <h1>Historial de citas</h1>
    
    <div class="history-table">
        <table>
            <thead>
                <tr>
                    <th>No. Cita</th>
                    <th>Doctor</th>
                    <th>Fecha de cita</th>
                    <th>Hora inicio</th>
                    <th>Hora final</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody id="appointmentHistory">
                <!-- This will be populated dynamically by JavaScript -->
            </tbody>
        </table>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/historyController.js') }}"></script>
@endsection
