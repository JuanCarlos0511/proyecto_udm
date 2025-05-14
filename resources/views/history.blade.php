@extends('layouts.app')

@section('title', 'Historial de Pagos')

@section('container-class', '')

@section('extra-css')
    <link rel="stylesheet" href="{{ asset('css/components/history.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pages/appointment-history.css') }}">
    <!-- Add CSRF token meta tag for AJAX requests -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Flatpickr para selector de fechas -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        /* Estilos para el selector de fechas */
        .date-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }
        
        .date-modal-content {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        
        .date-modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .date-modal-title {
            font-size: 18px;
            font-weight: 600;
            color: #333;
        }
        
        .date-modal-close {
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
            color: #777;
        }
        
        .date-sections {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .date-section {
            display: flex;
            align-items: center;
        }
        
        .date-label {
            width: 60px;
            font-weight: 500;
            color: #555;
        }
        
        .date-input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        
        .date-modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }
        
        .date-modal-btn {
            padding: 8px 16px;
            border-radius: 4px;
            font-weight: 500;
            cursor: pointer;
            border: none;
        }
        
        .date-reset-btn {
            background-color: #f3f4f6;
            color: #333;
        }
        
        .date-apply-btn {
            background-color: #6366f1;
            color: white;
        }
        
        /* Flatpickr customizations */
        .flatpickr-calendar {
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        
        .flatpickr-day.selected {
            background: #6366f1;
            border-color: #6366f1;
        }
        
        .flatpickr-day.selected:hover {
            background: #4f46e5;
            border-color: #4f46e5;
        }
    </style>
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
                <div class="period-filter" id="periodFilter">
                    <span id="periodText">Todos los periodos</span>
                    <i class="fas fa-calendar"></i>
                </div>
                
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Buscar...">
                </div>
            </div>
            
            <!-- Modal para seleccionar rango de fechas -->
            <div class="date-modal" id="dateModal">
                <div class="date-modal-content">
                    <div class="date-modal-header">
                        <h3 class="date-modal-title">Seleccionar Periodo</h3>
                        <button class="date-modal-close" id="closeDateModal">&times;</button>
                    </div>
                    <div class="date-sections">
                        <div class="date-section">
                            <div class="date-label">Desde:</div>
                            <input type="text" id="startDate" class="date-input" placeholder="Seleccionar fecha inicial">
                        </div>
                        <div class="date-section">
                            <div class="date-label">Hasta:</div>
                            <input type="text" id="endDate" class="date-input" placeholder="Seleccionar fecha final">
                        </div>
                    </div>
                    <div class="date-modal-footer">
                        <button class="date-modal-btn date-reset-btn" id="resetDates">Restablecer</button>
                        <button class="date-modal-btn date-apply-btn" id="applyDates">Aplicar</button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="appointment-table">
            <table>
                <thead>
                    <tr>
                        <th>Fecha <i class="fas fa-sort"></i></th>
                        <th>Asunto <i class="fas fa-sort"></i></th>
                        <th>Modalidad <i class="fas fa-sort"></i></th>
                        <th>Estado <i class="fas fa-sort"></i></th>
                        <th>Precio <i class="fas fa-sort"></i></th>
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
    
    <!-- Flatpickr para selector de fechas -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
    
    <!-- Agregar el ID del usuario como variable global -->
    <script>
        // Variable global con el ID del usuario autenticado
        const AUTHENTICATED_USER_ID = {{ auth()->id() ?? 'null' }};
    </script>
    
    <script src="{{ asset('js/appointmentHistoryController.js') }}"></script>
@endsection
