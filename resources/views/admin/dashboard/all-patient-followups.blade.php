@extends('layouts.admin')

@section('title', 'Todos los Pacientes en Seguimiento')

@section('page-title', 'Todos los Pacientes en Seguimiento')

@section('breadcrumb')
    <span class="breadcrumb-separator">/</span>
    <span><a href="{{ url('admin/tablero') }}">Tablero</a></span>
    <span class="breadcrumb-separator">/</span>
    <span>Todos los Pacientes en Seguimiento</span>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/patient-followup.css') }}">
@endsection

@section('content')
<div class="patients-container">
    <div class="patients-header">
        <h2 class="patients-title">Todos los Pacientes en Seguimiento</h2>
        <div class="patients-actions">
            <div class="patients-filter">
                <i class="fas fa-filter"></i>
                <span>Filtrar</span>
            </div>
        </div>
    </div>
    
    <table class="patients-table">
        <thead>
            <tr>
                <th>Paciente</th>
                <th>Doctor</th>
                <th>Tratamiento</th>
                <th>Fecha de inicio</th>
                <th>Última visita</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <div class="patient-info">
                        <div class="patient-avatar">
                            <img src="{{ asset('assets/avatar5.png') }}" alt="Paciente">
                        </div>
                        <span>Laura Sánchez</span>
                    </div>
                </td>
                <td>Dr. Juan Pérez</td>
                <td>Tratamiento Dental</td>
                <td>10/08/2022</td>
                <td>15/09/2022</td>
                <td><span class="patient-status status-progress">En Progreso</span></td>
                <td><i class="fas fa-ellipsis-h"></i></td>
            </tr>
            <tr>
                <td>
                    <div class="patient-info">
                        <div class="patient-avatar">
                            <img src="{{ asset('assets/avatar6.png') }}" alt="Paciente">
                        </div>
                        <span>Miguel Fernández</span>
                    </div>
                </td>
                <td>Dra. María López</td>
                <td>Ortodoncia</td>
                <td>05/07/2022</td>
                <td>20/09/2022</td>
                <td><span class="patient-status status-progress">En Progreso</span></td>
                <td><i class="fas fa-ellipsis-h"></i></td>
            </tr>
            <tr>
                <td>
                    <div class="patient-info">
                        <div class="patient-avatar">
                            <img src="{{ asset('assets/avatar7.png') }}" alt="Paciente">
                        </div>
                        <span>Elena Torres</span>
                    </div>
                </td>
                <td>Dr. Carlos Rodríguez</td>
                <td>Implante Dental</td>
                <td>15/06/2022</td>
                <td>12/09/2022</td>
                <td><span class="patient-status status-critical">Crítico</span></td>
                <td><i class="fas fa-ellipsis-h"></i></td>
            </tr>
            <tr>
                <td>
                    <div class="patient-info">
                        <div class="patient-avatar">
                            <img src="{{ asset('assets/avatar8.png') }}" alt="Paciente">
                        </div>
                        <span>Roberto Díaz</span>
                    </div>
                </td>
                <td>Dr. Juan Pérez</td>
                <td>Endodoncia</td>
                <td>20/07/2022</td>
                <td>18/09/2022</td>
                <td><span class="patient-status status-progress">En Progreso</span></td>
                <td><i class="fas fa-ellipsis-h"></i></td>
            </tr>
            <tr>
                <td>
                    <div class="patient-info">
                        <div class="patient-avatar">
                            <img src="{{ asset('assets/avatar1.png') }}" alt="Paciente">
                        </div>
                        <span>Carmen Ruiz</span>
                    </div>
                </td>
                <td>Dra. María López</td>
                <td>Tratamiento Periodontal</td>
                <td>01/08/2022</td>
                <td>10/09/2022</td>
                <td><span class="patient-status status-stable">Estable</span></td>
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
