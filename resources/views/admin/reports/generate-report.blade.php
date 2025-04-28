@extends('layouts.admin')

@section('title', 'Generar Reporte')

@section('page-title', 'Generar Reporte')

@section('breadcrumb')
    <span class="breadcrumb-separator">/</span>
    <span>Generar Reporte</span>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/reports.css') }}">
@endsection

@section('content')
    <div class="report-container">
        <form id="reportForm" action="{{ url('admin/reports/generate') }}" method="POST">
            @csrf
            <input type="hidden" id="reportType" name="reportType" value="">
            
            <div class="date-range-section">
                <div class="date-range-container">
                    <div class="date-picker-container">
                        <div class="date-picker-label">Desde</div>
                        <div class="date-picker" id="startDatePicker">
                            <i class="fas fa-calendar-alt date-picker-icon"></i>
                            <span class="date-picker-value" id="startDateValue">Ene 2022</span>
                            <input type="hidden" name="startDate" value="2022-01-01">
                        </div>
                    </div>
                    <div class="date-picker-container">
                        <div class="date-picker-label">Hasta</div>
                        <div class="date-picker" id="endDatePicker">
                            <i class="fas fa-calendar-alt date-picker-icon"></i>
                            <span class="date-picker-value" id="endDateValue">Sep 2022</span>
                            <input type="hidden" name="endDate" value="2022-09-30">
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="report-types-section">
                <h2 class="section-title">Seleccione el Tipo de Reporte</h2>
                <div class="report-types-grid">
                    <div class="report-type-card" data-type="financial">
                        <div class="report-type-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="report-type-title">Financiero</div>
                        <div class="report-type-description">
                            Ingresos, gastos y balance general durante el período seleccionado.
                        </div>
                    </div>
                    <div class="report-type-card" data-type="patients">
                        <div class="report-type-icon">
                            <i class="fas fa-user-injured"></i>
                        </div>
                        <div class="report-type-title">Pacientes</div>
                        <div class="report-type-description">
                            Estadísticas de pacientes, nuevos registros y seguimientos.
                        </div>
                    </div>
                    <div class="report-type-card" data-type="appointments">
                        <div class="report-type-icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="report-type-title">Citas</div>
                        <div class="report-type-description">
                            Análisis de citas, cancelaciones y tipos de consulta.
                        </div>
                    </div>
                    <div class="report-type-card" data-type="doctors">
                        <div class="report-type-icon">
                            <i class="fas fa-user-md"></i>
                        </div>
                        <div class="report-type-title">Médicos</div>
                        <div class="report-type-description">
                            Rendimiento de médicos, citas atendidas y valoraciones.
                        </div>
                    </div>
                    <div class="report-type-card" data-type="inventory">
                        <div class="report-type-icon">
                            <i class="fas fa-pills"></i>
                        </div>
                        <div class="report-type-title">Inventario</div>
                        <div class="report-type-description">
                            Estado del inventario, consumos y necesidades de reposición.
                        </div>
                    </div>
                    <div class="report-type-card" data-type="custom">
                        <div class="report-type-icon">
                            <i class="fas fa-sliders-h"></i>
                        </div>
                        <div class="report-type-title">Personalizado</div>
                        <div class="report-type-description">
                            Cree un reporte personalizado con los parámetros que necesite.
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="report-options-section">
                <h2 class="section-title">Opciones de Reporte</h2>
                <div class="options-grid">
                    <div class="option-group">
                        <label for="format" class="option-label">Formato</label>
                        <select id="format" name="format" class="option-select">
                            <option value="pdf">PDF</option>
                            <option value="excel">Excel</option>
                            <option value="csv">CSV</option>
                        </select>
                    </div>
                    <div class="option-group">
                        <label for="groupBy" class="option-label">Agrupar por</label>
                        <select id="groupBy" name="groupBy" class="option-select">
                            <option value="day">Día</option>
                            <option value="week">Semana</option>
                            <option value="month" selected>Mes</option>
                            <option value="quarter">Trimestre</option>
                        </select>
                    </div>
                    <div class="option-group">
                        <label for="includeCharts" class="option-label">Incluir Gráficos</label>
                        <select id="includeCharts" name="includeCharts" class="option-select">
                            <option value="yes" selected>Sí</option>
                            <option value="no">No</option>
                        </select>
                    </div>
                    <div class="option-group">
                        <label for="detailLevel" class="option-label">Nivel de Detalle</label>
                        <select id="detailLevel" name="detailLevel" class="option-select">
                            <option value="summary">Resumen</option>
                            <option value="detailed" selected>Detallado</option>
                            <option value="comprehensive">Exhaustivo</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <button type="submit" class="generate-button">
                <i class="fas fa-file-download generate-button-icon"></i>
                <span>Generar Reporte</span>
            </button>
        </form>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/admin/reports.js') }}"></script>
@endsection
