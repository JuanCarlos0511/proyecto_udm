@extends('layouts.admin')

@section('title', 'Todas las Citas')

@section('page-title', 'Todas las Citas')

@section('breadcrumb')
    <span class="breadcrumb-separator">/</span>
    <span><a href="{{ url('admin/tablero') }}">Tablero</a></span>
    <span class="breadcrumb-separator">/</span>
    <span>Todas las Citas</span>
@endsection

@section('styles')
<style>
    .appointments-container {
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        padding: 20px;
        margin-bottom: 20px;
    }
    
    .appointments-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    
    .appointments-title {
        font-size: 18px;
        font-weight: 600;
        color: #333;
    }
    
    .appointments-actions {
        display: flex;
        gap: 10px;
    }
    
    .appointments-filter {
        display: flex;
        align-items: center;
        gap: 8px;
        background-color: #f5f6fa;
        border-radius: 8px;
        padding: 8px 15px;
        cursor: pointer;
    }
    
    .appointments-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .appointments-table th {
        text-align: left;
        padding: 12px 15px;
        font-size: 14px;
        font-weight: 500;
        color: #666;
        border-bottom: 1px solid #e0e0e0;
    }
    
    .appointments-table td {
        padding: 12px 15px;
        font-size: 14px;
        color: #333;
        border-bottom: 1px solid #e0e0e0;
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
        background-color: #e6f7ee;
        color: #00a389;
    }
    
    .status-pending {
        background-color: #fff4e5;
        color: #ff9500;
    }
    
    .status-cancelled {
        background-color: #fee7e7;
        color: #ff4d4d;
    }
    
    .pagination {
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
<div class="appointments-container">
    <div class="appointments-header">
        <h2 class="appointments-title">Todas las Citas</h2>
        <div class="appointments-actions">
            <div class="appointments-filter">
                <i class="fas fa-filter"></i>
                <span>Filtrar</span>
            </div>
        </div>
    </div>
    
    <table class="appointments-table">
        <thead>
            <tr>
                <th>Paciente</th>
                <th>Doctor</th>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Estado</th>
                <th>Servicio</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>María González</td>
                <td>Dr. Juan Pérez</td>
                <td>15/09/2022</td>
                <td>09:00 AM</td>
                <td><span class="appointment-status status-completed">Completada</span></td>
                <td>Consulta General</td>
                <td><i class="fas fa-ellipsis-h"></i></td>
            </tr>
            <tr>
                <td>Carlos Rodríguez</td>
                <td>Dra. María López</td>
                <td>16/09/2022</td>
                <td>10:30 AM</td>
                <td><span class="appointment-status status-pending">Pendiente</span></td>
                <td>Limpieza Dental</td>
                <td><i class="fas fa-ellipsis-h"></i></td>
            </tr>
            <tr>
                <td>Ana Martínez</td>
                <td>Dr. Carlos Rodríguez</td>
                <td>17/09/2022</td>
                <td>11:00 AM</td>
                <td><span class="appointment-status status-cancelled">Cancelada</span></td>
                <td>Extracción</td>
                <td><i class="fas fa-ellipsis-h"></i></td>
            </tr>
            <tr>
                <td>José López</td>
                <td>Dr. Juan Pérez</td>
                <td>18/09/2022</td>
                <td>09:30 AM</td>
                <td><span class="appointment-status status-completed">Completada</span></td>
                <td>Consulta General</td>
                <td><i class="fas fa-ellipsis-h"></i></td>
            </tr>
            <tr>
                <td>Laura Sánchez</td>
                <td>Dra. María López</td>
                <td>19/09/2022</td>
                <td>10:00 AM</td>
                <td><span class="appointment-status status-pending">Pendiente</span></td>
                <td>Ortodoncia</td>
                <td><i class="fas fa-ellipsis-h"></i></td>
            </tr>
        </tbody>
    </table>
    
    <div class="pagination">
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
