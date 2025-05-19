@extends('layouts.admin')

@section('title', 'Historial de Citas')

@section('page-title', 'Historial de Citas')

@section('breadcrumb')
    <span class="breadcrumb-separator">/</span>
    <span>Historial de Citas</span>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/appointment-history.css') }}">
@endsection

@section('content')
    <div class="history-container">
        <div class="top-section">
            <div class="statistics-card">
                <h2 class="statistics-title">Estadísticas Rápidas</h2>
                <div class="statistics-grid">
                    <div class="stat-item">
                        <div class="stat-icon purple">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value" id="total-month">0</div>
                            <div class="stat-label">Total Citas Este Mes</div>
                        </div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-icon blue">
                            <i class="fas fa-calendar-day"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value" id="pending-appointments">0</div>
                            <div class="stat-label">Citas Pendientes</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="date-range-section">
                <form action="{{ route('admin.appointments.history') }}" method="GET" class="date-filter-form">
                    <div class="date-range-container">
                        <div class="date-picker-container">
                            <div class="date-picker-label">Desde</div>
                            <div class="date-picker">
                                <i class="fas fa-calendar-alt date-picker-icon"></i>
                                <input type="date" name="date_from" value="{{ $defaultStartDate ?? '2025-01-01' }}" class="date-input">
                            </div>
                        </div>
                        <div class="date-picker-container">
                            <div class="date-picker-label">Hasta</div>
                            <div class="date-picker">
                                <i class="fas fa-calendar-alt date-picker-icon"></i>
                                <input type="date" name="date_to" value="{{ $defaultEndDate ?? '2025-12-31' }}" class="date-input">
                            </div>
                        </div>
                        <div class="date-picker-container">
                            <button type="submit" class="filter-button">
                                <i class="fas fa-filter"></i> Filtrar
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
        
        <div class="loading" style="display: none;">
            <i class="fas fa-spinner"></i> Cargando datos...
        </div>
        
        <div class="appointments-section">
           
            <!-- Modal de filtrado -->
            <div class="filter-modal" id="filterModal" style="display: none;">
                <div class="filter-modal-content">
                    <div class="filter-modal-header">
                        <h3 class="filter-modal-title">Filtrar Citas</h3>
                        <button class="filter-modal-close" id="closeFilterModal"><i class="fas fa-times"></i></button>
                    </div>
                    <div class="filter-modal-body">
                        <div class="filter-group">
                            <label class="filter-label">Estado de la Cita</label>
                            <div class="filter-options">
                                <div class="filter-option">
                                    <input type="checkbox" id="statusCompleted" name="status" value="completed">
                                    <label for="statusCompleted">Completada</label>
                                </div>
                                <div class="filter-option">
                                    <input type="checkbox" id="statusPending" name="status" value="pending">
                                    <label for="statusPending">Pendiente</label>
                                </div>
                                <div class="filter-option">
                                    <input type="checkbox" id="statusCancelled" name="status" value="cancelled">
                                    <label for="statusCancelled">Cancelada</label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="filter-group">
                            <label class="filter-label">Doctor</label>
                            <select class="filter-select" id="doctorFilter" name="doctor_id">
                                <option value="">Todos los doctores</option>
                                @if(isset($doctors) && $doctors->count() > 0)
                                    @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}" {{ request('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                        {{ $doctor->name }}
                                    </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        
                        <div class="filter-group">
                            <label class="filter-label">Rango de Fechas</label>
                            <div class="filter-date-range">
                                <div class="filter-date">
                                    <label>Desde</label>
                                    <input type="date" id="dateFrom" class="filter-date-input">
                                </div>
                                <div class="filter-date">
                                    <label>Hasta</label>
                                    <input type="date" id="dateTo" class="filter-date-input">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="filter-modal-footer">
                        <button class="filter-reset-btn" id="resetFilters">Restablecer</button>
                        <button class="filter-apply-btn" id="applyFilters">Aplicar Filtros</button>
                    </div>
                </div>
            </div>
            <table class="appointments-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Paciente</th>
                        <th>Fecha</th>
                        <th>Asunto</th>
                        <th>Estado</th>
                        <th>Modalidad</th>
                        <th>Precio</th>
                    </tr>
                </thead>
                <tbody id="appointmentsTableBody">
                    @if(isset($appointments) && !empty($appointments))
                        @forelse($appointments as $appointment)
                        <tr>
                            <td>{{ $appointment->id }}</td>
                            <td>{{ $appointment->user->name }}</td>
                            <td>{{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y') }}</td>
                            <td>{{ $appointment->subject ?: 'N/A' }}</td>
                            <td><span class="appointment-status status-{{ strtolower($appointment->status) }}">{{ $appointment->status }}</span></td>
                            <td>{{ $appointment->modality ?: 'Consultorio' }}</td>
                            <td>${{ number_format($appointment->price, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">No hay citas para mostrar</td>
                        </tr>
                        @endforelse
                    @else
                        <tr>
                            <td colspan="8" class="text-center">No hay citas disponibles</td>
                        </tr>
                    @endif
                </tbody>
            </table>
            @if(isset($appointments) && method_exists($appointments, 'links'))
                {{ $appointments->links() }}
            @endif
        </div>
    </div>
@endsection

@section('scripts')
<script src="{{ asset('js/admin/appointment-history.js') }}"></script>
<script src="{{ asset('js/admin/appointment-history-loader.js') }}"></script>
@endsection
