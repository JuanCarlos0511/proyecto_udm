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
                        <img src="{{ asset('assets/profile.png') }}" alt="Foto de perfil">
                    </div>
                    <div class="profile-details">
                        <h3 class="profile-name">Nicholas Swatz</h3>
                        <div class="profile-role">@Employee</div>
                    </div>
                </div>
                
                <div class="profile-card">
                    <div class="profile-card-header">
                        <h3 class="profile-card-title">About</h3>
                    </div>
                    <div class="profile-card-content">
                        <div class="profile-field">
                            <div class="profile-field-label">Phone</div>
                            <div class="profile-field-value">(629) 555-0123</div>
                        </div>
                        <div class="profile-field">
                            <div class="profile-field-label">Email</div>
                            <div class="profile-field-value">nicholasswatz@gmail.com</div>
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
                            <div class="profile-field-value">390 Market Street, Suite 200</div>
                        </div>
                        <div class="profile-field">
                            <div class="profile-field-label">Ciudad/estado</div>
                            <div class="profile-field-value">San Francisco CA</div>
                        </div>
                        <div class="profile-field">
                            <div class="profile-field-label">Código postal</div>
                            <div class="profile-field-value">94102</div>
                        </div>
                    </div>
                </div>
                
                <div class="profile-card">
                    <div class="profile-card-header">
                        <h3 class="profile-card-title">Detalles del empleado</h3>
                    </div>
                    <div class="profile-card-content">
                        <div class="profile-field">
                            <div class="profile-field-label">Fecha de nacimiento</div>
                            <div class="profile-field-value">20 Sep, 1988</div>
                        </div>
                        <div class="profile-field">
                            <div class="profile-field-label">ID Interno</div>
                            <div class="profile-field-value">GER10654</div>
                        </div>
                        <div class="profile-field">
                            <div class="profile-field-label">Cargo</div>
                            <div class="profile-field-value">Gerente de Proyecto</div>
                        </div>
                        <div class="profile-field">
                            <div class="profile-field-label">Fecha de contratación</div>
                            <div class="profile-field-value">05 Ene, 2023</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="profile-right-column">

                <div class="profile-card">
                    <div class="profile-card-header">
                        <h3 class="profile-card-title">Información laboral</h3>
                        <a href="{{ url('admin/perfil/editar') }}" class="edit-button">+ Agregar info</a>
                    </div>
                    <div class="profile-card-content">
                        <div class="profile-grid">
                            <div class="profile-field">
                                <div class="profile-field-label">DEPARTAMENTO</div>
                                <div class="profile-field-value">Asociado Creativo</div>
                            </div>
                            <div class="profile-field">
                                <div class="profile-field-label">DIVISIÓN</div>
                                <div class="profile-field-value">Gestión de Proyectos</div>
                            </div>
                            <div class="profile-field">
                                <div class="profile-field-label">GERENTE</div>
                                <div class="profile-field-value">Alex Foster</div>
                            </div>
                            <div class="profile-field">
                                <div class="profile-field-label">HIRE DATE</div>
                                <div class="profile-field-value">May 13, 2024</div>
                            </div>
                            <div class="profile-field">
                                <div class="profile-field-label">LOCATION</div>
                                <div class="profile-field-value">Metro DC</div>
                            </div>
                        </div>
                        
                        <table class="profile-table" style="width: 100%; margin-top: 20px; border-collapse: collapse;">
                            <tr style="border-bottom: 1px solid #eaeaea;">
                                <td style="padding: 10px 0;">
                                    <div style="font-weight: 500;">Gerente de Proyecto</div>
                                    <div style="font-size: 14px; color: #666;">Tiempo completo</div>
                                </td>
                                <td style="text-align: right; padding: 10px 0;">
                                    <div style="font-size: 14px; color: #666;">Ene 2023 - Presente</div>
                                    <div style="font-size: 14px; color: #666;">1 año, 3 meses</div>
                                </td>
                            </tr>
                            <tr style="border-bottom: 1px solid #eaeaea;">
                                <td style="padding: 10px 0;">
                                    <div style="font-weight: 500;">Asistente de Proyecto</div>
                                    <div style="font-size: 14px; color: #666;">Tiempo completo</div>
                                </td>
                                <td style="text-align: right; padding: 10px 0;">
                                    <div style="font-size: 14px; color: #666;">Mar 2020 - Dic 2022</div>
                                    <div style="font-size: 14px; color: #666;">2 años, 9 meses</div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                
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
