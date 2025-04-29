<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clínica Miel - Panel de Administración - @yield('title')</title>
    <!-- Base styles -->
    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    
    <!-- Layout components -->
    <link rel="stylesheet" href="{{ asset('css/layout/admin-layout.css') }}">
    
    <!-- UI Components -->
    <link rel="stylesheet" href="{{ asset('css/components/buttons.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/profile-indicator.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/role-simulator.css') }}">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @yield('styles')
</head>
<body class="admin-page">
    <div class="admin-container">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="sidebar-header">
                <div class="logo">
                    <div class="logo-icon">
                        <i class="fas fa-hospital"></i>
                    </div>
                    <span>Clínica Miel</span>
                </div>
            </div>
            
            <div class="sidebar-sections">
                <div class="sidebar-section">
                    <h3 class="section-title">Personal</h3>
                    <ul class="sidebar-menu">
                        <li class="sidebar-menu-item {{ (request()->is('admin/tablero') || request()->is('admin/tablero/actualizar')) ? 'active' : '' }}">
                            <a href="{{ url('admin/tablero') }}">
                                <i class="fas fa-tachometer-alt"></i>
                                <span>Tablero</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item {{ request()->is('admin/perfil*') ? 'active' : '' }}">
                            <a href="{{ url('admin/perfil') }}">
                                <i class="fas fa-user"></i>
                                <span>Perfil</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item {{ request()->is('admin/doctores*') ? 'active' : '' }}">
                            <a href="{{ url('admin/doctores') }}">
                                <i class="fas fa-user-md"></i>
                                <span>Doctores</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="sidebar-section">
                    <h3 class="section-title">Gestión Clínica</h3>
                    <ul class="sidebar-menu">
                        <li class="sidebar-menu-item {{ request()->is('admin/tablero/citas-todas') ? 'active' : '' }}">
                            <a href="{{ url('admin/tablero/citas-todas') }}">
                                <i class="fas fa-calendar-check"></i>
                                <span>Citas</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item {{ request()->is('admin/tablero/seguimiento-todos') ? 'active' : '' }}">
                            <a href="{{ url('admin/tablero/seguimiento-todos') }}">
                                <i class="fas fa-user-injured"></i>
                                <span>Pacientes en Seguimiento</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="sidebar-section">
                    <h3 class="section-title">Reportes de Cita</h3>
                    <ul class="sidebar-menu">
                    <li class="sidebar-menu-item {{ request()->is('admin/historial-citas*') ? 'active' : '' }}">
                            <a href="{{ url('admin/historial-citas') }}">
                                <i class="fas fa-calendar-day"></i>
                                <span>Historial de Citas</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item {{ request()->is('admin/reportes/generar*') ? 'active' : '' }}">
                            <a href="{{ url('admin/reportes/generar') }}">
                                <i class="fas fa-file-download"></i>
                                <span>Generar Reporte</span>
                            </a>
                        </li>
                        
                    </ul>
                </div>
                
                <div class="sidebar-section">
                    <h3 class="section-title">Facturaciones</h3>
                    <ul class="sidebar-menu">
                        <li class="sidebar-menu-item {{ request()->is('admin/historial-facturas*') ? 'active' : '' }}">
                            <a href="{{ url('admin/historial-facturas') }}">
                                <i class="fas fa-history"></i>
                                <span>Historial de Facturas</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item {{ request()->is('admin/generar-facturas*') ? 'active' : '' }}">
                            <a href="{{ url('admin/generar-facturas') }}">
                                <i class="fas fa-file-invoice-dollar"></i>
                                <span>Generar Factura</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </aside>
        
        <!-- Main Content -->
        <main class="admin-main">
            <!-- Top Bar -->
            <header class="admin-header">
                <div class="breadcrumb">
                    <a href="{{ url('/') }}">
                        <i class="fas fa-home"></i>
                        Dashboard
                    </a>
                    @yield('breadcrumb')
                </div>
                
                <div class="header-actions">
                    <div class="header-notifications">
                        <a href="#" class="notification-icon">
                            <i class="fas fa-bell"></i>
                            <span class="notification-badge">3</span>
                        </a>
                    </div>
                    
                    <div class="user-profile dropdown">
                        <div class="user-info">
                            <img src="{{ asset('assets/profile.png') }}" alt="Usuario" class="user-avatar">
                            <span class="user-name">Dr. García</span>
                        </div>
                        <div class="dropdown-menu">
                            <a href="{{ route('profile') }}" class="dropdown-item profile-link">
                                <i class="fas fa-user"></i> Mi Perfil
                                @auth
                                    @if(!isset($isProfileFullyComplete) || !$isProfileFullyComplete)
                                    <span class="profile-incomplete" title="Completa tu información de perfil para mejorar tu experiencia.">
                                        <i class="fas fa-exclamation-triangle"></i>
                                    </span>
                                    @endif
                                @endauth
                            </a>
                            <a href="{{ url('ajustes') }}" class="dropdown-item">
                                <i class="fas fa-cog"></i> Configuración
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="{{ url('logout') }}" class="dropdown-item">
                                <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                            </a>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Page Content -->
            <div class="admin-content">
                <div class="content-header">
                    <h1 class="content-title">@yield('page-title')</h1>
                    <div class="content-actions">
                        @yield('page-actions')
                    </div>
                </div>
                
                <div class="content-body">
                    @yield('content')
                </div>
            </div>
        </main>
    </div>

    <script src="{{ asset('js/admin.js') }}"></script>
    <script src="{{ asset('js/role-simulator.js') }}"></script>
    @yield('scripts')
</body>
</html>
