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
                        <li class="sidebar-menu-item {{ request()->is('admin/dashboard*') ? 'active' : '' }}">
                            <a href="{{ url('admin/dashboard') }}">
                                <i class="fas fa-tachometer-alt"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item {{ request()->is('admin/profile*') ? 'active' : '' }}">
                            <a href="{{ url('admin/profile') }}">
                                <i class="fas fa-user"></i>
                                <span>Perfil</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item {{ request()->is('admin/contacts*') ? 'active' : '' }}">
                            <a href="{{ url('admin/contacts') }}">
                                <i class="fas fa-address-book"></i>
                                <span>Contactos</span>
                            </a>
                        </li>
                    </ul>
                </div>
                
                <div class="sidebar-section">
                    <h3 class="section-title">Reportes</h3>
                    <ul class="sidebar-menu">
                        <li class="sidebar-menu-item {{ request()->is('admin/reports/daily*') ? 'active' : '' }}">
                            <a href="{{ url('admin/reports/daily') }}">
                                <i class="fas fa-calendar-day"></i>
                                <span>Diarios</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item {{ request()->is('admin/reports/monthly*') ? 'active' : '' }}">
                            <a href="{{ url('admin/reports/monthly') }}">
                                <i class="fas fa-calendar-alt"></i>
                                <span>Mensuales</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item {{ request()->is('admin/reports/services*') ? 'active' : '' }}">
                            <a href="{{ url('admin/reports/services') }}">
                                <i class="fas fa-concierge-bell"></i>
                                <span>Servicios</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item {{ request()->is('admin/reports/inventory*') ? 'active' : '' }}">
                            <a href="{{ url('admin/reports/inventory') }}">
                                <i class="fas fa-boxes"></i>
                                <span>Inventario</span>
                            </a>
                        </li>
                    </ul>
                </div>
                
                <div class="sidebar-section">
                    <h3 class="section-title">Facturaciones</h3>
                    <ul class="sidebar-menu">
                        <li class="sidebar-menu-item {{ request()->is('admin/invoices/history*') ? 'active' : '' }}">
                            <a href="{{ url('admin/invoices/history') }}">
                                <i class="fas fa-history"></i>
                                <span>Historial</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item {{ request()->is('admin/invoices/generate*') ? 'active' : '' }}">
                            <a href="{{ url('admin/invoices/generate') }}">
                                <i class="fas fa-file-invoice"></i>
                                <span>Generar Factura</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item {{ request()->is('admin/templates*') ? 'active' : '' }}">
                            <a href="{{ url('admin/templates') }}">
                                <i class="fas fa-file-alt"></i>
                                <span>Plantillas</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="sidebar-footer">
                <a href="{{ url('admin/settings') }}" class="sidebar-menu-item {{ request()->is('admin/settings*') ? 'active' : '' }}">
                    <i class="fas fa-cog"></i>
                    <span>Configuración</span>
                </a>
            </div>
        </aside>
        
        <!-- Main Content -->
        <main class="admin-main">
            <!-- Top Bar -->
            <header class="admin-header">
                <div class="breadcrumb">
                    <a href="{{ url('admin/dashboard') }}">
                        <i class="fas fa-home"></i>
                    </a>
                    @yield('breadcrumb')
                </div>
                
                <div class="header-actions">
                    <div class="search-container">
                        <input type="text" placeholder="Buscar..." class="search-input">
                        <button class="search-button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                    
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
    @yield('scripts')
</body>
</html>
