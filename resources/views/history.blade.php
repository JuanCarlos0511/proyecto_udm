@extends('layouts.app')

@section('title', 'Historial')

@section('container-class', '')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/components/history.css') }}">
@endsection

@section('content')
    <div class="history-container">
        <div class="history-header">
            <h1>Historial de citas</h1>
            
            <div class="pdf-report-controls">
                <div class="time-filter">
                    <label for="timeFilter">Periodo:</label>
                    <select id="timeFilter" class="time-filter-select">
                        <option value="day">Último día</option>
                        <option value="week">Última semana</option>
                        <option value="month">Último mes</option>
                        <option value="year">Último año</option>
                        <option value="full" selected>Completo</option>
                    </select>
                </div>
                
                <button id="generatePdfBtn" class="btn-generate-pdf">
                    <i class="fas fa-file-pdf"></i> Generar PDF
                </button>
            </div>
        </div>
        
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
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="appointmentHistory">
                    <!-- This will be populated dynamically by JavaScript -->
                </tbody>
            </table>
        </div>

        <!-- Nueva sección con la información de la clínica -->
        <div class="clinic-info">
            <div class="phone">
                <i class="fas fa-phone-alt"></i> 4521 346
            </div>
            <div class="details">
                Clínica Miel | Dirección Física<br>
                Horario: Lunes a Viernes 8:00 - 20:00 hrs.
            </div>
        </div>
    </div>

    <!-- Overlay de carga para generación de PDF -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner-container">
            <div class="loading-spinner"></div>
            <div class="loading-text">Generando PDF...</div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Add jsPDF and jspdf-autotable from CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
    
    <!-- Add CSRF token meta tag for AJAX requests -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <script src="{{ asset('js/historyController.js') }}"></script>
@endsection
