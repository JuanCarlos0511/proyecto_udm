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


    <div class="appointments-container">
        <div class="appointments-header">
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
                    <tr class="appointment-row" data-id="{{ $appointment->id }}" data-group="{{ $appointment->appointment_group_id }}">
                        <td>
                            <div class="date-cell">
                                <div class="date-primary">{{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y') }}</div>
                                <div class="date-secondary">{{ \Carbon\Carbon::parse($appointment->date)->format('H:i') }} hrs</div>
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
                                $doctorName = 'Sin asignar';
                                $doctorFound = false;
                                
                                // Buscar directamente usando el appointment_group_id para encontrar al doctor
                                if ($appointment->appointment_group_id) {
                                    // Buscar cualquier cita relacionada donde el usuario sea doctor o admin
                                    $doctorAppointment = \App\Models\Appointment::where('appointment_group_id', $appointment->appointment_group_id)
                                        ->where('id', '!=', $appointment->id)
                                        ->whereHas('user', function($query) {
                                            $query->whereIn('role', ['doctor', 'admin']);
                                        })
                                        ->with('user')
                                        ->first();
                                        
                                    if ($doctorAppointment && $doctorAppointment->user) {
                                        $doctorName = $doctorAppointment->user->name;
                                        $doctorFound = true;
                                    }
                                }
                                
                                // Si no encontramos con el método directo, intentar con getRelatedAppointment
                                if (!$doctorFound) {
                                    $relatedAppointment = $appointment->getRelatedAppointment();
                                    
                                    if ($relatedAppointment && $relatedAppointment->user && 
                                        ($relatedAppointment->user->role === 'doctor' || $relatedAppointment->user->role === 'admin')) {
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
                                    @php
                                        $canAccept = false;
                                        
                                        // Doctores y administradores pueden aceptar sus propias citas
                                        if (auth()->user()->role === 'doctor' || auth()->user()->role === 'admin') {
                                            // Verificar si es su cita
                                            if ($appointment->appointment_group_id) {
                                                // Verificar directamente si hay una cita relacionada para este doctor/admin
                                                $doctorAppointment = \App\Models\Appointment::where('appointment_group_id', $appointment->appointment_group_id)
                                                    ->where('user_id', auth()->id())
                                                    ->first();
                                                    
                                                if ($doctorAppointment) {
                                                    $canAccept = true;
                                                }
                                            }
                                            
                                            // Si es admin, también puede aceptar si está asignado como doctor
                                            if (auth()->user()->role === 'admin' && !$canAccept) {
                                                $assignedDoctorId = null;
                                                if ($appointment->appointment_group_id) {
                                                    $doctorAppt = \App\Models\Appointment::where('appointment_group_id', $appointment->appointment_group_id)
                                                        ->whereHas('user', function($query) {
                                                            $query->whereIn('role', ['doctor', 'admin']);
                                                        })
                                                        ->first();
                                                    if ($doctorAppt && $doctorAppt->user_id === auth()->id()) {
                                                        $canAccept = true;
                                                    }
                                                }
                                            }
                                        }
                                    @endphp
                                    
                                    @if($canAccept)
                                        <button type="button" class="btn btn-action btn-accept" 
                                            onclick="aceptarCitaDirecto('{{ $appointment->id }}')" title="Aceptar cita">
                                            <i class="fas fa-check"></i> Aceptar
                                        </button>
                                    @endif
                                    <button type="button" class="btn btn-action btn-edit" 
                                        onclick="toggleAppointmentDetails('{{ $appointment->id }}')" title="Editar cita">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                @elseif($appointment->status == 'Agendado')
                                    @php
                                        // Determinar si el admin puede iniciar esta cita
                                        $canStart = true;
                                        $doctorAssigned = false;
                                        $relatedDoctorAppointment = null;
                                        $assignedDoctorName = '';
                                        
                                        // Verificar si hay un doctor asignado a esta cita
                                        if ($appointment->appointment_group_id) {
                                            $relatedDoctorAppointment = \App\Models\Appointment::where('appointment_group_id', $appointment->appointment_group_id)
                                                ->whereHas('user', function($query) {
                                                    $query->whereIn('role', ['doctor', 'admin']);
                                                })
                                                ->first();
                                                
                                            // Verificar si el doctor asignado es distinto al usuario actual
                                            if ($relatedDoctorAppointment && $relatedDoctorAppointment->user_id !== auth()->id() && auth()->user()->role === 'admin') {
                                                $doctorAssigned = true;
                                                $canStart = false;
                                                $assignedDoctorName = $relatedDoctorAppointment->user ? $relatedDoctorAppointment->user->name : 'otro doctor';
                                            }
                                        }
                                    @endphp
                                    
                                    @if($canStart)
                                        <a href="{{ route('admin.citas.show', $appointment->id) }}" class="btn btn-action btn-start" title="Iniciar cita">
                                            <i class="fas fa-play-circle"></i> Iniciar
                                        </a>
                                    @else
                                        <button type="button" class="btn btn-action btn-start" disabled title="No puedes iniciar esta cita porque está asignada a {{ $assignedDoctorName }}">
                                            <i class="fas fa-play-circle"></i> Iniciar
                                        </button>
                                        <div class="text-danger small mt-1">
                                            <i class="fas fa-info-circle"></i> Asignada a {{ $assignedDoctorName }}
                                        </div>
                                    @endif
                                @elseif($appointment->status == 'Completado' || $appointment->status == 'Cancelado')
                                    <button type="button" class="btn btn-action btn-view" 
                                        onclick="viewAppointmentDetails('{{ $appointment->id }}')" title="Ver detalles"
                                        style="min-width: 120px !important; padding: 8px 16px !important; width: auto !important; display: inline-flex !important; justify-content: center !important;">
                                        <i class="fas fa-eye"></i> <span style="margin-left: 8px;">Detalles</span>
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
                                                            @php
                                                            // Buscar si esta cita tiene doctor asignado a través del grupo de citas
                                                            $assignedDoctorId = null;
                                                            if ($appointment->appointment_group_id) {
                                                                $doctorAppt = \App\Models\Appointment::where('appointment_group_id', $appointment->appointment_group_id)
                                                                    ->whereHas('user', function($query) {
                                                                        $query->whereIn('role', ['doctor', 'admin']);
                                                                    })
                                                                    ->first();
                                                                if ($doctorAppt) {
                                                                    $assignedDoctorId = $doctorAppt->user_id;
                                                                }
                                                            }
                                                            @endphp

                                                            @foreach($doctors as $doctor)
                                                                <option value="{{ $doctor->id }}" {{ $assignedDoctorId == $doctor->id ? 'selected' : '' }}>
                                                                    {{ $doctor->name }} {{ $doctor->role == 'admin' ? '(Admin)' : '' }}
                                                                </option>
                                                            @endforeach
                                                            @if(!$doctors->contains('id', auth()->id()) && auth()->user()->role == 'admin')
                                                                <option value="{{ auth()->id() }}" {{ $assignedDoctorId == auth()->id() ? 'selected' : '' }}>
                                                                    {{ auth()->user()->name }} (Admin)
                                                                </option>
                                                            @endif
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
                                            <button type="button" class="btn btn-primary" onclick="updateAppointmentInbox('{{ $appointment->id }}', document.getElementById('form-{{ $appointment->id }}'))">
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
    <!-- Solo cargar los scripts esenciales -->
    <script>
        // Función para aceptar cita directamente sin recargar la página
        function aceptarCitaDirecto(appointmentId) {
            // Obtener el botón que se presionó
            const btn = document.querySelector(`button.btn-accept[onclick*="'${appointmentId}'"`);
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Procesando...';
            }
            
            // Construir URL y datos
            const url = `/admin/citas/${appointmentId}/accept`;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            
            console.log('Aceptando cita:', appointmentId);
            console.log('URL:', url);
            console.log('Token CSRF:', csrfToken ? 'Obtenido' : 'No encontrado');
            
            // Hacer la petición
            fetch(url, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ appointment_id: appointmentId })
            })
            .then(response => {
                console.log('Respuesta HTTP:', response.status);
                if (!response.ok) {
                    throw new Error('Error en la respuesta: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                console.log('Datos recibidos:', data);
                
                // Actualizar la UI
                const row = document.querySelector(`tr[data-id="${appointmentId}"]`);
                if (row) {
                    const statusCell = row.querySelector('.appointment-status');
                    if (statusCell) {
                        statusCell.innerHTML = '<i class="status-icon fas fa-check-circle"></i> Agendado';
                        statusCell.className = 'appointment-status status-agendado';
                    }
                    
                    // Ocultar el botón
                    if (btn) btn.style.display = 'none';
                    
                    // Actualizar otras citas relacionadas
                    if (data.appointment && data.appointment.appointment_group_id) {
                        const groupId = data.appointment.appointment_group_id;
                        console.log('Actualizando citas del grupo:', groupId);
                        
                        document.querySelectorAll(`tr[data-group="${groupId}"] .appointment-status`).forEach(cell => {
                            cell.innerHTML = '<i class="status-icon fas fa-check-circle"></i> Agendado';
                            cell.className = 'appointment-status status-agendado';
                        });
                        
                        document.querySelectorAll(`tr[data-group="${groupId}"] .btn-accept`).forEach(button => {
                            button.style.display = 'none';
                        });
                    }
                    
                    // Mostrar mensaje
                    alert(data.message || 'Cita aceptada correctamente');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al aceptar la cita: ' + error.message);
                
                // Restaurar el botón
                if (btn) {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-check"></i> Aceptar';
                }
            });
            
            // Prevenir recarga de página
            return false;
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializar los botones de filtro
            const filterButtons = document.querySelectorAll('.btn-filter');
            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    filterButtons.forEach(btn => btn.classList.remove('active'));
                    this.classList.add('active');
                    const status = this.getAttribute('data-status');
                    filterByStatus(status);
                });
            });
            
            // Función para filtrar citas por estado
            function filterByStatus(status) {
                const rows = document.querySelectorAll('.appointment-row');
                rows.forEach(row => {
                    const statusCell = row.querySelector('.appointment-status');
                    if (!statusCell) return;
                    const rowStatus = statusCell.textContent.trim();
                    
                    if (status === 'all' || rowStatus.includes(status)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }

            // Manejar el toggle de detalles
            window.toggleAppointmentDetails = function(appointmentId) {
                const detailsRow = document.getElementById('details-' + appointmentId);
                if (detailsRow) {
                    detailsRow.style.display = detailsRow.style.display === 'none' ? '' : 'none';
                }
            };
        });
    </script>
@endsection
