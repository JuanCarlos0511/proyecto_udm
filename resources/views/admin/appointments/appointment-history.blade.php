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
                            <div class="stat-value">64</div>
                            <div class="stat-label">Total Citas Este Mes</div>
                        </div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-icon blue">
                            <i class="fas fa-calendar-day"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value">28</div>
                            <div class="stat-label">Citas Pendientes</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="date-range-section">
                <div class="date-range-container">
                    <div class="date-picker-container">
                        <div class="date-picker-label">From</div>
                        <div class="date-picker" id="startDatePicker">
                            <i class="fas fa-calendar-alt date-picker-icon"></i>
                            <span class="date-picker-value" id="startDateValue">Jan 2022</span>
                        </div>
                    </div>
                    <div class="date-picker-container">
                        <div class="date-picker-label">To</div>
                        <div class="date-picker" id="endDatePicker">
                            <i class="fas fa-calendar-alt date-picker-icon"></i>
                            <span class="date-picker-value" id="endDateValue">Sep 2022</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="appointments-section">
            <div class="appointments-header">
                <h2 class="appointments-title">Historial de Citas</h2>
                <div class="appointments-actions">
                    <div class="appointments-filter" id="openFilterModal">
                        <i class="fas fa-filter appointments-filter-icon"></i>
                        <span class="appointments-filter-text">Filtrar</span>
                    </div>
                    <div class="active-filters" id="activeFilters" style="display: none;">
                        <span class="filter-badge" id="statusFilterBadge" style="display: none;">Estado: <span class="filter-value"></span> <i class="fas fa-times remove-filter"></i></span>
                        <span class="filter-badge" id="doctorFilterBadge" style="display: none;">Doctor: <span class="filter-value"></span> <i class="fas fa-times remove-filter"></i></span>
                        <span class="filter-badge" id="dateFilterBadge" style="display: none;">Fecha: <span class="filter-value"></span> <i class="fas fa-times remove-filter"></i></span>
                    </div>
                </div>
            </div>
            
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
                            <select class="filter-select" id="doctorFilter">
                                <option value="">Todos los doctores</option>
                                <option value="1">Dr. Juan Pérez</option>
                                <option value="2">Dra. María López</option>
                                <option value="3">Dr. Carlos Rodríguez</option>
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
                        <th class="checkbox-cell">
                            <div class="custom-checkbox" id="selectAll"></div>
                        </th>
                        <th>ID Cita</th>
                        <th>Paciente</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Servicio</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="checkbox-cell">
                            <div class="custom-checkbox"></div>
                        </td>
                        <td>#APT-001</td>
                        <td>María González</td>
                        <td>24 Ago 2022</td>
                        <td>10:00 AM</td>
                        <td>Consulta General</td>
                        <td><span class="appointment-status status-completed">Completada</span></td>
                        <td>
                            <div class="appointment-actions">
                                <div class="action-button">
                                    <i class="fas fa-eye"></i>
                                </div>
                                <div class="action-button">
                                    <i class="fas fa-edit"></i>
                                </div>
                                <div class="action-button">
                                    <i class="fas fa-trash"></i>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="checkbox-cell">
                            <div class="custom-checkbox"></div>
                        </td>
                        <td>#APT-002</td>
                        <td>Carlos Rodríguez</td>
                        <td>25 Ago 2022</td>
                        <td>11:30 AM</td>
                        <td>Limpieza Dental</td>
                        <td><span class="appointment-status status-pending">Pendiente</span></td>
                        <td>
                            <div class="appointment-actions">
                                <div class="action-button">
                                    <i class="fas fa-eye"></i>
                                </div>
                                <div class="action-button">
                                    <i class="fas fa-edit"></i>
                                </div>
                                <div class="action-button">
                                    <i class="fas fa-trash"></i>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="checkbox-cell">
                            <div class="custom-checkbox"></div>
                        </td>
                        <td>#APT-003</td>
                        <td>Ana Martínez</td>
                        <td>26 Ago 2022</td>
                        <td>2:00 PM</td>
                        <td>Extracción</td>
                        <td><span class="appointment-status status-cancelled">Cancelada</span></td>
                        <td>
                            <div class="appointment-actions">
                                <div class="action-button">
                                    <i class="fas fa-eye"></i>
                                </div>
                                <div class="action-button">
                                    <i class="fas fa-edit"></i>
                                </div>
                                <div class="action-button">
                                    <i class="fas fa-trash"></i>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="checkbox-cell">
                            <div class="custom-checkbox"></div>
                        </td>
                        <td>#APT-004</td>
                        <td>José López</td>
                        <td>27 Ago 2022</td>
                        <td>9:00 AM</td>
                        <td>Consulta General</td>
                        <td><span class="appointment-status status-completed">Completada</span></td>
                        <td>
                            <div class="appointment-actions">
                                <div class="action-button">
                                    <i class="fas fa-eye"></i>
                                </div>
                                <div class="action-button">
                                    <i class="fas fa-edit"></i>
                                </div>
                                <div class="action-button">
                                    <i class="fas fa-trash"></i>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="checkbox-cell">
                            <div class="custom-checkbox"></div>
                        </td>
                        <td>#APT-005</td>
                        <td>Laura Sánchez</td>
                        <td>28 Ago 2022</td>
                        <td>3:30 PM</td>
                        <td>Ortodoncia</td>
                        <td><span class="appointment-status status-pending">Pendiente</span></td>
                    </tr>
                </tbody>
            </table>
            <div class="pagination" id="appointmentsPagination">
                <a href="#" class="pagination-item active" data-page="1">1</a>
                <a href="#" class="pagination-item" data-page="2">2</a>
                <a href="#" class="pagination-item" data-page="3">3</a>
                <a href="#" class="pagination-item" data-page="4">4</a>
                <a href="#" class="pagination-item" data-page="5">5</a>
                <span class="pagination-separator">...</span>
                <a href="#" class="pagination-item" data-page="10">10</a>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="{{ asset('js/admin/appointment-history.js') }}"></script>
@endsection
