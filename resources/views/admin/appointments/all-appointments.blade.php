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
    <link rel="stylesheet" href="{{ asset('css/admin/appointments.css') }}">
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
        <span class="pagination-separator">...</span>
        <a href="#" class="pagination-item">10</a>
        <a href="#" class="pagination-item"><i class="fas fa-chevron-right"></i></a>
    </div>
</div>
@endsection
