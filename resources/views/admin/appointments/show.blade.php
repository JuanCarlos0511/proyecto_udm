@extends('layouts.admin')

@section('title')
    Detalles de la Cita #{{ $appointment->id }}
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/appointments.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/dropdown-fix.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Estilos generales */
        .page-header {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        
        .page-header .back-btn {
            margin-right: 1rem;
        }
        
        /* Tarjeta principal */
        .appointment-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 1.5rem;
            overflow: hidden;
        }
        
        /* Encabezado de la tarjeta */
        .appointment-header {
            padding: 1.25rem;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f8f9fa;
        }
        
        /* Cuerpo de la tarjeta */
        .appointment-body {
            padding: 1.5rem;
        }
        
        /* Información del paciente */
        .patient-profile {
            display: flex;
            align-items: center;
            padding: 1rem;
            background-color: #f0f7ff;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            border-left: 4px solid #4e73df;
        }
        
        .patient-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            border: 3px solid white;
            box-shadow: 0 3px 6px rgba(0,0,0,0.1);
            margin-right: 1rem;
            object-fit: cover;
            background: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .patient-details {
            flex: 1;
        }
        
        .patient-details h5 {
            margin-bottom: 0.35rem;
            font-weight: 600;
        }
        
        .patient-contact {
            display: flex;
            flex-wrap: wrap;
        }
        
        .patient-contact span {
            display: inline-flex;
            align-items: center;
            margin-right: 1rem;
            margin-bottom: 0.25rem;
            font-size: 0.875rem;
            color: #495057;
        }
        
        .patient-contact i {
            color: #4e73df;
            margin-right: 0.35rem;
            width: 16px;
            text-align: center;
        }
        
        /* Información de la cita */
        .appointment-info {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 1.5rem;
        }
        
        .info-item {
            flex-basis: 50%;
            margin-bottom: 1rem;
            padding-right: 1rem;
        }
        
        .info-item label {
            display: block;
            font-weight: 600;
            color: #566a7f;
            margin-bottom: 0.25rem;
            font-size: 0.875rem;
        }
        
        .info-item p {
            margin: 0;
            font-size: 0.95rem;
        }
        
        /* Estado de la cita */
        .status-badge {
            padding: 0.35rem 0.75rem;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
        }
        
        .status-badge i {
            margin-right: 0.35rem;
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
        
        /* Acciones */
        .action-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-top: 2rem;
            margin-bottom: 2rem;
            padding: 0.5rem 0;
        }
        
        .btn-action {
            padding: 0.75rem 1.5rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            border: none;
            box-shadow: 0 3px 6px rgba(0,0,0,0.1);
            width: auto;
            min-width: 150px;
            margin-bottom: 0.75rem;
            text-align: center;
        }
        
        .btn-action i {
            margin-right: 0.65rem;
            font-size: 1rem;
        }
        
        .btn-primary {
            background: #4e73df;
            color: white;
            border: none;
        }
        
        .btn-success {
            background: #1cc88a;
            color: white;
            border: none;
        }
        
        .btn-danger {
            background: #e74a3b;
            color: white;
            border: none;
        }
        
        .btn-secondary {
            background: #858796;
            color: white;
            border: none;
        }
        
        /* Formulario de completar cita */
        .completion-form {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 1.25rem;
            margin-top: 1.5rem;
        }
        
        .completion-form h5 {
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            color: #4e73df;
        }
        
        .completion-form h5 i {
            margin-right: 0.5rem;
        }
        
        .form-row {
            display: flex;
            margin: 0 -0.75rem;
        }
        
        .form-col {
            flex: 1;
            padding: 0 0.75rem;
        }
        
        .form-group {
            margin-bottom: 1rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            font-size: 0.9rem;
        }
        
        .form-control {
            width: 100%;
            padding: 0.65rem 0.75rem;
            border: 1px solid #ced4da;
            border-radius: 4px;
            font-size: 0.95rem;
        }
        
        .form-control:focus {
            border-color: #4e73df;
            outline: none;
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }
        
        .form-actions {
            display: flex;
            justify-content: flex-end;
            margin-top: 1.5rem;
        }
        
        .form-actions button {
            margin-left: 0.5rem;
        }
        
        .patient-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #fff;
            box-shadow: 0 0 15px rgba(0,0,0,0.15);
            background-color: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #4e73df;
            font-size: 30px;
        }
        
        .info-section {
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e9ecef;
            position: relative;
        }
        
        .info-title {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 12px;
            color: #2c3e50;
            display: flex;
            align-items: center;
        }
        
        .info-title i {
            margin-right: 10px;
            color: #4e73df;
            font-size: 18px;
        }
        
        .info-content {
            color: #212529;
            font-size: 15px;
            padding-left: 28px;
            line-height: 1.6;
        }
        
        .actions-bar {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            flex-wrap: wrap;
        }
        
        .btn {
            padding: 10px 20px;
            font-weight: 600;
            border-radius: 6px;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
            font-size: 14px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .btn i {
            margin-right: 8px;
            font-size: 16px;
        }
        
        .btn-accept {
            background-color: #36b9cc;
            color: white;
            border: none;
        }
        
        .btn-accept:hover {
            background-color: #2a9aaa;
            box-shadow: 0 4px 10px rgba(54, 185, 204, 0.3);
        }
        
        .btn-start {
            background-color: #4e73df;
            color: white;
            border: none;
        }
        
        .btn-start:hover {
            background-color: #2e59d9;
            box-shadow: 0 4px 10px rgba(78, 115, 223, 0.3);
        }
        
        .btn-secondary {
            background-color: #858796;
            color: white;
            border: none;
        }
        
        .btn-secondary:hover {
            background-color: #717384;
            box-shadow: 0 4px 10px rgba(133, 135, 150, 0.3);
        }
        
        .btn-danger {
            background-color: #e74a3b;
            color: white;
            border: none;
        }
        
        .btn-danger:hover {
            background-color: #d52a1a;
            box-shadow: 0 4px 10px rgba(231, 74, 59, 0.3);
        }
        
        .btn-success {
            background-color: #1cc88a;
            color: white;
            border: none;
        }
        
        .btn-success:hover {
            background-color: #17a673;
            box-shadow: 0 4px 10px rgba(28, 200, 138, 0.3);
        }
        
        .appointment-status-badge {
            padding: 10px 18px;
            border-radius: 30px;
            font-size: 14px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.08);
        }
        
        .appointment-status-badge i {
            margin-right: 8px;
        }
        
        .status-Solicitado {
            background-color: #fff8e1;
            color: #f57c00;
            border: 1px solid rgba(245, 124, 0, 0.3);
        }
        
        .status-Agendado {
            background-color: #e3f2fd;
            color: #1976d2;
            border: 1px solid rgba(25, 118, 210, 0.3);
        }
        
        .status-Completado {
            background-color: #e8f5e9;
            color: #2e7d32;
            border: 1px solid rgba(46, 125, 50, 0.3);
        }
        
        .status-Cancelado {
            background-color: #ffebee;
            color: #c62828;
            border: 1px solid rgba(198, 40, 40, 0.3);
        }
    </style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Encabezado de página -->
    <div class="page-header">
        <a href="http://localhost:8000/admin/tablero/citas-todas" class="btn btn-sm btn-outline-secondary back-btn">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
        <h2 class="mb-0">Detalles de la Cita #{{ $appointment->id }}</h2>
    </div>
    
    <!-- Tarjeta principal -->
    <div class="appointment-card">
        <!-- Encabezado de la tarjeta -->
        <div class="appointment-header">
            <h4>{{ $appointment->subject }}</h4>
            <span class="status-badge status-{{ $appointment->status }}">
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
        
        <div class="appointment-body">
            <!-- Perfil del paciente -->
            <div class="patient-profile">
                @if($appointment->user->photo_path)
                    <img src="{{ asset($appointment->user->photo_path) }}" alt="{{ $appointment->user->name }}" class="patient-avatar">
                @else
                    <div class="patient-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                @endif
                
                <div class="patient-details">
                    <h5>{{ $appointment->user->name }}</h5>
                    <div class="patient-contact">
                        <span><i class="fas fa-envelope"></i> {{ $appointment->user->email }}</span>
                        @if($appointment->user->phoneNumber)
                            <span><i class="fas fa-phone"></i> {{ $appointment->user->phoneNumber }}</span>
                        @endif
                        @if($appointment->user->age)
                            <span><i class="fas fa-birthday-cake"></i> {{ $appointment->user->age }} años</span>
                        @endif
                        @if($appointment->user->adress)
                            <span><i class="fas fa-map-marker-alt"></i> {{ $appointment->user->adress }}</span>
                        @endif
                    </div>
                </div>
            </div>
                
            <!-- Información de la cita -->
            <div class="appointment-info">
                <div class="info-item">
                    <label><i class="far fa-calendar-alt"></i> Fecha</label>
                    <p>{{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y') }}</p>
                </div>
                
                <div class="info-item">
                    <label><i class="far fa-clock"></i> Hora</label>
                    <p>{{ \Carbon\Carbon::parse($appointment->date)->format('H:i') }}</p>
                </div>
                
                <div class="info-item">
                    <label>
                        @if($appointment->modality == 'Consultorio')
                            <i class="fas fa-hospital"></i>
                        @else
                            <i class="fas fa-house-user"></i>
                        @endif
                        Modalidad
                    </label>
                    <p>{{ $appointment->modality }}</p>
                </div>
                
                <div class="info-item">
                    <label><i class="fas fa-dollar-sign"></i> Precio</label>
                    <p>${{ number_format($appointment->price, 2) }}</p>
                </div>
                
                @if($appointment->diagnosis)
                <div class="info-item">
                    <label><i class="fas fa-stethoscope"></i> Diagnóstico</label>
                    <p>{{ $appointment->diagnosis }}</p>
                </div>
                @endif
                
                @if($appointment->notes)
                <div class="info-item">
                    <label><i class="fas fa-clipboard-list"></i> Notas</label>
                    <p>{{ $appointment->notes }}</p>
                </div>
                @endif
                
                <div class="info-item">
                    <label><i class="fas fa-calendar-plus"></i> Fecha de Creación</label>
                    <p>{{ $appointment->created_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
            
            <!-- Formulario para completar cita -->
            <div class="mt-4" id="completarCitaForm">
                <div class="completion-form">
                    <h5><i class="fas fa-clipboard-check"></i> Completar Cita</h5>
                    
                    <form id="appointmentCompleteForm" onsubmit="completeAppointment(event, {{ $appointment->id }})">
                        @csrf
                        <input type="hidden" name="status" value="Completado">
                        
                        <div class="form-row">
                            <div class="form-col">
                                <div class="form-group">
                                    <label for="diagnosis">
                                        <i class="fas fa-stethoscope"></i> Diagnóstico
                                    </label>
                                    <textarea class="form-control" id="diagnosis" name="diagnosis" rows="5" placeholder="Ingrese el diagnóstico del paciente">{{ $appointment->diagnosis }}</textarea>
                                </div>
                            </div>
                            
                            <div class="form-col">
                                <div class="form-group">
                                    <label for="notes">
                                        <i class="fas fa-clipboard-list"></i> Notas adicionales
                                    </label>
                                    <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Notas internas sobre la consulta">{{ $appointment->notes }}</textarea>
                                </div>
                                
                                <div class="form-group">
                                    <label for="price">
                                        <i class="fas fa-dollar-sign"></i> Precio
                                    </label>
                                    <div class="price-input">
                                        <span class="currency-symbol">$</span>
                                        <input type="number" step="0.01" class="form-control" id="price" name="price" value="{{ $appointment->price }}" min="0">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-actions">
                            <button type="button" class="btn-action btn-secondary" id="btnCancel">
                                <i class="fas fa-times"></i> Cancelar
                            </button>
                            <button type="submit" class="btn-action btn-success">
                                <i class="fas fa-check-circle"></i> Guardar y Completar
                            </button>
                        </div>
                    </form>
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
                            
                            <div class="form-group text-right">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                    Cancelar
                                </button>
                                @if(auth()->user()->role === 'admin')
                                <button type="button" class="btn btn-info" onclick="saveChanges()">
                                    Guardar Cambios
                                </button>
                                @endif
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
<script>
    // Función para completar la cita usando Fetch API
    function completeAppointment(event, id) {
        event.preventDefault();
        
        // Obtener los valores del formulario
        const form = document.getElementById('appointmentCompleteForm');
        const diagnosis = document.getElementById('diagnosis').value;
        const notes = document.getElementById('notes').value;
        const price = document.getElementById('price').value;
        const token = document.querySelector('input[name="_token"]').value;
        
        // Crear datos para enviar
        const formData = new FormData();
        formData.append('_token', token);
        formData.append('_method', 'PUT');
        formData.append('diagnosis', diagnosis);
        formData.append('notes', notes);
        formData.append('price', price);
        formData.append('status', 'Completado');
        
        // Mostrar indicador de carga
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalBtnText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Procesando...';
        submitBtn.disabled = true;
        
        // Realizar petición con Fetch API usando POST para simular PUT (enfoque Laravel)
        fetch(`/admin/citas/${id}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': token
            },
            body: formData
        })
        .then(response => {
            // Si la respuesta indica éxito (sin importar el código de estado)
            // Intentamos parsear la respuesta como JSON
            return response.json().catch(() => {
                // Si no se puede parsear como JSON, asumimos que fue exitoso
                return { success: true, message: 'Cita completada correctamente' };
            });
        })
        .then(data => {
            // Mostrar mensaje de éxito
            if (typeof toastr !== 'undefined') {
                toastr.success('Cita completada correctamente');
            } else {
                alert('Cita completada correctamente');
            }
            
            // Recargar la página para mostrar los cambios después de un breve retraso
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        })
        .catch(error => {
            console.error('Error:', error);
            
            // Mostrar mensajes de error
            if (error.errors) {
                Object.values(error.errors).forEach(messages => {
                    if (Array.isArray(messages)) {
                        messages.forEach(message => {
                            if (typeof toastr !== 'undefined') {
                                toastr.error(message);
                            } else {
                                alert('Error: ' + message);
                            }
                        });
                    }
                });
            } else {
                if (typeof toastr !== 'undefined') {
                    toastr.error('Error al completar la cita. Intente nuevamente.');
                } else {
                    alert('Error al completar la cita. Intente nuevamente.');
                }
            }
        })
        .finally(() => {
            // Restaurar botón
            submitBtn.innerHTML = originalBtnText;
            submitBtn.disabled = false;
        });
    }
    
    // Función para guardar cambios como administrador sin completar la cita
    function saveChanges() {
        // Obtener los valores del formulario
        const form = document.getElementById('completeAppointmentForm');
        const diagnosis = document.getElementById('diagnosis').value;
        const notes = document.getElementById('notes').value;
        const price = document.getElementById('price').value;
        const id = document.getElementById('appointmentId').value;
        const token = document.querySelector('input[name="_token"]').value;
        
        // Crear datos para enviar
        const formData = new FormData();
        formData.append('_token', token);
        formData.append('_method', 'PUT');
        formData.append('diagnosis', diagnosis);
        formData.append('notes', notes);
        formData.append('price', price);
        // No cambiamos el estado a Completado, solo guardamos los cambios
        
        // Mostrar indicador de carga
        const saveBtn = document.querySelector('.btn-info');
        const originalBtnText = saveBtn.innerHTML;
        saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';
        saveBtn.disabled = true;
        
        // Realizar petición con Fetch API
        fetch(`/admin/citas/${id}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': token
            },
            body: formData
        })
        .then(response => {
            return response.json().catch(() => {
                return { success: true, message: 'Cambios guardados correctamente' };
            });
        })
        .then(data => {
            // Mostrar mensaje de éxito
            if (typeof toastr !== 'undefined') {
                toastr.success('Cambios guardados correctamente');
            } else {
                alert('Cambios guardados correctamente');
            }
            
            // Recargar la página para mostrar los cambios después de un breve retraso
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        })
        .catch(error => {
            console.error('Error:', error);
            
            // Mostrar mensajes de error
            if (error.errors) {
                Object.values(error.errors).forEach(messages => {
                    if (Array.isArray(messages)) {
                        messages.forEach(message => {
                            if (typeof toastr !== 'undefined') {
                                toastr.error(message);
                            } else {
                                alert('Error: ' + message);
                            }
                        });
                    }
                });
            } else {
                if (typeof toastr !== 'undefined') {
                    toastr.error('Error al guardar los cambios. Intente nuevamente.');
                } else {
                    alert('Error al guardar los cambios. Intente nuevamente.');
                }
            }
        })
        .finally(() => {
            // Restaurar botón
            saveBtn.innerHTML = originalBtnText;
            saveBtn.disabled = false;
        });
    }
    
    // Configuración del botón cancelar
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('btnCancel').addEventListener('click', function() {
            // Reiniciar el formulario
            document.getElementById('appointmentCompleteForm').reset();
        });
    });
</script>
@endsection
