@extends('layouts.app')

@section('title', 'Historial de Pagos')

@section('container-class', '')

@section('extra-css')
    <link rel="stylesheet" href="{{ asset('css/components/history.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pages/appointment-history.css') }}">
@endsection

@section('content')
    <div class="disbursement-container">
        <div class="appointment-header">
            <div class="title-section">
                <h1>Historial de Citas</h1>
                <p class="subtitle">Consulta el historial de tus citas médicas</p>
            </div>
            
            <div class="action-buttons">
                <a href="{{ route('appointment.clinic') }}" class="create-btn"><i class="fas fa-plus"></i> Agendar Nueva Cita</a>
            </div>
        </div>
        
        <div class="filter-tabs">
            <div class="tab-buttons">
                <button class="tab-btn active" data-filter="all">Todas</button>
                <button class="tab-btn" data-filter="solicitado">Solicitado</button>
                <button class="tab-btn" data-filter="agendado">Agendado</button>
                <button class="tab-btn" data-filter="completado">Completado</button>
                <button class="tab-btn" data-filter="cancelado">Cancelado</button>
            </div>
            
            <div class="filter-controls">
                <div class="period-filter">
                    <span>Todos los periodos</span>
                    <i class="fas fa-calendar"></i>
                </div>
                
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Buscar...">
                </div>
            </div>
        </div>
        
        <div class="appointment-table">
            <table>
                <thead>
                    <tr>
                        <th>ID <i class="fas fa-sort"></i></th>
                        <th>Fecha <i class="fas fa-sort"></i></th>
                        <th>Paciente <i class="fas fa-sort"></i></th>
                        <th>Especialidad <i class="fas fa-sort"></i></th>
                        <th>Modalidad <i class="fas fa-sort"></i></th>
                        <th>Status <i class="fas fa-sort"></i></th>
                        <th>Precio <i class="fas fa-sort"></i></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="appointmentHistory">
                    <!-- This will be populated dynamically by JavaScript -->
                </tbody>
            </table>
        </div>
        
        <div class="pagination">
            <span>Página 1 de 2</span>
            <div class="pagination-controls">
                <button class="prev-btn"><i class="fas fa-chevron-left"></i> Anterior</button>
                <button class="next-btn">Siguiente <i class="fas fa-chevron-right"></i></button>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Add Font Awesome for icons -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    
    <!-- Add CSRF token meta tag for AJAX requests -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <script src="{{ asset('js/appointmentHistoryController.js') }}"></script>
@endsection
