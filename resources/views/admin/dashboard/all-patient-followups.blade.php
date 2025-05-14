@extends('layouts.admin')

@section('title', 'Pacientes en Seguimiento')

@section('page-title', 'Pacientes en Seguimiento')

@section('breadcrumb')
    <span class="breadcrumb-separator">/</span>
    <span><a href="{{ url('admin/tablero') }}">Tablero</a></span>
    <span class="breadcrumb-separator">/</span>
    <span>Pacientes en Seguimiento</span>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/patient-followup.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/modal.css') }}">
    <!-- Meta tag para CSRF token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .add-patient-btn {
            background-color: #6366f1;
            color: white;
            border: none;
            border-radius: 6px;
            padding: 10px 16px;
            font-size: 14px;
            font-weight: 500;
            display: flex;
            align-items: center;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .add-patient-btn:hover {
            background-color: #4f46e5;
        }
        
        .add-patient-btn i {
            margin-right: 8px;
        }
        
        .status-active {
            background-color: #10b981;
        }
        
        .status-inactive {
            background-color: #9ca3af;
        }
        
        .status-completed {
            background-color: #3b82f6;
        }
        
        .action-buttons {
            display: flex;
            gap: 8px;
        }
        
        .action-btn {
            background: none;
            border: none;
            color: #6366f1;
            cursor: pointer;
            font-size: 16px;
            transition: color 0.2s;
        }
        
        .action-btn:hover {
            color: #4f46e5;
        }
        
        .treatment-cell {
            max-width: 200px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            font-weight: 500;
            color: #4f46e5;
        }
        
        .doctor-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .doctor-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            overflow: hidden;
            background-color: #e5e7eb;
        }
        
        .doctor-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .next-appointment {
            display: inline-block;
            padding: 4px 8px;
            background-color: #10b981;
            color: white;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .no-appointment {
            display: inline-block;
            padding: 4px 8px;
            background-color: #9ca3af;
            color: white;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .empty-state {
            text-align: center;
            padding: 40px 0;
            color: #6b7280;
        }
        
        .empty-state i {
            font-size: 48px;
            margin-bottom: 16px;
            color: #d1d5db;
        }
        
        .empty-state h3 {
            font-size: 18px;
            margin-bottom: 8px;
        }
        
        .empty-state p {
            font-size: 14px;
            margin-bottom: 24px;
        }
    </style>
@endsection

@section('content')
<div class="patients-container">
    <div class="patients-header">
        <h2 class="patients-title">Pacientes en Seguimiento</h2>
        <div class="patients-actions">
            <button id="openModalBtn" class="add-patient-btn">
                <i class="fas fa-plus"></i> Agregar Paciente
            </button>
        </div>
    </div>
    
    <!-- Modal para agregar seguimiento -->
    <div id="followUpModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Agregar Paciente en Seguimiento</h3>
                <span class="close">&times;</span>
            </div>
            <div class="modal-body">
                <form id="followUpForm" action="{{ route('follow-ups.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="patientSearch" class="form-label required-field">Nombre o Correo del Paciente</label>
                        <div class="patient-search-container">
                            <input type="text" id="patientSearch" class="form-control" placeholder="Buscar por nombre o correo...">
                            <div class="patient-search-results" id="patientSearchResults"></div>
                            <input type="hidden" id="patientId" name="patient_id">
                        </div>
                    </div>
                    
                    <!-- Tarjeta de información del paciente seleccionado -->
                    <div id="selectedPatientCard" class="selected-patient-card" style="display: none;">
                        <div class="patient-card-content">
                            <div class="patient-avatar">
                                <img id="patientAvatar" src="" alt="Foto de perfil">
                            </div>
                            <div class="patient-info">
                                <div class="patient-name" id="patientName"></div>
                                <div class="patient-email" id="patientEmail"></div>
                            </div>
                        </div>
                        <button type="button" id="changePatientBtn" class="change-patient-btn">
                            <i class="fas fa-exchange-alt"></i>
                        </button>
                    </div>
                    
                    <div class="form-group">
                        <label for="doctor" class="form-label required-field">Doctor</label>
                        <select id="doctor" name="doctor_id" class="form-select" required>
                            <option value="">Seleccionar doctor</option>
                            @foreach(\App\Models\User::where('role', 'doctor')->get() as $doctor)
                                <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="treatment" class="form-label required-field">Tratamiento</label>
                        <select id="treatment" name="notes" class="form-select" required>
                            <option value="">Seleccione un tratamiento</option>
                            <option value="Electroterapia">Electroterapia</option>
                            <option value="Hidroterapia">Hidroterapia</option>
                            <option value="Mecanoterapia">Mecanoterapia</option>
                            <option value="Atención Integral">Atención Integral</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="nextAppointment" class="form-label required-field">Fecha de Próxima Cita</label>
                        <input type="date" id="nextAppointment" name="next_appointment" class="form-control" required>
                    </div>
                    
                    <input type="hidden" name="start_date" id="startDate" value="{{ date('Y-m-d') }}">
                    <input type="hidden" name="status" value="active">
                    
                    <div class="form-actions">
                        <button type="button" id="cancelForm" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    @if(isset($followUps) && $followUps->count() > 0)
        <table class="patients-table">
            <thead>
                <tr>
                    <th>Doctor</th>
                    <th>Paciente</th>
                    <th>Tratamiento</th>
                    <th>Siguiente Cita</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($followUps as $followUp)
                    @php
                        // Buscar la próxima cita para este paciente y doctor
                        $key = $followUp->patient_id . '-' . $followUp->doctor_id;
                        $nextAppointment = isset($appointments[$key]) && count($appointments[$key]) > 0 ? $appointments[$key]->first() : null;
                    @endphp
                    <tr>
                        <td>
                            <div class="doctor-info">
                                <div class="doctor-avatar">
                                    @if($followUp->doctor->photo_path)
                                        <img src="{{ asset($followUp->doctor->photo_path) }}" alt="{{ $followUp->doctor->name }}">
                                    @else
                                        <img src="{{ asset('assets/default-avatar.png') }}" alt="{{ $followUp->doctor->name }}">
                                    @endif
                                </div>
                                <span>{{ $followUp->doctor->name }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="patient-info">
                                <div class="patient-avatar">
                                    @if($followUp->patient->photo_path)
                                        <img src="{{ asset($followUp->patient->photo_path) }}" alt="{{ $followUp->patient->name }}">
                                    @else
                                        <img src="{{ asset('assets/default-avatar.png') }}" alt="{{ $followUp->patient->name }}">
                                    @endif
                                </div>
                                <span>{{ $followUp->patient->name }}</span>
                            </div>
                        </td>
                        <td class="treatment-cell">{{ $followUp->notes }}</td>
                        <td>
                            @if($followUp->end_date)
                                <span class="next-appointment">{{ $followUp->end_date->format('d/m/Y') }}</span>
                            @else
                                <span class="no-appointment">Sin fecha definida</span>
                            @endif
                        </td>
                        <td>
                            @if($followUp->status == 'active')
                                <span class="patient-status status-active">Activo</span>
                            @elseif($followUp->status == 'inactive')
                                <span class="patient-status status-inactive">Inactivo</span>
                            @else
                                <span class="patient-status status-completed">Completado</span>
                            @endif
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('follow-ups.edit', $followUp->id) }}" class="action-btn" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('follow-ups.show', $followUp->id) }}" class="action-btn" title="Ver detalles">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <form action="{{ route('follow-ups.destroy', $followUp->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn" title="Eliminar" onclick="return confirm('¿Estás seguro de eliminar este seguimiento?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="empty-state">
            <i class="fas fa-user-md"></i>
            <h3>No hay pacientes en seguimiento</h3>
            <p>Comienza a dar seguimiento a tus pacientes para verlos aquí.</p>
        </div>
    @endif
</div>

@endsection

@section('scripts')
<script src="{{ asset('js/admin/patient-followup.js') }}"></script>
@endsection
