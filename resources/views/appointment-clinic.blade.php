@extends('layouts.app')

@section('title', 'Cita en Clínica')

@section('container-class', 'appointment-form')

@section('content')
    <div class="navigation-buttons">
        <button id="backToHome" class="back-btn"><i class="fas fa-arrow-left"></i> Regresar</button>
    </div>

    <h1>AGENDAR CITA EN CLÍNICA</h1>
    
    <form id="appointmentClinicForm">
        <div class="form-row">
            <div class="form-group">
                <label for="nombre">Nombre: (Autocompleta si está registrado)</label>
                <input type="text" id="nombre" name="nombre" required>
            </div>
            <div class="form-group">
                <label for="telefono">Teléfono:</label>
                <input type="tel" id="telefono" name="telefono" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="edad">Edad:</label>
                <input type="number" id="edad" name="edad" required>
            </div>
            <div class="form-group">
                <label for="email">Correo electrónico:</label>
                <input type="email" id="email" name="email" required>
            </div>
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
                    <!-- Calendar will be dynamically populated by JavaScript -->
                    <div class="calendar-header">
                        <button id="prevMonth" class="calendar-nav"><i class="fas fa-chevron-left"></i></button>
                        <h3 id="currentMonth">Abril de 2025</h3>
                        <button id="nextMonth" class="calendar-nav"><i class="fas fa-chevron-right"></i></button>
                    </div>
                    <!-- Sample calendar layout -->
                    <div class="calendar-days">
                        <div>Dom</div><div>Lun</div><div>Mar</div><div>Mié</div>
                        <div>Jue</div><div>Vie</div><div>Sáb</div>
                    </div>
                    <div class="calendar-grid" id="calendarGrid">
                        <!-- Calendar grid will be populated by JS -->
                    </div>
                </div>
            </div>
            
            <!-- Time selection container -->
            <div id="timeSelectionContainer" style="display: none;">
                <label>Seleccione una hora disponible:</label>
                <div class="time-slots-container" id="timeSlots">
                    <!-- Time slots will be populated by JS -->
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

    <!-- Confirmation Modal -->
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
