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
                <th>Fecha</th>
                <th>Hora</th>
                <th>Estado</th>
                <th>Asunto</th>
                <th>Modalidad</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($appointments as $appointment)
            <tr>
                <td>{{ $appointment->user->name }}</td>
                <td>{{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($appointment->date)->format('h:i A') }}</td>
                <td>
                    <span class="appointment-status status-{{ strtolower($appointment->status) }}">
                        {{ $appointment->status }}
                    </span>
                </td>
                <td>{{ $appointment->subject }}</td>
                <td>{{ $appointment->modality }}</td>
                <td class="actions-column">
                    @if($appointment->status === 'Solicitado')
                        <form action="{{ route('admin.appointments.update-status', $appointment->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" name="status" value="Agendado" class="btn btn-success btn-sm" title="Aceptar cita">
                                <i class="fas fa-check"></i>
                            </button>
                            <button type="submit" name="status" value="Cancelado" class="btn btn-danger btn-sm" title="Rechazar cita">
                                <i class="fas fa-times"></i>
                            </button>
                        </form>
                    @endif
                    <a href="{{ route('admin.appointments.edit', $appointment->id) }}" class="btn btn-primary btn-sm" title="Editar cita">
                        <i class="fas fa-edit"></i>
                    </a>
                </td>
            </tr>
            @endforeach

            @if($appointments->isEmpty())
            <tr>
                <td colspan="7" class="text-center">No hay citas pendientes</td>
            </tr>
            @endif
        </tbody>
    </table>
    
    <div class="pagination">
        @if ($appointments->onFirstPage())
            <span class="pagination-item disabled"><i class="fas fa-chevron-left"></i></span>
        @else
            <a href="{{ $appointments->previousPageUrl() }}" class="pagination-item"><i class="fas fa-chevron-left"></i></a>
        @endif
        
        @for ($i = 1; $i <= $appointments->lastPage(); $i++)
            @if ($i == $appointments->currentPage())
                <a href="{{ $appointments->url($i) }}" class="pagination-item active">{{ $i }}</a>
            @else
                @if ($i <= 3 || $i > $appointments->lastPage() - 2 || abs($i - $appointments->currentPage()) < 2)
                    <a href="{{ $appointments->url($i) }}" class="pagination-item">{{ $i }}</a>
                @elseif (abs($i - $appointments->currentPage()) == 2)
                    <span class="pagination-separator">...</span>
                @endif
            @endif
        @endfor
        
        @if ($appointments->hasMorePages())
            <a href="{{ $appointments->nextPageUrl() }}" class="pagination-item"><i class="fas fa-chevron-right"></i></a>
        @else
            <span class="pagination-item disabled"><i class="fas fa-chevron-right"></i></span>
        @endif
    </div>
</div>
@endsection
