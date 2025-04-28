@extends('layouts.admin')

@section('title', 'Toda la Actividad')

@section('page-title', 'Toda la Actividad')

@section('breadcrumb')
    <span class="breadcrumb-separator">/</span>
    <span><a href="{{ url('admin/perfil') }}">Perfil</a></span>
    <span class="breadcrumb-separator">/</span>
    <span>Toda la Actividad</span>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/profile-activity.css') }}">
@endsection

@section('content')
    <div class="activity-container">
        <div class="activity-header">
            <h2 class="activity-title">Historial de Actividad</h2>
            <div class="activity-filters">
                <div class="activity-filter active" data-filter="all">Todas</div>
                <div class="activity-filter" data-filter="appointments">Citas</div>
                <div class="activity-filter" data-filter="patients">Pacientes</div>
                <div class="activity-filter" data-filter="billing">Facturación</div>
            </div>
        </div>
        <div class="activity-timeline">
            <div class="timeline-item">
                <div class="timeline-dot"></div>
                <div class="timeline-content">
                    <div class="timeline-header">
                        <div class="timeline-title">Cita Agendada</div>
                        <div class="timeline-time">Hoy, 10:30 AM</div>
                    </div>
                    <div class="timeline-description">
                        Has agendado una cita para María González para consulta general el 28 de Agosto a las 10:30 AM.
                    </div>
                </div>
            </div>
            <div class="timeline-item">
                <div class="timeline-dot"></div>
                <div class="timeline-content">
                    <div class="timeline-header">
                        <div class="timeline-title">Factura Generada</div>
                        <div class="timeline-time">Hoy, 9:45 AM</div>
                    </div>
                    <div class="timeline-description">
                        Has generado la factura #INV-005 para Laura Sánchez por un monto de $450.00.
                    </div>
                </div>
            </div>
            <div class="timeline-item">
                <div class="timeline-dot"></div>
                <div class="timeline-content">
                    <div class="timeline-header">
                        <div class="timeline-title">Paciente Actualizado</div>
                        <div class="timeline-time">Ayer, 3:20 PM</div>
                    </div>
                    <div class="timeline-description">
                        Has actualizado la información de contacto para el paciente Carlos Rodríguez.
                    </div>
                </div>
            </div>
            <div class="timeline-item">
                <div class="timeline-dot"></div>
                <div class="timeline-content">
                    <div class="timeline-header">
                        <div class="timeline-title">Cita Completada</div>
                        <div class="timeline-time">Ayer, 11:15 AM</div>
                    </div>
                    <div class="timeline-description">
                        Has marcado como completada la cita con José López para consulta de seguimiento.
                    </div>
                </div>
            </div>
            <div class="timeline-item">
                <div class="timeline-dot"></div>
                <div class="timeline-content">
                    <div class="timeline-header">
                        <div class="timeline-title">Nuevo Paciente</div>
                        <div class="timeline-time">24 Ago, 2:30 PM</div>
                    </div>
                    <div class="timeline-description">
                        Has registrado a Ana Martínez como nueva paciente en el sistema.
                    </div>
                </div>
            </div>
        </div>
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
    <script src="{{ asset('js/admin/profile-activity.js') }}"></script>
@endsection
