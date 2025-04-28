@extends('layouts.admin')

@section('title', 'Historial de Facturación')

@section('page-title', 'Historial de Facturación')

@section('breadcrumb')
    <span class="breadcrumb-separator">/</span>
    <span>Historial de Facturación</span>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/billing-history.css') }}">
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
                            <div class="stat-value">$24,850</div>
                            <div class="stat-label">Ingresos Este Mes</div>
                        </div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-icon blue">
                            <i class="fas fa-receipt"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value">42</div>
                            <div class="stat-label">Facturas Emitidas</div>
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
        <div class="bills-section">
            <div class="bills-header">
                <h2 class="bills-title">Historial de Facturación</h2>
                <div class="bills-actions">
                    <div class="bills-filter">
                        <i class="fas fa-filter bills-filter-icon"></i>
                        <span class="bills-filter-text">Filtrar</span>
                    </div>
                </div>
            </div>
            <table class="bills-table">
                <thead>
                    <tr>
                        <th class="checkbox-cell">
                            <div class="custom-checkbox" id="selectAll"></div>
                        </th>
                        <th>Nº Factura</th>
                        <th>Paciente</th>
                        <th>Fecha</th>
                        <th>Monto</th>
                        <th>Método de Pago</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="checkbox-cell">
                            <div class="custom-checkbox"></div>
                        </td>
                        <td>#INV-001</td>
                        <td>María González</td>
                        <td>24 Ago 2022</td>
                        <td>$350.00</td>
                        <td>Tarjeta de Crédito</td>
                        <td><span class="bill-status status-paid">Pagada</span></td>
                        <td>
                            <div class="bill-actions">
                                <div class="action-button">
                                    <i class="fas fa-eye"></i>
                                </div>
                                <div class="action-button">
                                    <i class="fas fa-print"></i>
                                </div>
                                <div class="action-button">
                                    <i class="fas fa-download"></i>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="checkbox-cell">
                            <div class="custom-checkbox"></div>
                        </td>
                        <td>#INV-002</td>
                        <td>Carlos Rodríguez</td>
                        <td>25 Ago 2022</td>
                        <td>$180.00</td>
                        <td>Efectivo</td>
                        <td><span class="bill-status status-paid">Pagada</span></td>
                        <td>
                            <div class="bill-actions">
                                <div class="action-button">
                                    <i class="fas fa-eye"></i>
                                </div>
                                <div class="action-button">
                                    <i class="fas fa-print"></i>
                                </div>
                                <div class="action-button">
                                    <i class="fas fa-download"></i>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="checkbox-cell">
                            <div class="custom-checkbox"></div>
                        </td>
                        <td>#INV-003</td>
                        <td>Ana Martínez</td>
                        <td>26 Ago 2022</td>
                        <td>$520.00</td>
                        <td>Transferencia</td>
                        <td><span class="bill-status status-pending">Pendiente</span></td>
                        <td>
                            <div class="bill-actions">
                                <div class="action-button">
                                    <i class="fas fa-eye"></i>
                                </div>
                                <div class="action-button">
                                    <i class="fas fa-print"></i>
                                </div>
                                <div class="action-button">
                                    <i class="fas fa-download"></i>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="checkbox-cell">
                            <div class="custom-checkbox"></div>
                        </td>
                        <td>#INV-004</td>
                        <td>José López</td>
                        <td>27 Ago 2022</td>
                        <td>$120.00</td>
                        <td>Tarjeta de Débito</td>
                        <td><span class="bill-status status-paid">Pagada</span></td>
                        <td>
                            <div class="bill-actions">
                                <div class="action-button">
                                    <i class="fas fa-eye"></i>
                                </div>
                                <div class="action-button">
                                    <i class="fas fa-print"></i>
                                </div>
                                <div class="action-button">
                                    <i class="fas fa-download"></i>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="checkbox-cell">
                            <div class="custom-checkbox"></div>
                        </td>
                        <td>#INV-005</td>
                        <td>Laura Sánchez</td>
                        <td>28 Ago 2022</td>
                        <td>$450.00</td>
                        <td>Tarjeta de Crédito</td>
                        <td><span class="bill-status status-overdue">Vencida</span></td>
                        <td>
                            <div class="bill-actions">
                                <div class="action-button">
                                    <i class="fas fa-eye"></i>
                                </div>
                                <div class="action-button">
                                    <i class="fas fa-print"></i>
                                </div>
                                <div class="action-button">
                                    <i class="fas fa-download"></i>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="pagination">
                <div class="pagination-button">
                    <i class="fas fa-chevron-left"></i>
                </div>
                <div class="pagination-button active">1</div>
                <div class="pagination-button">2</div>
                <div class="pagination-button">3</div>
                <div class="pagination-button">
                    <i class="fas fa-chevron-right"></i>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/admin/billing-history.js') }}"></script>
@endsection
