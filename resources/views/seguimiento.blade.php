@extends('layouts.app')

@section('title', 'Plan de Seguimiento')

@section('extra-css')
    <link rel="stylesheet" href="{{ asset('css/seguimiento.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endsection

@section('container-class', 'seguimiento-panel')

@section('content')
    <div class="seguimiento-container">
        <!-- Profile Section -->
        <div class="profile-section">
            <div class="profile-image">
                <img src="{{ Auth::user()->photo_path ? asset(Auth::user()->photo_path) : asset('assets/profile.png') }}" alt="{{ Auth::user()->name }}" class="profile-picture">
            </div>
            <div class="profile-info">
                <h1>{{ Auth::user() ? Auth::user()->name : 'Paciente' }}</h1>
                <div class="profile-details">
                    <div class="detail-item">
                        <span class="detail-label">Edad:</span>
                        <span class="detail-value">{{ Auth::user() ? Auth::user()->age : '30' }} años</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Email:</span>
                        <span class="detail-value">{{ Auth::user() ? Auth::user()->email : 'paciente@ejemplo.com' }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Teléfono:</span>
                        <span class="detail-value">{{ Auth::user() ? Auth::user()->phoneNumber : '(123) 456-7890' }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Contacto de emergencia:</span>
                        <span class="detail-value">{{ Auth::user() && Auth::user()->emergency_contact_name ? Auth::user()->emergency_contact_name : 'No especificado' }}</span>
                    </div>
                </div>
                <div class="profile-actions">
                </div>
            </div>
        </div>
        
        <!-- Tabs Section -->
        <div class="tabs-section">
            <div class="tabs-header">
                <button class="tab-btn active" data-tab="appointments">Historial de citas</button>
                <button class="tab-btn" data-tab="upcoming">Próximas citas</button>
                <button class="tab-btn" data-tab="doctors">Mis doctores</button>
            </div>
            
            <div class="tabs-content">
                <!-- Appointments History Tab -->
                <div class="tab-pane active" id="appointments">
                    <div class="appointments-list">
                        <h3>Historial de citas</h3>
                        <table class="appointments-table">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Doctor</th>
                                    <th>Especialidad</th>
                                    <th>Modalidad</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="appointmentsHistoryList">
                                <!-- Will be populated by JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Upcoming Appointments Tab -->
                <div class="tab-pane" id="upcoming">
                    <div class="upcoming-appointments">
                        <div class="calendar-container">
                            <h3>Calendario de citas</h3>
                            <div id="appointmentsCalendar"></div>
                        </div>
                        <div class="upcoming-list">
                            <h3>Próximas citas</h3>
                            <div id="upcomingAppointmentsList">
                                <!-- Will be populated by JavaScript -->
                            </div>
                            <div class="schedule-button-container">
                                <a href="{{ url('appointment-clinic') }}" class="schedule-btn">Agendar nueva cita</a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Doctors Tab -->
                <div class="tab-pane" id="doctors">
                    <h3>Mis doctores</h3>
                    <div class="doctors-grid" id="doctorsList">
                        <!-- Will be populated by JavaScript -->
                    </div>
                </div>
                

            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <!-- Calendar library -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
    
    <!-- Chart library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Custom controller -->
    <script src="{{ asset('js/seguimientoController.js') }}"></script>
@endsection
