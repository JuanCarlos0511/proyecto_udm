@extends('layouts.admin')

@section('title', 'Buzón de Citas')

@section('page-title', 'Buzón de Citas')

@section('breadcrumb')
    <span class="breadcrumb-separator">/</span>
    <span><a href="{{ route('admin.dashboard') }}">Tablero</a></span>
    <span class="breadcrumb-separator">/</span>
    <span>Buzón de Citas</span>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/main.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/appointments.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/dropdown-fix.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/filter-buttons.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout/admin-layout.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
@endsection

@section('content')
<div class="appointments-dashboard">
    <!-- Panel superior con cards de resumen -->
    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-icon bg-primary">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $appointments->where('status', 'Pendiente')->count() }}</h3>
                <p>Citas Pendientes</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon bg-success">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $appointments->where('status', 'Completado')->count() }}</h3>
                <p>Citas Completadas</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon bg-danger">
                <i class="fas fa-times-circle"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $appointments->where('status', 'Cancelado')->count() }}</h3>
                <p>Citas Canceladas</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon bg-info">
                <i class="fas fa-calendar"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $appointments->count() }}</h3>
                <p>Total de Citas</p>
            </div>
        </div>
    </div>

    <div class="appointments-container">
        <div class="appointments-header">
            <h2 class="appointments-title">Buzón de Citas</h2>
            <div class="filter-container">
                <div class="filter-buttons">
                    <span class="filter-label">Filtrar por estado:</span>
                    <div class="btn-group status-filter-buttons">
                        <button type="button" class="btn btn-filter active" data-status="all">
                            <i class="fas fa-list"></i> Todas
                        </button>
                        <button type="button" class="btn btn-filter" data-status="Solicitado">
                            <i class="fas fa-clock"></i> Solicitadas
                            <span class="badge badge-primary">{{ $appointments->where('status', 'Solicitado')->count() }}</span>
                        </button>
                        <button type="button" class="btn btn-filter" data-status="Agendado">
                            <i class="fas fa-calendar-check"></i> Agendadas
                            <span class="badge badge-success">{{ $appointments->where('status', 'Agendado')->count() }}</span>
                        </button>
                        <button type="button" class="btn btn-filter" data-status="Completado">
                            <i class="fas fa-check-circle"></i> Completadas
                            <span class="badge badge-info">{{ $appointments->where('status', 'Completado')->count() }}</span>
                        </button>
                        <button type="button" class="btn btn-filter" data-status="Cancelado">
                            <i class="fas fa-times-circle"></i> Canceladas
                            <span class="badge badge-danger">{{ $appointments->where('status', 'Cancelado')->count() }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="appointments-table">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Paciente</th>
                        <th>Doctor</th>
                        <th>Modalidad</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($appointments as $appointment)
                    <tr class="appointment-row" data-id="{{ $appointment->id }}">
                        <td>
                            <div class="date-cell">
                                <div class="date-primary">{{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y') }}</div>
                                <div class="date-secondary">{{ \Carbon\Carbon::parse($appointment->date)->format('h:i A') }}</div>
                            </div>
                        </td>
                        <td>
                            <div class="user-info">
                                <span class="user-name">{{ $appointment->user->name }}</span>
                                @if($appointment->user->email)
                                    <span class="user-email">{{ $appointment->user->email }}</span>
                                @endif
                            </div>
                        </td>
                        <td>
                            @php
                                // Obtener la cita relacionada del doctor (si existe)
                                $relatedAppointment = null;
                                $doctorName = 'Sin asignar';
                                
                                if ($appointment->appointment_group_id) {
                                    $relatedAppointment = \App\Models\Appointment::where('appointment_group_id', $appointment->appointment_group_id)
                                        ->whereHas('user', function($query) {
                                            $query->where('role', 'doctor');
                                        })
                                        ->with('user')
                                        ->first();
                                        
                                    if ($relatedAppointment && $relatedAppointment->user) {
                                        $doctorName = $relatedAppointment->user->name;
                                    }
                                }
                            @endphp
                            <div class="doctor-info">
                                <span class="doctor-name">{{ $doctorName }}</span>
                            </div>
                        </td>
                        <td>{{ $appointment->modality ?? 'Presencial' }}</td>
                        <td>
                            <span class="appointment-status status-{{ strtolower($appointment->status) }}">
                                <i class="status-icon fas {{ strtolower($appointment->status) === 'completado' ? 'fa-check-circle' : (strtolower($appointment->status) === 'cancelado' ? 'fa-times-circle' : 'fa-clock') }}"></i>
                                {{ $appointment->status }}
                            </span>
                        </td>
                        <td class="actions-cell">
                            <div class="action-buttons">
                                @if($appointment->status == 'Solicitado')
                                    <button type="button" class="btn btn-action btn-accept" 
                                        onclick="acceptAppointment('{{ $appointment->id }}')" title="Aceptar cita">
                                        <i class="fas fa-check"></i> Aceptar
                                    </button>
                                    <button type="button" class="btn btn-action btn-edit" 
                                        onclick="toggleAppointmentDetails('{{ $appointment->id }}')" title="Editar cita">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                @elseif($appointment->status == 'Agendado')
                                    <button type="button" class="btn btn-action btn-start" 
                                        onclick="showStartAppointmentModal('{{ $appointment->id }}')" title="Iniciar cita">
                                        <i class="fas fa-play-circle"></i> Iniciar
                                    </button>
                                @elseif($appointment->status == 'Completado' || $appointment->status == 'Cancelado')
                                    <button type="button" class="btn btn-action btn-view" 
                                        onclick="viewAppointmentDetails('{{ $appointment->id }}')" title="Ver detalles">
                                        <i class="fas fa-eye"></i> Detalles
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    <tr class="appointment-details" id="details-{{ $appointment->id }}" style="display: none;">
                        <td colspan="6">
                            <div class="edit-container">
                                <form class="appointment-edit-form" id="form-{{ $appointment->id }}" method="POST" action="/admin/citas/{{ $appointment->id }}">
                                    @csrf
                                    <input type="hidden" name="_method" value="PUT">
                                    <input type="hidden" name="action_url" value="/admin/citas/{{ $appointment->id }}">
                                    <div class="edit-header">
                                        <h3 class="form-title">Editar Cita</h3>
                                        <button type="button" class="close-edit-form" onclick="toggleAppointmentDetails('{{ $appointment->id }}')">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                    
                                    <div class="edit-body">
                                        <div class="form-section">
                                            <h4 class="section-title">Información Principal</h4>
                                            <div class="form-row">
                                                <div class="form-group">
                                                    <label for="date-{{ $appointment->id }}">Fecha</label>
                                                    <div class="input-icon-wrapper">
                                                        <input type="date" class="form-control" id="date-{{ $appointment->id }}" name="date" 
                                                            value="{{ \Carbon\Carbon::parse($appointment->date)->format('Y-m-d') }}">
                                                        <i class="fas fa-calendar-alt input-icon"></i>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="time-{{ $appointment->id }}">Hora</label>
                                                    <div class="input-icon-wrapper">
                                                        <input type="time" class="form-control" id="time-{{ $appointment->id }}" name="time" 
                                                            value="{{ \Carbon\Carbon::parse($appointment->date)->format('H:i') }}">
                                                        <i class="fas fa-clock input-icon"></i>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-row">
                                                <div class="form-group full-width">
                                                    <label for="subject-{{ $appointment->id }}">Asunto</label>
                                                    <div class="input-icon-wrapper">
                                                        <input type="text" class="form-control" id="subject-{{ $appointment->id }}" name="subject" 
                                                            value="{{ $appointment->subject }}">
                                                        <i class="fas fa-tag input-icon"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="form-section">
                                            <h4 class="section-title">Detalles de la Cita</h4>
                                            <div class="form-row">
                                                <div class="form-group">
                                                    <label for="doctor-{{ $appointment->id }}">Doctor Asignado</label>
                                                    <div class="select-wrapper">
                                                        <select class="form-control" id="doctor-{{ $appointment->id }}" name="doctor_id">
                                                            <option value="">Sin asignar</option>
                                                            @foreach($doctors as $doctor)
                                                                <option value="{{ $doctor->id }}" {{ $appointment->doctor_id == $doctor->id ? 'selected' : '' }}>
                                                                    {{ $doctor->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <i class="fas fa-user-md select-icon"></i>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="modality-{{ $appointment->id }}">Modalidad</label>
                                                    <div class="select-wrapper">
                                                        <select class="form-control" id="modality-{{ $appointment->id }}" name="modality">
                                                            <option value="Presencial" {{ ($appointment->modality ?? 'Presencial') == 'Presencial' ? 'selected' : '' }}>Presencial</option>
                                                            <option value="Virtual" {{ ($appointment->modality ?? 'Presencial') == 'Virtual' ? 'selected' : '' }}>Virtual</option>
                                                            <option value="Domicilio" {{ ($appointment->modality ?? 'Presencial') == 'Domicilio' ? 'selected' : '' }}>Domicilio</option>
                                                        </select>
                                                        <i class="fas fa-map-marker-alt select-icon"></i>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="price-{{ $appointment->id }}">Precio</label>
                                                    <div class="input-icon-wrapper">
                                                        <input type="number" step="0.01" class="form-control" id="price-{{ $appointment->id }}" name="price" 
                                                            value="{{ $appointment->price }}">
                                                        <i class="fas fa-dollar-sign input-icon"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-actions">
                                        <div class="secondary-actions">
                                            @if($appointment->canBeAccepted())
                                                <button type="button" class="btn btn-success" onclick="acceptAppointment('{{ $appointment->id }}'); return false;">
                                                    <i class="fas fa-check-circle"></i> Aceptar Cita
                                                </button>
                                            @endif
                                        </div>
                                        <div class="primary-actions">
                                            <button type="button" class="btn btn-secondary" onclick="toggleAppointmentDetails('{{ $appointment->id }}')">
                                                <i class="fas fa-times"></i> Cancelar
                                            </button>
                                            <button type="button" class="btn btn-primary" onclick="updateAppointment('{{ $appointment->id }}', document.getElementById('form-{{ $appointment->id }}'))">
                                                <i class="fas fa-save"></i> Guardar cambios
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="empty-message">
                            <div class="empty-state">
                                <i class="fas fa-calendar-times empty-icon"></i>
                                <p>No hay citas disponibles</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Sistema de paginación funcional -->
        <div class="pagination-container">
            <div class="pagination-info">
                Mostrando <span id="showing-from">1</span> a <span id="showing-to">{{ min(7, count($appointments)) }}</span> de <span id="total-items">{{ count($appointments) }}</span> citas
            </div>
            <div class="pagination" id="pagination-container">
                <!-- La paginación se maneja con JavaScript -->
            </div>
        </div>
        

    </div>

    <!-- Modal para iniciar cita -->
    <div id="startAppointmentModal" class="modal fade" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Iniciar Cita</h4>
                    <button type="button" class="close" onclick="closeStartAppointmentModal()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- Datos del paciente -->
                        <div class="col-md-4">
                            <div class="patient-profile-card">
                                <div class="profile-header">
                                    <div class="profile-avatar">
                                        <img id="patientAvatar" src="{{ asset('assets/default-avatar.png') }}" alt="Foto del paciente">
                                    </div>
                                    <h5 id="patientName" class="profile-name">Nombre del Paciente</h5>
                                </div>
                                <div class="profile-details">
                                    <div class="profile-detail-item">
                                        <span class="detail-label"><i class="fas fa-envelope"></i> Email:</span>
                                        <span id="patientEmail" class="detail-value">correo@ejemplo.com</span>
                                    </div>
                                    <div class="profile-detail-item">
                                        <span class="detail-label"><i class="fas fa-phone"></i> Teléfono:</span>
                                        <span id="patientPhone" class="detail-value">123-456-7890</span>
                                    </div>
                                    <div class="profile-detail-item">
                                        <span class="detail-label"><i class="fas fa-calendar"></i> Edad:</span>
                                        <span id="patientAge" class="detail-value">30 años</span>
                                    </div>
                                    <div class="profile-detail-item">
                                        <span class="detail-label"><i class="fas fa-map-marker-alt"></i> Dirección:</span>
                                        <span id="patientAddress" class="detail-value">Calle Principal #123</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Formulario para iniciar cita -->
                        <div class="col-md-8">
                            <form id="startAppointmentForm" method="POST">
                                @csrf
                                <input type="hidden" id="appointmentId" name="appointment_id">
                                
                                <div class="form-group">
                                    <label for="appointmentNotes"><i class="fas fa-clipboard"></i> Notas de la Cita:</label>
                                    <textarea id="appointmentNotes" name="notes" class="form-control" rows="8" placeholder="Escribe las notas de la cita aquí..."></textarea>
                                </div>
                                
                                <div class="form-group">
                                    <label for="appointmentTotal"><i class="fas fa-dollar-sign"></i> Precio Total:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">$</span>
                                        </div>
                                        <input type="number" step="0.01" id="appointmentTotal" name="price" class="form-control" placeholder="0.00">
                                    </div>
                                </div>
                                
                                <div class="form-actions text-right">
                                    <button type="button" class="btn btn-secondary" onclick="closeStartAppointmentModal()">
                                        <i class="fas fa-times"></i> Cancelar
                                    </button>
                                    <button type="button" class="btn btn-success" onclick="completeAppointment()">
                                        <i class="fas fa-check-circle"></i> Completar Cita
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="{{ asset('js/admin/appointments.js') }}"></script>
    <script src="{{ asset('js/admin/appointments-pagination.js') }}"></script>
@endsection
