@extends('layouts.admin')

@section('title', 'Historial de Citas')

@section('page-title', 'Historial de Citas')

@section('breadcrumb')
    <span class="breadcrumb-separator">/</span>
    <span>Historial de Citas</span>
@endsection

@section('styles')
<style>
    /* Statistics Section */
    .top-section {
        display: flex;
        gap: 20px;
        margin-bottom: 20px;
    }

    .statistics-card {
        flex: 1;
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        padding: 20px;
        min-width: 280px;
    }

    .statistics-title {
        font-size: 16px;
        font-weight: 600;
        color: #333;
        margin-bottom: 20px;
    }

    .statistics-grid {
        display: flex;
        gap: 15px;
    }

    .stat-item {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        color: white;
    }

    .stat-icon.purple {
        background-color: #6c5dd3;
    }

    .stat-icon.blue {
        background-color: #3e7bfa;
    }

    .stat-content {
        display: flex;
        flex-direction: column;
    }

    .stat-value {
        font-size: 24px;
        font-weight: 600;
        color: #333;
    }

    .stat-label {
        font-size: 14px;
        color: #666;
    }

    /* Date Range Section */
    .date-range-section {
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        padding: 20px;
        display: flex;
        justify-content: flex-end;
        align-items: center;
        flex: 2;
    }

    .chart-title {
        font-size: 16px;
        font-weight: 600;
        color: #333;
    }
    
    .date-range-container {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .date-picker-container {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .date-picker-label {
        font-size: 12px;
        color: #666;
    }

    .date-picker {
        display: flex;
        align-items: center;
        gap: 10px;
        background-color: #f5f6fa;
        border-radius: 8px;
        padding: 10px 15px;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .date-picker:hover {
        background-color: #eef0f7;
    }

    .date-picker-icon {
        color: #6c5dd3;
    }

    .date-picker-value {
        font-size: 14px;
        color: #333;
    }

    /* Appointments Table */
    .appointments-section {
        margin-top: 20px;
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        padding: 20px;
    }

    .appointments-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .appointments-title {
        font-size: 16px;
        font-weight: 600;
        color: #333;
    }

    .appointments-actions {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .appointments-filter {
        display: flex;
        align-items: center;
        gap: 10px;
        background-color: #f5f6fa;
        border-radius: 8px;
        padding: 8px 15px;
        cursor: pointer;
    }

    .appointments-filter-icon {
        color: #6c5dd3;
    }

    .appointments-filter-text {
        font-size: 14px;
        color: #333;
    }

    .appointments-table {
        width: 100%;
        border-collapse: collapse;
    }

    .appointments-table th {
        text-align: left;
        padding: 15px;
        font-size: 14px;
        font-weight: 500;
        color: #666;
        border-bottom: 1px solid #e0e0e0;
    }

    .appointments-table td {
        padding: 15px;
        font-size: 14px;
        color: #333;
        border-bottom: 1px solid #e0e0e0;
    }

    .appointments-table tr:last-child td {
        border-bottom: none;
    }

    .appointments-table tr:hover {
        background-color: #f9f9f9;
    }

    .appointment-status {
        display: inline-flex;
        align-items: center;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }

    .status-completed {
        background-color: rgba(52, 168, 83, 0.1);
        color: #34A853;
    }

    .status-pending {
        background-color: rgba(251, 188, 5, 0.1);
        color: #FBBC05;
    }

    .status-cancelled {
        background-color: rgba(234, 67, 53, 0.1);
        color: #EA4335;
    }

    .appointment-actions {
        display: flex;
        gap: 10px;
    }

    .action-button {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #666;
        background-color: #f5f6fa;
        cursor: pointer;
        transition: all 0.2s;
    }

    .action-button:hover {
        background-color: #6c5dd3;
        color: white;
    }

    /* Checkbox styling */
    .checkbox-cell {
        width: 40px;
    }

    .custom-checkbox {
        width: 20px;
        height: 20px;
        border-radius: 4px;
        border: 2px solid #e0e0e0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
    }

    .custom-checkbox.checked {
        background-color: #6c5dd3;
        border-color: #6c5dd3;
    }

    .custom-checkbox.checked::after {
        content: '\f00c';
        font-family: 'Font Awesome 5 Free';
        font-weight: 900;
        color: white;
        font-size: 12px;
    }

    /* Pagination */
    .pagination {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        margin-top: 20px;
        gap: 10px;
    }

    .pagination-button {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f5f6fa;
        color: #666;
        cursor: pointer;
        transition: all 0.2s;
    }

    .pagination-button:hover {
        background-color: #eef0f7;
    }

    .pagination-button.active {
        background-color: #6c5dd3;
        color: white;
    }

    /* Date Picker Modal */
    .modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
    }

    .modal-content {
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        width: 300px;
        padding: 20px;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .modal-title {
        font-size: 16px;
        font-weight: 600;
        color: #333;
    }

    .modal-close {
        cursor: pointer;
        font-size: 18px;
        color: #666;
    }

    .month-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
        margin-bottom: 20px;
    }

    .month-item {
        padding: 10px;
        text-align: center;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .month-item:hover {
        background-color: #f5f6fa;
    }

    .month-item.active {
        background-color: #6c5dd3;
        color: white;
    }

    .year-selector {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    .year-value {
        font-size: 16px;
        font-weight: 500;
    }

    .year-arrow {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f5f6fa;
        cursor: pointer;
        transition: all 0.2s;
    }

    .year-arrow:hover {
        background-color: #eef0f7;
    }

    .modal-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    .modal-button {
        padding: 8px 15px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
    }

    .cancel-button {
        background-color: #f5f6fa;
        color: #666;
    }

    .cancel-button:hover {
        background-color: #eef0f7;
    }

    .apply-button {
        background-color: #6c5dd3;
        color: white;
    }

    .apply-button:hover {
        background-color: #5a4cbe;
    }
    
    /* Estilos para el modal de filtrado */
    .filter-modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
    }
    
    .filter-modal-content {
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        width: 400px;
        max-width: 90%;
    }
    
    .filter-modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 20px;
        border-bottom: 1px solid #e0e0e0;
    }
    
    .filter-modal-title {
        font-size: 16px;
        font-weight: 600;
        color: #333;
        margin: 0;
    }
    
    .filter-modal-close {
        background: none;
        border: none;
        font-size: 16px;
        color: #666;
        cursor: pointer;
    }
    
    .filter-modal-body {
        padding: 20px;
    }
    
    .filter-group {
        margin-bottom: 20px;
    }
    
    .filter-label {
        display: block;
        font-size: 14px;
        font-weight: 500;
        color: #333;
        margin-bottom: 10px;
    }
    
    .filter-options {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }
    
    .filter-option {
        display: flex;
        align-items: center;
        gap: 5px;
    }
    
    .filter-option label {
        font-size: 14px;
        color: #333;
    }
    
    .filter-select {
        width: 100%;
        padding: 10px;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        font-size: 14px;
        color: #333;
    }
    
    .filter-date-range {
        display: flex;
        gap: 10px;
    }
    
    .filter-date {
        flex: 1;
    }
    
    .filter-date label {
        display: block;
        font-size: 12px;
        color: #666;
        margin-bottom: 5px;
    }
    
    .filter-date-input {
        width: 100%;
        padding: 8px;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        font-size: 14px;
        color: #333;
    }
    
    .filter-modal-footer {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        padding: 15px 20px;
        border-top: 1px solid #e0e0e0;
    }
    
    .filter-reset-btn {
        padding: 8px 15px;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        background-color: white;
        font-size: 14px;
        font-weight: 500;
        color: #666;
        cursor: pointer;
    }
    
    .filter-reset-btn:hover {
        background-color: #f5f5f5;
    }
    
    .filter-apply-btn {
        padding: 8px 15px;
        border: none;
        border-radius: 8px;
        background-color: #6c5dd3;
        font-size: 14px;
        font-weight: 500;
        color: white;
        cursor: pointer;
    }
    
    .filter-apply-btn:hover {
        background-color: #5a4cbe;
    }
    
    /* Estilos para las etiquetas de filtros activos */
    .active-filters {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-left: 15px;
    }
    
    .filter-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 10px;
        background-color: #f0edff;
        border-radius: 20px;
        font-size: 12px;
        color: #6c5dd3;
    }
    
    .filter-value {
        font-weight: 500;
    }
    
    .remove-filter {
        cursor: pointer;
    }
</style>
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
                        <th>Acciones</th>
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Variables para el rango de fechas en la sección superior
        const startDatePicker = document.getElementById('startDatePicker');
        const endDatePicker = document.getElementById('endDatePicker');
        const startDateValue = document.getElementById('startDateValue');
        const endDateValue = document.getElementById('endDateValue');
        
        // Variables para el modal de filtrado
        const openFilterModalBtn = document.getElementById('openFilterModal');
        const filterModal = document.getElementById('filterModal');
        const closeFilterModalBtn = document.getElementById('closeFilterModal');
        const applyFiltersBtn = document.getElementById('applyFilters');
        const resetFiltersBtn = document.getElementById('resetFilters');
        const activeFilters = document.getElementById('activeFilters');
        
        // Variables para las etiquetas de filtros
        const statusFilterBadge = document.getElementById('statusFilterBadge');
        const doctorFilterBadge = document.getElementById('doctorFilterBadge');
        const dateFilterBadge = document.getElementById('dateFilterBadge');
        
        // Funcionalidad del modal de filtrado
        openFilterModalBtn.addEventListener('click', function() {
            filterModal.style.display = 'flex';
        });
        
        closeFilterModalBtn.addEventListener('click', function() {
            filterModal.style.display = 'none';
        });
        
        // Cerrar modal al hacer clic fuera del contenido
        filterModal.addEventListener('click', function(event) {
            if (event.target === filterModal) {
                filterModal.style.display = 'none';
            }
        });
        
        // Aplicar filtros
        applyFiltersBtn.addEventListener('click', function() {
            // Obtener los valores de los filtros
            const statusFilters = [];
            document.querySelectorAll('input[name="status"]:checked').forEach(checkbox => {
                statusFilters.push(checkbox.value);
            });
            
            const doctorFilter = document.getElementById('doctorFilter').value;
            const dateFrom = document.getElementById('dateFrom').value;
            const dateTo = document.getElementById('dateTo').value;
            
            // Mostrar las etiquetas de filtros activos
            if (statusFilters.length > 0) {
                statusFilterBadge.querySelector('.filter-value').textContent = statusFilters.join(', ');
                statusFilterBadge.style.display = 'inline-flex';
                activeFilters.style.display = 'flex';
            } else {
                statusFilterBadge.style.display = 'none';
            }
            
            if (doctorFilter) {
                const doctorName = document.getElementById('doctorFilter').options[document.getElementById('doctorFilter').selectedIndex].text;
                doctorFilterBadge.querySelector('.filter-value').textContent = doctorName;
                doctorFilterBadge.style.display = 'inline-flex';
                activeFilters.style.display = 'flex';
            } else {
                doctorFilterBadge.style.display = 'none';
            }
            
            if (dateFrom || dateTo) {
                let dateText = '';
                if (dateFrom && dateTo) {
                    dateText = `${dateFrom} - ${dateTo}`;
                } else if (dateFrom) {
                    dateText = `Desde ${dateFrom}`;
                } else {
                    dateText = `Hasta ${dateTo}`;
                }
                dateFilterBadge.querySelector('.filter-value').textContent = dateText;
                dateFilterBadge.style.display = 'inline-flex';
                activeFilters.style.display = 'flex';
            } else {
                dateFilterBadge.style.display = 'none';
            }
            
            // Si no hay filtros activos, ocultar la sección
            if (!statusFilters.length && !doctorFilter && !dateFrom && !dateTo) {
                activeFilters.style.display = 'none';
            }
            
            // Cerrar el modal
            filterModal.style.display = 'none';
            
            // Aquí se llamaría a la función para filtrar los datos
            filterAppointments(statusFilters, doctorFilter, dateFrom, dateTo);
        });
        
        // Restablecer filtros
        resetFiltersBtn.addEventListener('click', function() {
            // Limpiar los checkboxes de estado
            document.querySelectorAll('input[name="status"]').forEach(checkbox => {
                checkbox.checked = false;
            });
            
            // Restablecer el selector de doctor
            document.getElementById('doctorFilter').value = '';
            
            // Limpiar los campos de fecha
            document.getElementById('dateFrom').value = '';
            document.getElementById('dateTo').value = '';
        });
        
        // Eliminar filtros individuales
        document.querySelectorAll('.remove-filter').forEach(removeBtn => {
            removeBtn.addEventListener('click', function() {
                const badge = this.parentElement;
                badge.style.display = 'none';
                
                // Identificar qué filtro se está eliminando
                if (badge === statusFilterBadge) {
                    document.querySelectorAll('input[name="status"]').forEach(checkbox => {
                        checkbox.checked = false;
                    });
                } else if (badge === doctorFilterBadge) {
                    document.getElementById('doctorFilter').value = '';
                } else if (badge === dateFilterBadge) {
                    document.getElementById('dateFrom').value = '';
                    document.getElementById('dateTo').value = '';
                }
                
                // Verificar si hay algún filtro activo
                const visibleBadges = document.querySelectorAll('.filter-badge[style*="display: inline-flex"]');
                if (visibleBadges.length === 0) {
                    activeFilters.style.display = 'none';
                }
                
                // Actualizar la tabla sin el filtro eliminado
                const statusFilters = [];
                document.querySelectorAll('input[name="status"]:checked').forEach(checkbox => {
                    statusFilters.push(checkbox.value);
                });
                
                const doctorFilter = document.getElementById('doctorFilter').value;
                const dateFrom = document.getElementById('dateFrom').value;
                const dateTo = document.getElementById('dateTo').value;
                
                filterAppointments(statusFilters, doctorFilter, dateFrom, dateTo);
            });
        });
        
        // Current date values para el picker superior
        let currentStartDate = { month: 8, year: 2022 }; // September 2022
        let currentEndDate = { month: 8, year: 2022 }; // September 2022
        let activePickerType = null;
        
        function createDatePickerModal(type) {
            activePickerType = type;
            const currentDate = type === 'start' ? currentStartDate : currentEndDate;
            
            const modal = document.createElement('div');
            modal.className = 'modal';
            modal.innerHTML = `
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="modal-title">Select ${type === 'start' ? 'Start' : 'End'} Date</div>
                        <div class="modal-close">&times;</div>
                    </div>
                    <div class="year-selector">
                        <div class="year-arrow prev-year">
                            <i class="fas fa-chevron-left"></i>
                        </div>
                        <div class="year-value">${currentDate.year}</div>
                        <div class="year-arrow next-year">
                            <i class="fas fa-chevron-right"></i>
                        </div>
                    </div>
                    <div class="month-grid">
                        ${months.map((month, index) => `
                            <div class="month-item ${index === currentDate.month ? 'active' : ''}" data-month="${index}">
                                ${month}
                            </div>
                        `).join('')}
                    </div>
                    <div class="modal-actions">
                        <div class="modal-button cancel-button">Cancel</div>
                        <div class="modal-button apply-button">Apply</div>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
            
            // Close button functionality
            modal.querySelector('.modal-close').addEventListener('click', function() {
                document.body.removeChild(modal);
            });
            
            // Cancel button functionality
            modal.querySelector('.cancel-button').addEventListener('click', function() {
                document.body.removeChild(modal);
            });
            
            // Month selection functionality
            const monthItems = modal.querySelectorAll('.month-item');
            monthItems.forEach(item => {
                item.addEventListener('click', function() {
                    monthItems.forEach(m => m.classList.remove('active'));
                    this.classList.add('active');
                    const monthIndex = parseInt(this.getAttribute('data-month'));
                    if (activePickerType === 'start') {
                        currentStartDate.month = monthIndex;
                    } else {
                        currentEndDate.month = monthIndex;
                    }
                });
            });
            
            // Year navigation functionality
            modal.querySelector('.prev-year').addEventListener('click', function() {
                const yearValue = modal.querySelector('.year-value');
                const currentYear = parseInt(yearValue.textContent);
                if (activePickerType === 'start') {
                    currentStartDate.year = currentYear - 1;
                } else {
                    currentEndDate.year = currentYear - 1;
                }
                yearValue.textContent = currentYear - 1;
            });
            
            modal.querySelector('.next-year').addEventListener('click', function() {
                const yearValue = modal.querySelector('.year-value');
                const currentYear = parseInt(yearValue.textContent);
                if (activePickerType === 'start') {
                    currentStartDate.year = currentYear + 1;
                } else {
                    currentEndDate.year = currentYear + 1;
                }
                yearValue.textContent = currentYear + 1;
            });
            
            // Apply button functionality
            modal.querySelector('.apply-button').addEventListener('click', function() {
                if (activePickerType === 'start') {
                    startDateValue.textContent = `${months[currentStartDate.month]} ${currentStartDate.year}`;
                    
                    // Ensure end date is not before start date
                    if (currentEndDate.year < currentStartDate.year || 
                        (currentEndDate.year === currentStartDate.year && currentEndDate.month < currentStartDate.month)) {
                        currentEndDate = { ...currentStartDate };
                        endDateValue.textContent = `${months[currentEndDate.month]} ${currentEndDate.year}`;
                    }
                } else {
                    endDateValue.textContent = `${months[currentEndDate.month]} ${currentEndDate.year}`;
                    
                    // Ensure start date is not after end date
                    if (currentStartDate.year > currentEndDate.year || 
                        (currentStartDate.year === currentEndDate.year && currentStartDate.month > currentEndDate.month)) {
                        currentStartDate = { ...currentEndDate };
                        startDateValue.textContent = `${months[currentStartDate.month]} ${currentStartDate.year}`;
                    }
                }
                
                document.body.removeChild(modal);
                updateData();
            });
        }
        
        startDatePicker.addEventListener('click', function() {
            createDatePickerModal('start');
        });
        
        endDatePicker.addEventListener('click', function() {
            createDatePickerModal('end');
        });
        
        // Checkbox functionality
        const selectAllCheckbox = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.custom-checkbox:not(#selectAll)');
        
        selectAllCheckbox.addEventListener('click', function() {
            this.classList.toggle('checked');
            const isChecked = this.classList.contains('checked');
            
            checkboxes.forEach(checkbox => {
                if (isChecked) {
                    checkbox.classList.add('checked');
                } else {
                    checkbox.classList.remove('checked');
                }
            });
        });
        
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('click', function() {
                this.classList.toggle('checked');
                
                // Check if all checkboxes are checked
                const allChecked = Array.from(checkboxes).every(cb => cb.classList.contains('checked'));
                
                if (allChecked) {
                    selectAllCheckbox.classList.add('checked');
                } else {
                    selectAllCheckbox.classList.remove('checked');
                }
            });
        });
        
        // Datos de ejemplo para las citas
        const appointmentsData = [
            { id: 1, patient: 'María González', doctor: 'Dr. Juan Pérez', date: '2022-09-15', time: '09:00 AM', status: 'completed', service: 'Consulta General' },
            { id: 2, patient: 'Carlos Rodríguez', doctor: 'Dra. María López', date: '2022-09-16', time: '10:30 AM', status: 'pending', service: 'Limpieza Dental' },
            { id: 3, patient: 'Ana Martínez', doctor: 'Dr. Carlos Rodríguez', date: '2022-09-17', time: '11:00 AM', status: 'cancelled', service: 'Extracción' },
            { id: 4, patient: 'José López', doctor: 'Dr. Juan Pérez', date: '2022-09-18', time: '09:30 AM', status: 'completed', service: 'Consulta General' },
            { id: 5, patient: 'Laura Sánchez', doctor: 'Dra. María López', date: '2022-09-19', time: '10:00 AM', status: 'pending', service: 'Ortodoncia' },
            { id: 6, patient: 'Pedro García', doctor: 'Dr. Carlos Rodríguez', date: '2022-09-20', time: '11:30 AM', status: 'completed', service: 'Limpieza Dental' },
            { id: 7, patient: 'Sofía Flores', doctor: 'Dr. Juan Pérez', date: '2022-09-21', time: '09:00 AM', status: 'pending', service: 'Consulta General' },
            { id: 8, patient: 'Miguel Torres', doctor: 'Dra. María López', date: '2022-09-22', time: '10:30 AM', status: 'cancelled', service: 'Extracción' },
            { id: 9, patient: 'Carmen Ruiz', doctor: 'Dr. Carlos Rodríguez', date: '2022-09-23', time: '11:00 AM', status: 'completed', service: 'Ortodoncia' },
            { id: 10, patient: 'Daniel Morales', doctor: 'Dr. Juan Pérez', date: '2022-09-24', time: '09:30 AM', status: 'pending', service: 'Limpieza Dental' },
            { id: 11, patient: 'Isabel Jiménez', doctor: 'Dra. María López', date: '2022-09-25', time: '10:00 AM', status: 'completed', service: 'Consulta General' },
            { id: 12, patient: 'Roberto Vargas', doctor: 'Dr. Carlos Rodríguez', date: '2022-09-26', time: '11:30 AM', status: 'cancelled', service: 'Extracción' }
        ];
        
        // Variables para la paginación
        const itemsPerPage = 5;
        let currentPage = 1;
        let filteredData = [...appointmentsData];
        
        // Función para filtrar las citas
        function filterAppointments(statusFilters, doctorFilter, dateFrom, dateTo) {
            filteredData = appointmentsData.filter(appointment => {
                // Filtrar por estado
                if (statusFilters.length > 0 && !statusFilters.includes(appointment.status)) {
                    return false;
                }
                
                // Filtrar por doctor
                if (doctorFilter && appointment.doctor !== getDoctorNameById(doctorFilter)) {
                    return false;
                }
                
                // Filtrar por fecha
                if (dateFrom && new Date(appointment.date) < new Date(dateFrom)) {
                    return false;
                }
                
                if (dateTo && new Date(appointment.date) > new Date(dateTo)) {
                    return false;
                }
                
                return true;
            });
            
            // Resetear a la primera página y actualizar la tabla
            currentPage = 1;
            updatePagination();
            renderAppointments();
        }
        
        // Función para obtener el nombre del doctor por ID
        function getDoctorNameById(id) {
            const doctors = {
                '1': 'Dr. Juan Pérez',
                '2': 'Dra. María López',
                '3': 'Dr. Carlos Rodríguez'
            };
            return doctors[id] || '';
        }
        
        // Función para renderizar las citas en la tabla
        function renderAppointments() {
            const tableBody = document.querySelector('.appointments-table tbody');
            tableBody.innerHTML = '';
            
            const startIndex = (currentPage - 1) * itemsPerPage;
            const endIndex = startIndex + itemsPerPage;
            const paginatedData = filteredData.slice(startIndex, endIndex);
            
            if (paginatedData.length === 0) {
                const row = document.createElement('tr');
                row.innerHTML = `<td colspan="7" style="text-align: center;">No se encontraron citas con los filtros seleccionados</td>`;
                tableBody.appendChild(row);
                return;
            }
            
            paginatedData.forEach(appointment => {
                const row = document.createElement('tr');
                
                // Obtener la clase de estado para el estilo
                let statusClass = '';
                let statusText = '';
                
                switch(appointment.status) {
                    case 'completed':
                        statusClass = 'status-completed';
                        statusText = 'Completada';
                        break;
                    case 'pending':
                        statusClass = 'status-pending';
                        statusText = 'Pendiente';
                        break;
                    case 'cancelled':
                        statusClass = 'status-cancelled';
                        statusText = 'Cancelada';
                        break;
                }
                
                row.innerHTML = `
                    <td>
                        <div class="custom-checkbox" id="checkbox-${appointment.id}"></div>
                    </td>
                    <td>${appointment.patient}</td>
                    <td>${appointment.doctor}</td>
                    <td>${appointment.date}</td>
                    <td>${appointment.time}</td>
                    <td>
                        <span class="appointment-status ${statusClass}">${statusText}</span>
                    </td>
                    <td>${appointment.service}</td>
                `;
                
                tableBody.appendChild(row);
                
                // Agregar funcionalidad al checkbox
                const checkbox = document.getElementById(`checkbox-${appointment.id}`);
                checkbox.addEventListener('click', function() {
                    this.classList.toggle('checked');
                    
                    // Verificar si todos los checkboxes están marcados
                    const allChecked = Array.from(document.querySelectorAll('.custom-checkbox:not(#selectAll)')).every(cb => cb.classList.contains('checked'));
                    
                    if (allChecked) {
                        selectAllCheckbox.classList.add('checked');
                    } else {
                        selectAllCheckbox.classList.remove('checked');
                    }
                });
            });
        }
        
        // Función para actualizar la paginación
        function updatePagination() {
            const pagination = document.getElementById('appointmentsPagination');
            const totalPages = Math.ceil(filteredData.length / itemsPerPage);
            
            pagination.innerHTML = '';
            
            // Agregar botón anterior
            const prevButton = document.createElement('a');
            prevButton.href = '#';
            prevButton.className = 'pagination-item';
            prevButton.innerHTML = '<i class="fas fa-chevron-left"></i>';
            prevButton.addEventListener('click', function(e) {
                e.preventDefault();
                if (currentPage > 1) {
                    currentPage--;
                    updatePagination();
                    renderAppointments();
                }
            });
            pagination.appendChild(prevButton);
            
            // Mostrar páginas
            const maxVisiblePages = 5;
            let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
            let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);
            
            if (endPage - startPage + 1 < maxVisiblePages && startPage > 1) {
                startPage = Math.max(1, endPage - maxVisiblePages + 1);
            }
            
            for (let i = startPage; i <= endPage; i++) {
                const pageLink = document.createElement('a');
                pageLink.href = '#';
                pageLink.className = 'pagination-item' + (i === currentPage ? ' active' : '');
                pageLink.textContent = i;
                pageLink.dataset.page = i;
                
                pageLink.addEventListener('click', function(e) {
                    e.preventDefault();
                    currentPage = parseInt(this.dataset.page);
                    updatePagination();
                    renderAppointments();
                });
                
                pagination.appendChild(pageLink);
            }
            
            // Mostrar puntos suspensivos si hay más páginas
            if (endPage < totalPages) {
                const separator = document.createElement('span');
                separator.className = 'pagination-separator';
                separator.textContent = '...';
                pagination.appendChild(separator);
                
                // Mostrar la última página
                const lastPage = document.createElement('a');
                lastPage.href = '#';
                lastPage.className = 'pagination-item';
                lastPage.textContent = totalPages;
                lastPage.dataset.page = totalPages;
                
                lastPage.addEventListener('click', function(e) {
                    e.preventDefault();
                    currentPage = parseInt(this.dataset.page);
                    updatePagination();
                    renderAppointments();
                });
                
                pagination.appendChild(lastPage);
            }
            
            // Agregar botón siguiente
            const nextButton = document.createElement('a');
            nextButton.href = '#';
            nextButton.className = 'pagination-item';
            nextButton.innerHTML = '<i class="fas fa-chevron-right"></i>';
            nextButton.addEventListener('click', function(e) {
                e.preventDefault();
                if (currentPage < totalPages) {
                    currentPage++;
                    updatePagination();
                    renderAppointments();
                }
            });
            pagination.appendChild(nextButton);
        }
        
        // Inicializar la tabla y la paginación
        renderAppointments();
        updatePagination();
        
        function updateData() {
            // This would typically fetch data from the server based on the selected date range
            // For demo purposes, we'll just log the current selection
            const startDateText = document.getElementById('startDateValue').textContent;
            const endDateText = document.getElementById('endDateValue').textContent;
            console.log(`Fetching appointment data from ${startDateText} to ${endDateText}`);
            
            // Refresh the table data based on the selected date range
            // En una aplicación real, aquí se haría una llamada AJAX para obtener los datos filtrados
            // Por ahora, simplemente reiniciamos los filtros y mostramos todos los datos
            filteredData = [...appointmentsData];
            currentPage = 1;
            updatePagination();
            renderAppointments();
        }
    });
</script>
@endsection
