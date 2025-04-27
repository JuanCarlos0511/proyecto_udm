@extends('layouts.admin')

@section('title', 'Tablero')

@section('page-title', 'Tablero')

@section('breadcrumb')
    <span class="breadcrumb-separator">/</span>
    <span>Tablero</span>
@endsection

@section('styles')
<style>
    .dashboard-container {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        margin-bottom: 20px;
    }
    
    .dashboard-card {
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }
    
    .dashboard-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 20px;
        border-bottom: 1px solid #eaeaea;
    }
    
    .dashboard-card-title {
        font-size: 16px;
        font-weight: 600;
        color: #333;
        margin: 0;
    }
    
    .dashboard-card-content {
        padding: 20px;
    }
    
    .dashboard-stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 15px;
        margin-bottom: 20px;
    }
    
    .stat-card {
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        padding: 20px;
        text-align: center;
    }
    
    .stat-value {
        font-size: 24px;
        font-weight: 600;
        color: #333;
        margin-bottom: 5px;
    }
    
    .stat-label {
        font-size: 14px;
        color: #666;
    }
    
    .appointment-item {
        display: flex;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #eaeaea;
    }
    
    .appointment-item:last-child {
        border-bottom: none;
    }
    
    .appointment-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        overflow: hidden;
        margin-right: 15px;
    }
    
    .appointment-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .appointment-details {
        flex-grow: 1;
    }
    
    .appointment-name {
        font-weight: 500;
        margin-bottom: 3px;
    }
    
    .appointment-info {
        font-size: 13px;
        color: #666;
    }
    
    .appointment-time {
        font-size: 13px;
        color: #666;
        text-align: right;
    }
    
    .patient-item {
        display: flex;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #eaeaea;
    }
    
    .patient-item:last-child {
        border-bottom: none;
    }
    
    .patient-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        overflow: hidden;
        margin-right: 15px;
    }
    
    .patient-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .patient-details {
        flex-grow: 1;
    }
    
    .patient-name {
        font-weight: 500;
        margin-bottom: 3px;
    }
    
    .patient-info {
        font-size: 13px;
        color: #666;
    }
    
    .patient-status {
        font-size: 12px;
        padding: 3px 8px;
        border-radius: 12px;
        background-color: #e6f7ff;
        color: #0070f3;
    }
    
    .see-all {
        text-align: center;
        margin-top: 15px;
    }
    
    .see-all a {
        color: #666;
        font-size: 14px;
        text-decoration: none;
    }
    
    .see-all a:hover {
        text-decoration: underline;
    }
    
    .edit-button {
        background-color: transparent;
        border: none;
        color: #6c5dd3;
        font-size: 14px;
        cursor: pointer;
        padding: 0;
        display: inline-flex;
        align-items: center;
        text-decoration: none;
    }
    
    .edit-button:hover {
        text-decoration: underline;
    }
</style>
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
