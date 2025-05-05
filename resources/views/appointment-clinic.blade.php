@extends('layouts.app')

@section('title', 'Agendar Cita')

@section('extra-css')
    <link rel="stylesheet" href="{{ asset('css/components/modern-appointment.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('container-class', 'modern-appointment-page')

@section('content')
    <div class="navigation-buttons">
        <button id="backToHome" class="back-btn"><i class="fas fa-arrow-left"></i> Regresar</button>
    </div>

    <div class="modern-appointment-container">
        <!-- Columna 1: Formulario Principal -->
        <div class="form-column">
            <h2 class="modern-form-title">Agendar Cita en Clínica</h2>
            
            <form id="modernAppointmentForm">
                @csrf
                <input type="hidden" id="isAuthenticated" value="{{ Auth::check() }}">
                @if(Auth::check())
                    <input type="hidden" id="userData" value="{{ json_encode([
                        'name' => Auth::user()->name,
                        'age' => Auth::user()->age,
                        'email' => Auth::user()->email,
                        'phoneNumber' => Auth::user()->phoneNumber,
                        'emergency_contact_name' => Auth::user()->emergency_contact_name,
                        'emergency_contact_phone' => Auth::user()->emergency_contact_phone,
                        'emergency_contact_relationship' => Auth::user()->emergency_contact_relationship,
                    ]) }}">
                @endif

                <!-- Información Personal -->
                <div class="modern-form-group">
                    <label for="nombre" class="required-field">Nombre completo {{ Auth::check() ? '(Autenticado)' : '' }}</label>
                    <input type="text" id="nombre" name="nombre" required {{ Auth::check() ? 'readonly' : '' }}>
                    <span class="error-message">Este campo es obligatorio</span>
                </div>

                <div class="modern-form-row">
                    <div class="modern-form-group">
                        <label for="edad" class="required-field">Edad</label>
                        <input type="number" id="edad" name="edad" required {{ Auth::check() ? 'readonly' : '' }}>
                        <span class="error-message">Este campo es obligatorio</span>
                    </div>
                    <div class="modern-form-group">
                        <label for="telefono" class="required-field">Teléfono</label>
                        <input type="tel" id="telefono" name="telefono" required {{ Auth::check() ? 'readonly' : '' }}>
                        <span class="error-message">Este campo es obligatorio</span>
                    </div>
                </div>

                <div class="modern-form-group">
                    <label for="email" class="required-field">Correo electrónico</label>
                    <input type="email" id="email" name="email" required {{ Auth::check() ? 'readonly' : '' }}>
                    <span class="error-message">Introduzca un correo electrónico válido</span>
                </div>

                <!-- Contacto de emergencia - Campos en columnas separadas -->
                <div class="emergency-contact-section">
                    <h3 class="modern-form-subtitle">Contacto de Emergencia</h3>
                    
                    <div class="contact-row-container">
                        <div class="modern-form-group">
                            <label for="contact_name" class="required-field">Nombre de contacto de emergencia</label>
                            <input type="text" id="contact_name" name="contact_name" required>
                            <span class="error-message">Este campo es obligatorio</span>
                        </div>
                        <div class="modern-form-group">
                            <label for="contact_relationship" class="required-field">Parentesco</label>
                            <input type="text" id="contact_relationship" name="contact_relationship" required>
                            <span class="error-message">Este campo es obligatorio</span>
                        </div>
                    </div>

                    <div class="modern-form-group">
                        <label for="contact_phone" class="required-field">Teléfono de contacto de emergencia</label>
                        <input type="tel" id="contact_phone" name="contact_phone" required>
                        <span class="error-message">Este campo es obligatorio</span>
                    </div>
                </div>

                <!-- Detalles de la Cita -->
                <h3 class="modern-form-subtitle">Detalles de la Cita</h3>
                
                <div class="modern-form-group">
                    <label for="especialidad" class="required-field">Especialidad requerida</label>
                    <select id="especialidad" name="especialidad" required>
                        <option value="">Seleccione una especialidad</option>
                        <option value="electroterapia">Electroterapia</option>
                        <option value="hidroterapia">Hidroterapia</option>
                        <option value="mecanoterapia">Mecanoterapia</option>
                        <option value="atencion-integral">Atención Integral</option>
                    </select>
                    <span class="error-message">Seleccione una especialidad</span>
                </div>
                
                <div class="modern-form-group">
                    <label for="doctor" class="required-field">Seleccionar Doctor</label>
                    <select id="doctor" name="doctor" required>
                        <option value="">Seleccione un doctor</option>
                        <option value="3">Rosa Elba Martínez</option>
                        <option value="5">Isaac Solís Martínez</option>
                        <option value="6">Karla Lorena Martínez Ávila</option>
                    </select>
                    <span class="error-message">Seleccione un doctor</span>
                </div>
            </form>
        </div>
        
        <!-- Columna 2: Detalles adicionales y Selección de Fecha/Hora -->
        <div class="datetime-column">
            <h2 class="modern-form-title">Detalles y Programación</h2>
            
            <!-- Continuación del formulario -->
            <div class="modern-form-group">
                <label for="diagnosis">Diagnóstico inicial (si aplica)</label>
                <textarea id="diagnosis" name="diagnosis" rows="3"></textarea>
            </div>

            <div class="modern-form-group">
                <label>¿Cuenta con algún padecimiento o discapacidad?</label>
                <div class="modern-radio-group">
                    <div class="modern-radio-option">
                        <input type="radio" id="si" name="padecimiento" value="si">
                        <label for="si">Sí</label>
                    </div>
                    <div class="modern-radio-option">
                        <input type="radio" id="no" name="padecimiento" value="no" checked>
                        <label for="no">No</label>
                    </div>
                </div>
            </div>

            <div class="modern-form-group" id="padecimientoDetails" style="display: none;">
                <label for="detalles" class="required-field">Por favor, describa su padecimiento:</label>
                <textarea id="detalles" name="detalles" rows="4"></textarea>
                <span class="error-message">Describa su padecimiento</span>
            </div>

            <div class="modern-form-group">
                <label for="referred_by">Referido por (opcional)</label>
                <input type="text" id="referred_by" name="referred_by">
            </div>
            
            <!-- Selección de Fecha y Hora -->
            <h3 class="modern-form-subtitle">Selección de Fecha y Hora</h3>
            
            <div class="date-time-section">
                <div class="date-time-buttons">
                    <button id="dateSelectBtn" class="date-select-btn">
                        <i class="far fa-calendar-alt"></i> Seleccionar Fecha
                    </button>
                    <button id="timeSelectBtn" class="time-select-btn" disabled>
                        <i class="far fa-clock"></i> Seleccionar Hora
                    </button>
                </div>
                
                <div class="selected-date-time">
                    <div style="display: none;">
                        <p><span class="label">Fecha seleccionada:</span></p>
                        <p id="selectedDate"></p>
                    </div>
                    <div style="display: none;">
                        <p><span class="label">Hora seleccionada:</span></p>
                        <p id="selectedTime"></p>
                    </div>
                </div>
            </div>
            
            <!-- Calendario emergente -->
            <div id="calendarPopup" class="calendar-popup">
                <div class="calendar-header">
                    <button id="prevMonth" class="calendar-nav"><i class="fas fa-chevron-left"></i></button>
                    <h3 id="currentMonth">Abril de 2025</h3>
                    <button id="nextMonth" class="calendar-nav"><i class="fas fa-chevron-right"></i></button>
                </div>
                <div class="calendar-days">
                    <div>Dom</div><div>Lun</div><div>Mar</div><div>Mié</div>
                    <div>Jue</div><div>Vie</div><div>Sáb</div>
                </div>
                <div class="calendar-grid" id="calendarGrid">
                    <!-- Se llenará dinámicamente con JavaScript -->
                </div>
            </div>
            
            <!-- Selector de hora emergente -->
            <div id="timePopup" class="time-popup">
                <h3>Seleccione una hora disponible</h3>
                <div class="time-slots" id="timeSlots">
                    <!-- Se llenará dinámicamente con JavaScript -->
                </div>
            </div>
            
            <div class="modern-form-actions">
                <button type="button" id="cancelBtn" class="cancel-btn">Cancelar</button>
                <button type="button" id="scheduleBtn" class="schedule-btn">Agendar Cita</button>
            </div>
        </div>
    </div>

    <!-- Modal de confirmación -->
    <div id="confirmationModal" class="modern-modal">
        <div class="modern-modal-content">
            <div class="modern-modal-header">
                <i class="fas fa-check-circle"></i>
                <h3>Cita Agendada</h3>
            </div>
            <p>Tu solicitud de cita en clínica ha sido enviada y se ha agregado a tu historial</p>
            <div class="modern-modal-buttons">
                <button id="goToHome" class="home-btn">Ir a inicio</button>
                <button id="goToHistory" class="history-btn">Ver historial</button>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/modernAppointmentController.js') }}"></script>
@endsection
