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
<style>
    .activity-container {
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        padding: 20px;
        margin-bottom: 20px;
    }
    
    .activity-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid #eaeaea;
    }
    
    .activity-title {
        font-size: 18px;
        font-weight: 600;
        color: #333;
    }
    
    .activity-filters {
        display: flex;
        gap: 10px;
    }
    
    .activity-filter {
        padding: 8px 15px;
        border-radius: 8px;
        background-color: #f5f6fa;
        font-size: 14px;
        color: #333;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .activity-filter:hover {
        background-color: #eef0f7;
    }
    
    .activity-filter.active {
        background-color: #6c5dd3;
        color: white;
    }
    
    .activity-list {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }
    
    .activity-item {
        display: flex;
        gap: 15px;
        padding-bottom: 15px;
        border-bottom: 1px solid #eaeaea;
    }
    
    .activity-item:last-child {
        border-bottom: none;
    }
    
    .activity-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        overflow: hidden;
    }
    
    .activity-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .activity-content {
        flex-grow: 1;
    }
    
    .activity-user {
        font-weight: 500;
        margin-bottom: 5px;
    }
    
    .activity-text {
        font-size: 14px;
        color: #333;
        margin-bottom: 5px;
    }
    
    .activity-date {
        font-size: 12px;
        color: #666;
    }
    
    .activity-pagination {
        display: flex;
        justify-content: center;
        gap: 5px;
        margin-top: 20px;
    }
    
    .pagination-item {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 35px;
        height: 35px;
        border-radius: 8px;
        background-color: #f5f6fa;
        color: #333;
        text-decoration: none;
        font-size: 14px;
    }
    
    .pagination-item.active {
        background-color: #6c5dd3;
        color: white;
    }
    
    .pagination-separator {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 35px;
        height: 35px;
        font-size: 14px;
        color: #666;
    }
</style>
@endsection

@section('content')
<div class="activity-container">
    <div class="activity-header">
        <h2 class="activity-title">Toda la Actividad</h2>
        <div class="activity-filters">
            <div class="activity-filter active">Todos</div>
            <div class="activity-filter">Comentarios</div>
            <div class="activity-filter">Tareas</div>
            <div class="activity-filter">Proyectos</div>
        </div>
    </div>
    
    <div class="activity-list">
        <div class="activity-item">
            <div class="activity-avatar">
                <img src="{{ asset('assets/avatar1.png') }}" alt="Usuario">
            </div>
            <div class="activity-content">
                <div class="activity-user">María González</div>
                <div class="activity-text">Completó la tarea "Revisión de documentos del paciente"</div>
                <div class="activity-date">Hoy, 10:30 AM</div>
            </div>
        </div>
        
        <div class="activity-item">
            <div class="activity-avatar">
                <img src="{{ asset('assets/avatar2.png') }}" alt="Usuario">
            </div>
            <div class="activity-content">
                <div class="activity-user">Carlos Rodríguez</div>
                <div class="activity-text">Comentó en el proyecto "Implementación de nuevo sistema"</div>
                <div class="activity-date">Hoy, 9:15 AM</div>
            </div>
        </div>
        
        <div class="activity-item">
            <div class="activity-avatar">
                <img src="{{ asset('assets/avatar3.png') }}" alt="Usuario">
            </div>
            <div class="activity-content">
                <div class="activity-user">Ana Martínez</div>
                <div class="activity-text">Creó una nueva tarea "Preparar informe mensual"</div>
                <div class="activity-date">Ayer, 4:45 PM</div>
            </div>
        </div>
        
        <div class="activity-item">
            <div class="activity-avatar">
                <img src="{{ asset('assets/avatar4.png') }}" alt="Usuario">
            </div>
            <div class="activity-content">
                <div class="activity-user">José López</div>
                <div class="activity-text">Actualizó el estado del proyecto "Campaña de marketing"</div>
                <div class="activity-date">Ayer, 2:30 PM</div>
            </div>
        </div>
        
        <div class="activity-item">
            <div class="activity-avatar">
                <img src="{{ asset('assets/avatar5.png') }}" alt="Usuario">
            </div>
            <div class="activity-content">
                <div class="activity-user">Laura Sánchez</div>
                <div class="activity-text">Completó la tarea "Revisión de presupuesto"</div>
                <div class="activity-date">Ayer, 11:20 AM</div>
            </div>
        </div>
        
        <div class="activity-item">
            <div class="activity-avatar">
                <img src="{{ asset('assets/avatar6.png') }}" alt="Usuario">
            </div>
            <div class="activity-content">
                <div class="activity-user">Miguel Fernández</div>
                <div class="activity-text">Comentó en la tarea "Diseño de nueva interfaz"</div>
                <div class="activity-date">20 Sep, 2022</div>
            </div>
        </div>
        
        <div class="activity-item">
            <div class="activity-avatar">
                <img src="{{ asset('assets/avatar7.png') }}" alt="Usuario">
            </div>
            <div class="activity-content">
                <div class="activity-user">Elena Torres</div>
                <div class="activity-text">Creó un nuevo proyecto "Optimización de procesos"</div>
                <div class="activity-date">19 Sep, 2022</div>
            </div>
        </div>
        
        <div class="activity-item">
            <div class="activity-avatar">
                <img src="{{ asset('assets/avatar8.png') }}" alt="Usuario">
            </div>
            <div class="activity-content">
                <div class="activity-user">Roberto Díaz</div>
                <div class="activity-text">Actualizó la documentación del proyecto "Sistema de gestión"</div>
                <div class="activity-date">18 Sep, 2022</div>
            </div>
        </div>
    </div>
    
    <div class="activity-pagination">
        <a href="#" class="pagination-item"><i class="fas fa-chevron-left"></i></a>
        <a href="#" class="pagination-item active">1</a>
        <a href="#" class="pagination-item">2</a>
        <a href="#" class="pagination-item">3</a>
        <a href="#" class="pagination-item">4</a>
        <a href="#" class="pagination-item">5</a>
        <span class="pagination-separator">...</span>
        <a href="#" class="pagination-item">10</a>
        <a href="#" class="pagination-item"><i class="fas fa-chevron-right"></i></a>
    </div>
</div>
@endsection
