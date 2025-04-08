<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clínica Miel - @yield('title')</title>
    <!-- Base styles -->
    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    
    <!-- Layout components -->
    <link rel="stylesheet" href="{{ asset('css/layout/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout/footer.css') }}">
    
    <!-- UI Components -->
    <link rel="stylesheet" href="{{ asset('css/components/hero.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/buttons.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/specialties.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/appointments.css') }}">
    
    <!-- Page-specific styles -->
    <link rel="stylesheet" href="{{ asset('css/pages/admin.css') }}">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @yield('extra-css')
</head>
<body>
    <header class="main-header">
        <div class="header-container">
            <div class="logo">
                <img src="{{ asset('assets/clinica_logo.png') }}" alt="Clínica Miel">
            </div>
            <nav class="main-nav">
                <a href="{{ url('/') }}" class="{{ request()->is('/') ? 'active' : '' }}">Inicio</a>
                <div class="dropdown-nav">
                    <a class="{{ request()->is('appointment*') || request()->is('appointment-clinic*') || request()->is('appointment-home*') ? 'active' : '' }}">Agendar Cita <i class="fas fa-chevron-down"></i></a>
                    <div class="dropdown-content">
                        <a href="{{ url('appointment-clinic') }}">En Clínica</a>
                        <a href="{{ url('appointment-home') }}">A Domicilio</a>
                    </div>
                </div>
                <a href="{{ url('history') }}" class="{{ request()->is('history*') ? 'active' : '' }}">Historial</a>
                <a href="{{ url('admin') }}" class="{{ request()->is('admin*') ? 'active' : '' }}">Admin</a>
                <a href="{{ url('seguimiento') }}" class="{{ request()->is('seguimiento*') ? 'active' : '' }}">Seguimiento</a>
                <a href="{{ url('doctor-history') }}" class="{{ request()->is('doctor-history*') ? 'active' : '' }}">Historial Pacientes</a>
            </nav>
            <div class="user-icon">
                <img src="{{ asset('assets/profile.png') }}" alt="Usuario">
            </div>
        </div>
    </header>
    <div class="container @yield('container-class')">

        @yield('content')

        <footer>
            <div class="contact-info">
                <i class="fas fa-phone"></i>
                <p>4521 346</p>
                <p>Clínica Miel | Dirección Física</p>
                <p>Horario: Lunes a Viernes 8:00 - 20:00 hrs.</p>
            </div>
        </footer>
    </div>

    <script src="{{ asset('js/dropdown.js') }}"></script>
    @yield('scripts')
</body>
</html>
