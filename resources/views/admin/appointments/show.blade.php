@extends('layouts.admin')

@section('title')
    Detalles de la Cita
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/appointments.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/dropdown-fix.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .appointment-details-card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.08);
            padding: 24px;
            margin-bottom: 30px;
        }
        
        .appointment-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e9ecef;
        }
        
        .appointment-status-badge {
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 500;
        }
        
        .patient-info {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 8px;
        }
        
        .patient-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        .info-section {
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e9ecef;
        }
        
        .info-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 10px;
            color: #495057;
        }
        
        .info-content {
            color: #212529;
        }
        
        .actions-bar {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
        
        .status-Solicitado {
            background-color: #fff8e1;
            color: #f57c00;
        }
        
        .status-Agendado {
            background-color: #e3f2fd;
            color: #1976d2;
        }
        
        .status-Completado {
            background-color: #e8f5e9;
            color: #2e7d32;
        }
        
        .status-Cancelado {
            background-color: #ffebee;
            color: #c62828;
        }
    </style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('admin.appointments.index') }}" class="btn btn-sm btn-outline-secondary mr-3">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
                <h2 class="mb-0">Detalles de la Cita #{{ $appointment->id }}</h2>
            </div>
            
            <div class="appointment-details-card">
                <div class="appointment-header">
                    <h4>{{ $appointment->subject }}</h4>
                    <span class="appointment-status-badge status-{{ $appointment->status }}">
                        @if($appointment->status == 'Solicitado')
                            <i class="fas fa-clock"></i>
                        @elseif($appointment->status == 'Agendado')
                            <i class="fas fa-calendar-check"></i>
                        @elseif($appointment->status == 'Completado')
                            <i class="fas fa-check-circle"></i>
                        @elseif($appointment->status == 'Cancelado')
                            <i class="fas fa-times-circle"></i>
                        @endif
                        {{ $appointment->status }}
                    </span>
                </div>
                
                <div class="patient-info">
                    @if($appointment->user->photo_path)
                        <img src="{{ asset($appointment->user->photo_path) }}" alt="{{ $appointment->user->name }}" class="patient-avatar">
                    @else
                        <div class="patient-avatar d-flex align-items-center justify-content-center bg-secondary text-white">
                            <i class="fas fa-user"></i>
                        </div>
                    @endif
                    <div>
                        <h5 class="mb-1">{{ $appointment->user->name }}</h5>
                        <p class="mb-0 text-muted small">
                            <i class="fas fa-envelope"></i> {{ $appointment->user->email }}
                            @if($appointment->user->phoneNumber)
                                &nbsp;|&nbsp; <i class="fas fa-phone"></i> {{ $appointment->user->phoneNumber }}
                            @endif
                        </p>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-section">
                            <div class="info-title">Fecha y Hora</div>
                            <div class="info-content">
                                <i class="far fa-calendar-alt"></i> {{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y') }}
                                &nbsp;|&nbsp;
                                <i class="far fa-clock"></i> {{ \Carbon\Carbon::parse($appointment->date)->format('H:i') }}
                            </div>
                        </div>
                        
                        <div class="info-section">
                            <div class="info-title">Modalidad</div>
                            <div class="info-content">
                                @if($appointment->modality == 'Consultorio')
                                    <i class="fas fa-hospital"></i>
                                @else
                                    <i class="fas fa-house-user"></i>
                                @endif
                                {{ $appointment->modality }}
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="info-section">
                            <div class="info-title">Precio</div>
                            <div class="info-content">
                                <i class="fas fa-dollar-sign"></i> {{ number_format($appointment->price, 2) }}
                            </div>
                        </div>
                        
                        <div class="info-section">
                            <div class="info-title">Fecha de Creación</div>
                            <div class="info-content">
                                <i class="fas fa-calendar-plus"></i> {{ $appointment->created_at->format('d/m/Y H:i') }}
                            </div>
                        </div>
                    </div>
                </div>
                
                @if($appointment->notes)
                <div class="info-section">
                    <div class="info-title">Notas</div>
                    <div class="info-content">
                        {{ $appointment->notes }}
                    </div>
                </div>
                @endif
                
                <div class="actions-bar">
                    @if($appointment->status == 'Solicitado')
                        <button type="button" class="btn btn-accept" onclick="acceptAppointment('{{ $appointment->id }}')">
                            <i class="fas fa-check"></i> Aceptar Cita
                        </button>
                    @elseif($appointment->status == 'Agendado')
                        <button type="button" class="btn btn-start" onclick="showStartAppointmentModal('{{ $appointment->id }}')">
                            <i class="fas fa-play-circle"></i> Iniciar Cita
                        </button>
                    @endif
                    
                    @if($appointment->status != 'Completado' && $appointment->status != 'Cancelado')
                        <button type="button" class="btn btn-danger" onclick="cancelAppointment('{{ $appointment->id }}')">
                            <i class="fas fa-times-circle"></i> Cancelar Cita
                        </button>
                    @endif
                </div>
            </div>
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
                    <!-- Información del paciente -->
                    <div class="col-md-4">
                        <div class="patient-details">
                            <h5>Datos del Paciente</h5>
                            <div class="text-center mb-3">
                                <img id="patientAvatar" src="{{ asset('images/default-user.png') }}" alt="Avatar del paciente" class="patient-avatar">
                            </div>
                            <div class="patient-info-item">
                                <strong>Nombre:</strong>
                                <span id="patientName">{{ $appointment->user->name }}</span>
                            </div>
                            <div class="patient-info-item">
                                <strong>Email:</strong>
                                <span id="patientEmail">{{ $appointment->user->email }}</span>
                            </div>
                            <div class="patient-info-item">
                                <strong>Teléfono:</strong>
                                <span id="patientPhone">{{ $appointment->user->phoneNumber }}</span>
                            </div>
                            <div class="patient-info-item">
                                <strong>Edad:</strong>
                                <span id="patientAge">{{ $appointment->user->age }} años</span>
                            </div>
                            <div class="patient-info-item">
                                <strong>Dirección:</strong>
                                <span id="patientAddress">{{ $appointment->user->adress ?? 'No disponible' }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Formulario para iniciar cita -->
                    <div class="col-md-8">
                        <form id="completeAppointmentForm">
                            <input type="hidden" id="appointmentId" value="{{ $appointment->id }}">
                            
                            <div class="form-group">
                                <label for="appointmentNotes">Notas de la consulta:</label>
                                <textarea id="appointmentNotes" class="form-control" rows="6"></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label for="appointmentTotal">Precio total:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">$</span>
                                    </div>
                                    <input type="number" id="appointmentTotal" class="form-control" value="{{ $appointment->price }}" min="0" step="0.01">
                                </div>
                            </div>
                            
                            <div class="text-right">
                                <button type="button" class="btn btn-secondary" onclick="closeStartAppointmentModal()">
                                    Cancelar
                                </button>
                                <button type="button" class="btn btn-primary" onclick="completeAppointment()">
                                    Completar Cita
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/admin/appointments.js') }}"></script>
@endsection
