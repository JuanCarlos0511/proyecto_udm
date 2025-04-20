@extends('layouts.app')

@section('title', 'Cita en Clínica')

@section('container-class', 'appointment-form')

@section('content')
    <div class="navigation-buttons">
        <button id="backToHome" class="back-btn"><i class="fas fa-arrow-left"></i> Regresar</button>
    </div>

    <h1>AGENDAR CITA EN CLÍNICA</h1>
    
    <form id="appointmentClinicForm">
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

        <div class="form-row">
            <div class="form-group">
                <label for="nombre">Nombre: {{ Auth::check() ? '(Autenticado)' : '(Autocompleta si está registrado)' }}</label>
                <input type="text" id="nombre" name="nombre" required {{ Auth::check() ? 'readonly' : '' }}>
            </div>
            <div class="form-group">
                <label for="telefono">Teléfono:</label>
                <input type="tel" id="telefono" name="telefono" required {{ Auth::check() ? 'readonly' : '' }}>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="edad">Edad:</label>
                <input type="number" id="edad" name="edad" required {{ Auth::check() ? 'readonly' : '' }}>
            </div>
            <div class="form-group">
                <label for="email">Correo electrónico:</label>
                <input type="email" id="email" name="email" required {{ Auth::check() ? 'readonly' : '' }}>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="contact_name">Nombre de contacto de emergencia:</label>
                <input type="text" id="contact_name" name="contact_name" required>
            </div>
            <div class="form-group">
                <label for="contact_relationship">Parentesco:</label>
                <input type="text" id="contact_relationship" name="contact_relationship" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="contact_phone">Teléfono de contacto de emergencia:</label>
                <input type="tel" id="contact_phone" name="contact_phone" required>
            </div>
            <div class="form-group">
                <label for="referred_by">Referido por (opcional):</label>
                <input type="text" id="referred_by" name="referred_by">
            </div>
        </div>

        <div class="form-group">
            <label for="diagnosis">Diagnóstico inicial (si aplica):</label>
            <textarea id="diagnosis" name="diagnosis" rows="3"></textarea>
        </div>

        <div class="form-group">
            <label for="especialidad">Especialidad requerida:</label>
            <select id="especialidad" name="especialidad" required>
                <option value="">Seleccione una especialidad</option>
                <option value="electroterapia">Electroterapia</option>
                <option value="hidroterapia">Hidroterapia</option>
                <option value="mecanoterapia">Mecanoterapia</option>
                <option value="atencion-integral">Atención Integral</option>
            </select>
        </div>

        <div class="form-group">
            <label>Seleccione una fecha y hora:</label>
            <div class="calendar-container">
                <div class="calendar" id="calendar">
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
                    </div>
                </div>
            </div>
            
            <div id="timeSelectionContainer" style="display: none;">
                <label>Seleccione una hora disponible:</label>
                <div class="time-slots-container" id="timeSlots">
                </div>
            </div>
        </div>

        <div class="form-group">
            <label>¿Cuenta con algún padecimiento o discapacidad?</label>
            <div class="radio-group">
                <input type="radio" id="si" name="padecimiento" value="si">
                <label for="si">Sí</label>
                <input type="radio" id="no" name="padecimiento" value="no" checked>
                <label for="no">No</label>
            </div>
        </div>

        <div class="form-group" id="padecimientoDetails" style="display: none;">
            <label for="detalles">Por favor, describa su padecimiento:</label>
            <textarea id="detalles" name="detalles" rows="4"></textarea>
        </div>

        <div class="form-actions">
            <button type="button" id="cancelar" class="cancel-btn">Cancelar</button>
            <button type="button" id="solicitar" class="solicitar-btn">Solicitar Cita</button>
        </div>
    </form>

    <div id="confirmationModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-check-circle"></i> Cita Agendada</h3>
            </div>
            <p>Tu solicitud de cita en clínica ha sido enviada y se ha agregado a tu historial</p>
            <div class="modal-buttons">
                <button id="goToHome" class="home-btn">Ir a inicio</button>
                <button id="goToHistory" class="history-btn">Ver historial</button>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/appointmentClinicController.js') }}"></script>
@endsection
