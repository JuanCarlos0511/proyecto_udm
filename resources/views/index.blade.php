<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clínica Intel - Agenda tu Cita</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <header>
            <div class="logo">
                <img src="{{ asset('assets/logo.png') }}" alt="Clínica Intel">
            </div>
            <nav class="main-nav">
                <a href="{{ url('admin') }}" id="adminButton" class="nav-link">Admin</a>
            </nav>
            <div class="user-icon">
                <img src="{{ asset('assets/user-icon.png') }}" alt="Usuario">
            </div>
        </header>

        <main>
            <section class="hero">
                <h1>Agenda tu cita ahora</h1>
                <p>Agenda tu cita en línea y disfruta de la comodidad de ser atendido desde tu hogar. Nuestro equipo de profesionales está listo para ayudarte.</p>
                
                <div class="search-container">
                    <div class="dropdown">
                        <button id="scheduleAppointment" class="schedule-btn">Agendar cita</button>
                        <div id="appointmentOptions" class="dropdown-content">
                            <button id="scheduleInPerson" class="dropdown-btn">Agendar presencialmente</button>
                            <button id="scheduleAtHome" class="dropdown-btn">Agendar a domicilio</button>
                        </div>
                    </div>
                </div>
            </section>

            <section class="specialties">
                <h2>Especialidades Médicas de Vanguardia</h2>
                <p>Nuestro equipo de expertos usa atención médica integral y personalizada, con especialidades diseñadas para mejorar la calidad de vida.</p>
                
                <div class="services-grid">
                    <div class="service-card">
                        <img src="{{ asset('assets/electroterapia.jpg') }}" alt="Electroterapia">
                        <h3>Electroterapia: Alivio Inmediato</h3>
                        <p>Aprovechamos las propiedades terapéuticas de la electricidad para rehabilitar las lesiones y aliviar el dolor en diferentes áreas anatómicas.</p>
                    </div>
                    <!-- Add more service cards as needed -->
                </div>
            </section>
        </main>

        <footer>
            <p>© 2025 Clínica Intel. Todos los derechos reservados.</p>
        </footer>
    </div>

    <script src="{{ asset('js/main.js') }}"></script>
</body>
</html>
