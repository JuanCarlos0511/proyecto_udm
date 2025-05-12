@extends('layouts.admin')

@section('title', 'Perfil')

@section('page-title', 'Perfil')

@section('breadcrumb')
    <span class="breadcrumb-separator">/</span>
    <span>Perfil</span>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/profile.css') }}">
@endsection

@section('content')
<div class="profile-container">
    
    <div class="profile-content">
        <div class="profile-layout">
            <div class="profile-left-column">
                <div class="profile-info">
                    <div class="profile-avatar">
                        @if(auth()->user()->google_id)
                            <img src="{{ auth()->user()->avatar ?? asset('assets/profile.png') }}" alt="Foto de perfil de {{ auth()->user()->name }}">
                        @else
                            <img src="{{ asset('assets/profile.png') }}" alt="Foto de perfil">
                        @endif
                    </div>
                    <div class="profile-details">
                        <h3 class="profile-name">{{ auth()->user()->name }}</h3>
                        <div class="profile-role">{{ ucfirst(auth()->user()->role) }}</div>
                    </div>
                </div>
                
                <div class="profile-card">
                    <div class="profile-card-header">
                        <h3 class="profile-card-title">About</h3>
                    </div>
                    <div class="profile-card-content">
                        <div class="profile-field">
                            <div class="profile-field-label">Teléfono</div>
                            <div class="profile-field-value">{{ auth()->user()->phoneNumber == '0000000000' ? 'No especificado' : auth()->user()->phoneNumber }}</div>
                        </div>
                        <div class="profile-field">
                            <div class="profile-field-label">Email</div>
                            <div class="profile-field-value">{{ auth()->user()->email }}</div>
                        </div>
                    </div>
                </div>
                
                <div class="profile-card">
                    <div class="profile-card-header">
                        <h3 class="profile-card-title">Dirección</h3>
                    </div>
                    <div class="profile-card-content">
                        <div class="profile-field">
                            <div class="profile-field-label">Dirección</div>
                            <div class="profile-field-value">{{ auth()->user()->adress ? auth()->user()->adress : 'No especificada' }}</div>
                        </div>
                    </div>
                </div>
                
                <!-- Sección de Detalles del empleado eliminada -->
            </div>
            
            <div class="profile-right-column">

                <!-- Sección de Información laboral eliminada -->
                
                <div class="profile-card">
                    <div class="profile-card-header">
                        <h3 class="profile-card-title">Actividad reciente</h3>
                        <a href="{{ url('admin/perfil/actividad-toda') }}" class="edit-button">Ver todo</a>
                    </div>
                    <div class="profile-card-content">
                        <div class="activity-list">
                            <div class="activity-item">
                                <div class="activity-avatar">
                                    <img src="{{ asset('assets/profile.png') }}" alt="Foto de perfil">
                                </div>
                                <div class="activity-content">
                                    <div>
                                        <span class="activity-user">Nicholas Swatz</span>
                                        <span class="activity-action">actualizó la información del paciente</span>
                                    </div>
                                    <div class="activity-date">Hace 2 horas</div>
                                    <div class="activity-details">
                                        Actualizó la información de contacto del paciente María González.
                                    </div>
                                </div>
                            </div>
                            
                            <div class="activity-item">
                                <div class="activity-avatar">
                                    <img src="{{ asset('assets/profile.png') }}" alt="Foto de perfil">
                                </div>
                                <div class="activity-content">
                                    <div>
                                        <span class="activity-user">Nicholas Swatz</span>
                                        <span class="activity-action">creó una nueva cita</span>
                                    </div>
                                    <div class="activity-date">Ayer a las 15:30</div>
                                    <div class="activity-details">
                                        Programó una cita para Juan Pérez con el Dr. Rodríguez para el 15 de mayo.
                                    </div>
                                </div>
                            </div>
                            
                            <div class="activity-item">
                                <div class="activity-avatar">
                                    <img src="{{ asset('assets/profile.png') }}" alt="Foto de perfil">
                                </div>
                                <div class="activity-content">
                                    <div>
                                        <span class="activity-user">Nicholas Swatz</span>
                                        <span class="activity-action">generó un reporte</span>
                                    </div>
                                    <div class="activity-date">Hace 2 días</div>
                                    <div class="activity-details">
                                        Generó un reporte de ingresos mensuales para abril 2025.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="profile-card">
                    <div class="profile-card-header">
                        <h3 class="profile-card-title">Compensaciones</h3>
                        <a href="{{ url('admin/perfil/compensaciones-todas') }}" class="edit-button">Ver todo</a>
                    </div>
                    <div class="profile-card-content">
                        <div class="profile-field">
                            <div class="profile-field-label">SALARIO BASE</div>
                            <div class="profile-field-value">$85,000.00 / año</div>
                        </div>
                        <div class="profile-field">
                            <div class="profile-field-label">BONOS</div>
                            <div class="profile-field-value">$5,000.00 / año</div>
                        </div>
                        <div class="profile-field">
                            <div class="profile-field-label">TOTAL</div>
                            <div class="profile-field-value">$90,000.00 / año</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
