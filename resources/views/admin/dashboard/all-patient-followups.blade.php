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
                        @if(Auth::user()->role === 'doctor')
                            <!-- Si es un doctor, el campo se bloquea con su propia información -->
                            <input type="text" class="form-control" value="{{ Auth::user()->name }}" readonly>
                            <input type="hidden" id="doctor" name="doctor_id" value="{{ Auth::user()->id }}">
                        @else
                            <!-- Si es un administrador, puede seleccionar cualquier doctor -->
                            <select id="doctor" name="doctor_id" class="form-select" required>
                                <option value="">Seleccionar doctor</option>
                                @foreach(\App\Models\User::where('role', 'doctor')->get() as $doctor)
                                    <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                                @endforeach
                            </select>
                        @endif
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
                        <div class="appointment-datetime-container">
                            <input type="date" id="nextAppointment" name="next_appointment" class="form-control" required>
                            <input type="time" id="appointmentTime" name="appointment_time" class="form-control" value="09:00" required>
                        </div>
                    </div>
                    <style>
                        .appointment-datetime-container {
                            display: flex;
                            gap: 10px;
                        }
                        .appointment-datetime-container input {
                            flex: 1;
                        }
                    </style>
                    
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
                        // Variable para almacenar la próxima cita si existe
                        $nextAppointment = null;
                    @endphp
                    <tr>
                        <td>
                            <div class="doctor-info">
                                    @php
                                        // Obtener al doctor del grupo de seguimiento
                                        $doctorFollowUp = $followUp->user->role === 'doctor' ? $followUp : 
                                            $followUp->followUpGroupMembers()->byUserRole('doctor')->first();
                                        $doctor = $doctorFollowUp ? $doctorFollowUp->user : null;
                                    @endphp
                                    <span>{{ $doctor ? $doctor->name : 'Sin asignar' }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="patient-info">
                                    @php
                                        // Obtener al paciente del grupo de seguimiento
                                        $patientFollowUp = $followUp->user->role === 'paciente' ? $followUp : 
                                            $followUp->followUpGroupMembers()->byUserRole('paciente')->first();
                                        $patient = $patientFollowUp ? $patientFollowUp->user : null;
                                    @endphp
                                <span>{{ $patient ? $patient->name : 'Sin asignar' }}</span>
                            </div>
                        </td>
                        <td class="treatment-cell">{{ $followUp->notes }}</td>
                        <td>
                            @php
                                // Identificar el paciente asociado al seguimiento
                                $patientUser = null;
                                
                                // Si este followUp pertenece a un paciente directamente
                                if ($followUp->user->role === 'paciente') {
                                    $patientUser = $followUp->user;
                                } else {
                                    // Si este followUp pertenece a un doctor, buscar el registro del paciente
                                    $patientFollowUp = \App\Models\FollowUp::where('follow_up_group_id', $followUp->follow_up_group_id)
                                        ->whereHas('user', function($q) {
                                            $q->where('role', 'paciente');
                                        })
                                        ->first();
                                    
                                    if ($patientFollowUp) {
                                        $patientUser = $patientFollowUp->user;
                                    }
                                }
                                
                                // Obtener la cita para este paciente específico
                                $appointmentRow = null;
                                
                                if ($patientUser) {
                                    $appointmentRow = \Illuminate\Support\Facades\DB::table('appointments')
                                        ->where('user_id', $patientUser->id)
                                        ->where('subject', 'like', "%Seguimiento: {$followUp->notes}%")
                                        ->where('status', '!=', 'Cancelado')
                                        ->orderBy('date', 'asc')
                                        ->first();
                                }
                                    
                                $dateFormatted = null;
                                $timeFormatted = null;
                                
                                if ($appointmentRow && !empty($appointmentRow->date)) {
                                    // Separar la fecha y hora manualmente para evitar problemas de formato
                                    $dateParts = explode(' ', $appointmentRow->date); // Separar fecha y hora
                                    if (count($dateParts) >= 2) {
                                        $datePart = $dateParts[0]; // YYYY-MM-DD
                                        $timePart = $dateParts[1]; // HH:MM:SS
                                        
                                        // Convertir formato de fecha
                                        $dateElements = explode('-', $datePart);
                                        if (count($dateElements) === 3) {
                                            $dateFormatted = $dateElements[2] . '/' . $dateElements[1] . '/' . $dateElements[0];
                                        }
                                        
                                        // Convertir formato de hora (quitar segundos)
                                        $timeElements = explode(':', $timePart);
                                        if (count($timeElements) >= 2) {
                                            $timeFormatted = $timeElements[0] . ':' . $timeElements[1];
                                        }
                                    }
                                }
                            @endphp
                            
                            @if($dateFormatted && $timeFormatted)
                                <span class="next-appointment">
                                    {{ $dateFormatted }} a las {{ $timeFormatted }}
                                </span>
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
                                @php
                                    // Usamos el follow_up_group_id como identificador para las rutas
                                    $followUpGroupId = $followUp->follow_up_group_id;
                                @endphp
                                <button class="action-btn edit-btn" title="Editar" onclick="toggleFollowUpDetails('{{ $followUpGroupId }}')">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <a href="{{ route('follow-ups.show', ['seguimiento' => $followUpGroupId]) }}" class="action-btn" title="Ver detalles">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <button class="action-btn delete-btn" title="Eliminar" onclick="deleteFollowUp('{{ $followUpGroupId }}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        <!-- Filas expandibles para edición (inicialmente ocultas) -->
        @foreach($followUps as $followUp)
        <div class="followup-details" id="details-{{ $followUp->follow_up_group_id }}" style="display: none;">
            <div class="edit-container">
                <form class="followup-edit-form" id="form-{{ $followUp->follow_up_group_id }}" method="POST" action="{{ route('follow-ups.update', ['seguimiento' => $followUp->follow_up_group_id]) }}">
                    @csrf
                    @method('PUT')
                    <div class="form-header">
                        <h3>Editar Seguimiento</h3>
                        <button type="button" class="close-btn" onclick="toggleFollowUpDetails('{{ $followUp->follow_up_group_id }}')">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    
                    <div class="edit-body">
                        <div class="form-section">
                            <h4 class="section-title">Información Principal</h4>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="status-{{ $followUp->follow_up_group_id }}">Estado</label>
                                    <div class="select-wrapper">
                                        <select class="form-control" id="status-{{ $followUp->follow_up_group_id }}" name="status">
                                            <option value="active" {{ $followUp->status === 'active' ? 'selected' : '' }}>Activo</option>
                                            <option value="inactive" {{ $followUp->status === 'inactive' ? 'selected' : '' }}>Inactivo</option>
                                            <option value="completed" {{ $followUp->status === 'completed' ? 'selected' : '' }}>Completado</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="notes-{{ $followUp->follow_up_group_id }}">Tratamiento</label>
                                    <div class="select-wrapper">
                                        <select class="form-control" id="notes-{{ $followUp->follow_up_group_id }}" name="notes">
                                            <option value="" disabled {{ $followUp->notes === null ? 'selected' : '' }}>Seleccione un tratamiento</option>
                                            <option value="Electroterapia" {{ $followUp->notes === 'Electroterapia' ? 'selected' : '' }}>Electroterapia</option>
                                            <option value="Hidroterapia" {{ $followUp->notes === 'Hidroterapia' ? 'selected' : '' }}>Hidroterapia</option>
                                            <option value="Mecanoterapia" {{ $followUp->notes === 'Mecanoterapia' ? 'selected' : '' }}>Mecanoterapia</option>
                                            <option value="Atención Integral" {{ $followUp->notes === 'Atención Integral' ? 'selected' : '' }}>Atención Integral</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="start_date-{{ $followUp->follow_up_group_id }}">Fecha de inicio</label>
                                    <input type="date" class="form-control" id="start_date-{{ $followUp->follow_up_group_id }}" name="start_date" value="{{ \Carbon\Carbon::parse($followUp->start_date)->format('Y-m-d') }}">
                                </div>
                                <div class="form-group">
                                    <label for="end_date-{{ $followUp->follow_up_group_id }}">Fecha final</label>
                                    <input type="date" class="form-control" id="end_date-{{ $followUp->follow_up_group_id }}" name="end_date" value="{{ $followUp->end_date ? \Carbon\Carbon::parse($followUp->end_date)->format('Y-m-d') : '' }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <div class="primary-actions">
                            <button type="button" class="btn btn-secondary" onclick="toggleFollowUpDetails('{{ $followUp->follow_up_group_id }}')">
                                <i class="fas fa-times"></i> Cancelar
                            </button>
                            <button type="button" class="btn btn-primary" onclick="updateFollowUp('{{ $followUp->follow_up_group_id }}')">
                                <i class="fas fa-save"></i> Guardar cambios
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @endforeach
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
<script src="{{ asset('js/admin/followups.js') }}"></script>
@endsection
