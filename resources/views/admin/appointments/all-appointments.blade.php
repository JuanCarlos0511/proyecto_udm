@extends('layouts.admin')

@section('title', 'Todas las Citas')

@section('page-title', 'Todas las Citas')

@section('breadcrumb')
    <span class="breadcrumb-separator">/</span>
    <span><a href="{{ route('admin.dashboard') }}">Tablero</a></span>
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
            @forelse($appointments as $appointment)
            <tr class="appointment-row" data-id="{{ $appointment->id }}">
                <td>{{ $appointment->user->name }}</td>
                <td>{{ $appointment->doctor ? $appointment->doctor->name : 'Sin asignar' }}</td>
                <td>{{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($appointment->date)->format('h:i A') }}</td>
                <td>
                    <span class="appointment-status status-{{ strtolower($appointment->status) }}">
                        {{ $appointment->status }}
                    </span>
                </td>
                <td>{{ $appointment->subject }}</td>
                <td class="actions-cell">
                    <div class="dropdown">
                        <button class="dropdown-toggle">
                            <i class="fas fa-ellipsis-h"></i>
                        </button>
                        <div class="dropdown-menu">
                            @if($appointment->canBeAccepted())
                                <a href="#" class="dropdown-item accept-appointment" data-id="{{ $appointment->id }}">
                                    <i class="fas fa-check"></i> Aceptar
                                </a>
                            @endif
                            @if($appointment->canBeCancelled())
                                <a href="#" class="dropdown-item cancel-appointment" data-id="{{ $appointment->id }}">
                                    <i class="fas fa-times"></i> Cancelar
                                </a>
                            @endif
                            @if($appointment->status !== 'Completado' && $appointment->status !== 'Cancelado')
                                <a href="javascript:void(0);" class="dropdown-item edit-appointment" onclick="toggleAppointmentDetails('{{ $appointment->id }}')">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                            @endif
                        </div>
                    </div>
                </td>
            </tr>
            <tr class="appointment-details" id="details-{{ $appointment->id }}" style="display: none;">
                <td colspan="7">
                    <form class="appointment-edit-form" onsubmit="event.preventDefault(); updateAppointment('{{ $appointment->id }}', this);">
                        @csrf
                        <h3 class="form-title">Editar Cita</h3>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="date-{{ $appointment->id }}">Fecha</label>
                                <input type="date" class="form-control" id="date-{{ $appointment->id }}" name="date" 
                                    value="{{ \Carbon\Carbon::parse($appointment->date)->format('Y-m-d') }}">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="time-{{ $appointment->id }}">Hora</label>
                                <input type="time" class="form-control" id="time-{{ $appointment->id }}" name="time" 
                                    value="{{ \Carbon\Carbon::parse($appointment->date)->format('H:i') }}">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="doctor-{{ $appointment->id }}">Doctor Asignado</label>
                                <select class="form-control" id="doctor-{{ $appointment->id }}" name="doctor_id">
                                    <option value="">Sin asignar</option>
                                    @foreach($doctors as $doctor)
                                        <option value="{{ $doctor->id }}" {{ $appointment->doctor_id == $doctor->id ? 'selected' : '' }}>
                                            {{ $doctor->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-actions">
                            @if($appointment->canBeAccepted())
                                <button type="button" class="btn btn-success" onclick="acceptAppointment('{{ $appointment->id }}'); return false;">Aceptar Cita</button>
                            @endif
                            <button type="submit" class="btn btn-primary">Guardar cambios</button>
                            <button type="button" class="btn btn-secondary" onclick="toggleAppointmentDetails('{{ $appointment->id }}')">Cancelar</button>
                        </div>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">No hay citas disponibles</td>
            </tr>
            @endforelse
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

@section('scripts')
    <script src="{{ asset('js/admin/appointments.js') }}"></script>
@endsection
