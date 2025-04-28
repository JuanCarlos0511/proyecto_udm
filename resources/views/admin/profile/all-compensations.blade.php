@extends('layouts.admin')

@section('title', 'Todas las Compensaciones')

@section('page-title', 'Todas las Compensaciones')

@section('breadcrumb')
    <span class="breadcrumb-separator">/</span>
    <span><a href="{{ url('admin/perfil') }}">Perfil</a></span>
    <span class="breadcrumb-separator">/</span>
    <span>Todas las Compensaciones</span>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/profile-compensations.css') }}">
@endsection

@section('content')
    <div class="compensations-container">
        <div class="compensations-header">
            <h2 class="compensations-title">Historial de Compensaciones</h2>
            <div class="compensations-actions">
                <div class="compensations-filter">
                    <i class="fas fa-filter compensations-filter-icon"></i>
                    <span class="compensations-filter-text">Filtrar</span>
                </div>
                <div class="compensations-export">
                    <i class="fas fa-download compensations-export-icon"></i>
                    <span class="compensations-export-text">Exportar</span>
                </div>
            </div>
        </div>
        <table class="compensations-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Fecha</th>
                    <th>Paciente</th>
                    <th>Servicio</th>
                    <th>Monto</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr data-id="COMP-001">
                    <td>#COMP-001</td>
                    <td>24 Ago 2022</td>
                    <td>María González</td>
                    <td>Consulta General</td>
                    <td>$150.00</td>
                    <td><span class="compensation-status status-paid">Pagada</span></td>
                    <td>
                        <div class="compensation-actions">
                            <div class="action-button" data-action="view">
                                <i class="fas fa-eye"></i>
                            </div>
                            <div class="action-button" data-action="download">
                                <i class="fas fa-download"></i>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr data-id="COMP-002">
                    <td>#COMP-002</td>
                    <td>25 Ago 2022</td>
                    <td>Carlos Rodríguez</td>
                    <td>Consulta Especializada</td>
                    <td>$200.00</td>
                    <td><span class="compensation-status status-paid">Pagada</span></td>
                    <td>
                        <div class="compensation-actions">
                            <div class="action-button" data-action="view">
                                <i class="fas fa-eye"></i>
                            </div>
                            <div class="action-button" data-action="download">
                                <i class="fas fa-download"></i>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr data-id="COMP-003">
                    <td>#COMP-003</td>
                    <td>26 Ago 2022</td>
                    <td>Ana Martínez</td>
                    <td>Procedimiento</td>
                    <td>$350.00</td>
                    <td><span class="compensation-status status-pending">Pendiente</span></td>
                    <td>
                        <div class="compensation-actions">
                            <div class="action-button" data-action="view">
                                <i class="fas fa-eye"></i>
                            </div>
                            <div class="action-button" data-action="download">
                                <i class="fas fa-download"></i>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr data-id="COMP-004">
                    <td>#COMP-004</td>
                    <td>27 Ago 2022</td>
                    <td>José López</td>
                    <td>Consulta General</td>
                    <td>$150.00</td>
                    <td><span class="compensation-status status-paid">Pagada</span></td>
                    <td>
                        <div class="compensation-actions">
                            <div class="action-button" data-action="view">
                                <i class="fas fa-eye"></i>
                            </div>
                            <div class="action-button" data-action="download">
                                <i class="fas fa-download"></i>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr data-id="COMP-005">
                    <td>#COMP-005</td>
                    <td>28 Ago 2022</td>
                    <td>Laura Sánchez</td>
                    <td>Procedimiento</td>
                    <td>$300.00</td>
                    <td><span class="compensation-status status-pending">Pendiente</span></td>
                    <td>
                        <div class="compensation-actions">
                            <div class="action-button" data-action="view">
                                <i class="fas fa-eye"></i>
                            </div>
                            <div class="action-button" data-action="download">
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
@endsection

@section('scripts')
    <script src="{{ asset('js/admin/profile-compensations.js') }}"></script>
@endsection
