@extends('layouts.admin')

@section('title', 'Historial de Facturación')

@section('page-title', 'Historial de Facturación')

@section('breadcrumb')
    <span class="breadcrumb-separator">/</span>
    <span>Historial de Facturación</span>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/billing-history.css') }}">
    <!-- Meta tag para CSRF token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
    <div class="history-container">
        <div class="top-section">
            <div class="statistics-card">
                <h2 class="statistics-title">Estadísticas Rápidas</h2>
                <div class="statistics-grid">
                    <div class="stat-item">
                        <div class="stat-icon purple">
                            <i class="fas fa-file-invoice-dollar"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value" id="total-month">0</div>
                            <div class="stat-label">Facturas Este Mes</div>
                        </div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-icon blue">
                            <i class="fas fa-receipt"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value" id="pending-bills">0</div>
                            <div class="stat-label">Facturas Pendientes</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="date-range-section">
                <div class="date-range-container">
                    <div class="date-picker-container">
                        <div class="date-picker-label">Desde</div>
                        <div class="date-picker">
                            <i class="fas fa-calendar-alt date-picker-icon"></i>
                            <input type="date" id="date-from" class="date-input" value="2023-01-01">
                        </div>
                    </div>
                    <div class="date-picker-container">
                        <div class="date-picker-label">Hasta</div>
                        <div class="date-picker">
                            <i class="fas fa-calendar-alt date-picker-icon"></i>
                            <input type="date" id="date-to" class="date-input" value="2023-12-31">
                        </div>
                    </div>
                    <div class="date-picker-container">
                        <button type="button" id="loadBillsBtn" class="filter-button">
                            <i class="fas fa-filter"></i> Filtrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="loading" style="display: none;">
            <i class="fas fa-spinner fa-spin"></i> Cargando datos...
        </div>
        
        <div class="bills-section">
            <div class="bills-header">
                <h2 class="bills-title">Historial de Facturación</h2>
            </div>
            <table class="bills-table">
                <thead>
                    <tr>
                        <th>Nº Factura</th>
                        <th>Paciente</th>
                        <th>Fecha</th>
                        <th>Teléfono</th>
                        <th>RFC</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="billsTableBody">
                    <tr>
                        <td colspan="7" class="text-center">No hay facturas disponibles</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/admin/billing-history.js') }}"></script>
    <script src="{{ asset('js/admin/billing-history-loader.js') }}"></script>
@endsection
