@extends('layouts.admin')

@section('title', 'Tablero')

@section('page-title', 'Tablero')

@section('breadcrumb')
    <span class="breadcrumb-separator">/</span>
    <span>Tablero</span>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/tablero.css') }}">
@endsection

@section('content')
<div class="dashboard-stats">
    <div class="stat-card">
        <div class="stat-value">42</div>
        <div class="stat-label">Citas Hoy</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">156</div>
        <div class="stat-label">Pacientes Activos</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">28</div>
        <div class="stat-label">Pacientes en Seguimiento</div>
    </div>
</div>

<div class="dashboard-container">
    <div class="dashboard-card">
        <div class="dashboard-card-header">
            <h3 class="dashboard-card-title">Citas Recientes</h3>
            <a href="{{ url('admin/tablero/citas-todas') }}" class="edit-button">Ver todo</a>
        </div>
        <div class="dashboard-card-content">
            <div class="appointment-item">
                <div class="appointment-avatar">
                    <img src="{{ asset('assets/avatar1.png') }}" alt="Paciente">
                </div>
                <div class="appointment-details">
                    <div class="appointment-name">María González</div>
                    <div class="appointment-info">Consulta General</div>
                </div>
                <div class="appointment-time">
                    Hoy, 10:30 AM
                </div>
            </div>
            <div class="appointment-item">
                <div class="appointment-avatar">
                    <img src="{{ asset('assets/avatar2.png') }}" alt="Paciente">
                </div>
                <div class="appointment-details">
                    <div class="appointment-name">Carlos Rodríguez</div>
                    <div class="appointment-info">Seguimiento Tratamiento</div>
                </div>
                <div class="appointment-time">
                    Hoy, 11:45 AM
                </div>
            </div>
            <div class="appointment-item">
                <div class="appointment-avatar">
                    <img src="{{ asset('assets/avatar3.png') }}" alt="Paciente">
                </div>
                <div class="appointment-details">
                    <div class="appointment-name">Ana Martínez</div>
                    <div class="appointment-info">Revisión Dental</div>
                </div>
                <div class="appointment-time">
                    Hoy, 2:15 PM
                </div>
            </div>
            <div class="appointment-item">
                <div class="appointment-avatar">
                    <img src="{{ asset('assets/avatar4.png') }}" alt="Paciente">
                </div>
                <div class="appointment-details">
                    <div class="appointment-name">José López</div>
                    <div class="appointment-info">Consulta General</div>
                </div>
                <div class="appointment-time">
                    Mañana, 9:00 AM
                </div>
            </div>
            <div class="see-all">
                <a href="{{ url('admin/tablero/citas-todas') }}">Ver todas las citas</a>
            </div>
        </div>
    </div>
    
    <div class="dashboard-card">
        <div class="dashboard-card-header">
            <h3 class="dashboard-card-title">Pacientes en Seguimiento</h3>
            <a href="{{ url('admin/tablero/seguimiento-todos') }}" class="edit-button">Ver todo</a>
        </div>
        <div class="dashboard-card-content">
            <div class="patient-item">
                <div class="patient-avatar">
                    <img src="{{ asset('assets/avatar5.png') }}" alt="Paciente">
                </div>
                <div class="patient-details">
                    <div class="patient-name">Laura Sánchez</div>
                    <div class="patient-info">Tratamiento Dental</div>
                </div>
                <div class="patient-status">
                    En Progreso
                </div>
            </div>
            <div class="patient-item">
                <div class="patient-avatar">
                    <img src="{{ asset('assets/avatar6.png') }}" alt="Paciente">
                </div>
                <div class="patient-details">
                    <div class="patient-name">Miguel Fernández</div>
                    <div class="patient-info">Ortodoncia</div>
                </div>
                <div class="patient-status">
                    En Progreso
                </div>
            </div>
            <div class="patient-item">
                <div class="patient-avatar">
                    <img src="{{ asset('assets/avatar7.png') }}" alt="Paciente">
                </div>
                <div class="patient-details">
                    <div class="patient-name">Elena Torres</div>
                    <div class="patient-info">Implante Dental</div>
                </div>
                <div class="patient-status">
                    Crítico
                </div>
            </div>
            <div class="patient-item">
                <div class="patient-avatar">
                    <img src="{{ asset('assets/avatar8.png') }}" alt="Paciente">
                </div>
                <div class="patient-details">
                    <div class="patient-name">Roberto Díaz</div>
                    <div class="patient-info">Endodoncia</div>
                </div>
                <div class="patient-status">
                    En Progreso
                </div>
            </div>
            <div class="see-all">
                <a href="{{ url('admin/tablero/seguimiento-todos') }}">Ver todos los pacientes</a>
            </div>
        </div>
    </div>
</div>
@endsection
