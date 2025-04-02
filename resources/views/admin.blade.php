<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clínica Intel - Panel de Administración</title>
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container admin-panel">
        <header>
            <div class="logo">
                <img src="{{ asset('assets/logo.png') }}" alt="Clínica Intel">
            </div>
            <nav class="main-nav">
                <a href="#" class="active">Inicio</a>
                <a href="{{ url('seguimiento') }}">Seguimiento</a>
                <a href="{{ url('doctor-history') }}">Historial Pacientes</a>
            </nav>
            <div class="user-icon">
                <img src="{{ asset('assets/user-icon.png') }}" alt="Usuario">
            </div>
        </header>

        <div class="admin-layout">
            <!-- Left Column - Active Appointments -->
            <div class="admin-column">
                <div class="active-appointments">
                    <h3>Citas Activas</h3>
                    <div class="appointment-items" id="activeAppointments">
                        <!-- Will be populated by JavaScript -->
                    </div>
                </div>
            </div>

            <!-- Right Column - Pending Appointments -->
            <div class="admin-column">
                <div class="pending-appointments">
                    <h3>Citas pendientes de confirmar:</h3>
                    <div class="pending-items" id="pendingAppointments">
                        <!-- Will be populated by JavaScript -->
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Schedule Button at Bottom -->
        <div class="schedule-button-container">
            <button id="scheduleNewAppointment" class="schedule-btn-large">AGENDAR CITA</button>
        </div>
    </div>

    <script src="{{ asset('js/admin.js') }}"></script>
</body>
</html>
