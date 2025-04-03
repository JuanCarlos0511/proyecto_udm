<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clínica Intel - @yield('title')</title>
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @yield('extra-css')
</head>
<body>
    <div class="container @yield('container-class')">
        <header>
            <div class="logo">
                <img src="{{ asset('assets/logo.png') }}" alt="Clínica Intel">
            </div>
            <nav class="main-nav">
                <a href="{{ url('/') }}" class="{{ request()->is('/') ? 'active' : '' }}">Inicio</a>
                <a href="{{ url('appointment') }}" class="{{ request()->is('appointment*') ? 'active' : '' }}">Agendar Cita</a>
                <a href="{{ url('history') }}" class="{{ request()->is('history*') ? 'active' : '' }}">Historial</a>
                <a href="{{ url('admin') }}" class="{{ request()->is('admin*') ? 'active' : '' }}">Admin</a>
                <a href="{{ url('seguimiento') }}" class="{{ request()->is('seguimiento*') ? 'active' : '' }}">Seguimiento</a>
                <a href="{{ url('doctor-history') }}" class="{{ request()->is('doctor-history*') ? 'active' : '' }}">Historial Pacientes</a>
            </nav>
            <div class="user-icon">
                <img src="{{ asset('assets/user-icon.png') }}" alt="Usuario">
            </div>
        </header>

        @yield('content')

        <footer>
            <div class="contact-info">
                <i class="fas fa-phone"></i>
                <p>4521 346</p>
                <p>Clínica Intel | Dirección Física</p>
                <p>Horario: Lunes a Viernes 8:00 - 20:00 hrs.</p>
            </div>
        </footer>
    </div>

    @yield('scripts')
</body>
</html>
