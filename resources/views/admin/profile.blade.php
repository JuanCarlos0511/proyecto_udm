@extends('layouts.admin')

@section('title', 'Perfil')

@section('page-title', 'Perfil')

@section('breadcrumb')
    <span class="breadcrumb-separator">/</span>
    <span>Perfil</span>
@endsection

@section('styles')
<style>
    .profile-container {
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .profile-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px 24px;
        border-bottom: 1px solid #eaeaea;
    }

    .profile-header h2 {
        font-size: 18px;
        font-weight: 600;
        margin: 0;
    }

    .profile-header-actions button {
        background-color: #000;
        color: white;
        border: none;
        border-radius: 4px;
        padding: 6px 12px;
        font-size: 14px;
        cursor: pointer;
    }

    .profile-tabs {
        display: flex;
        border-bottom: 1px solid #eaeaea;
        margin-bottom: 20px;
    }

    .profile-tab {
        padding: 12px 24px;
        font-size: 14px;
        cursor: pointer;
        border-bottom: 2px solid transparent;
    }

    .profile-tab.active {
        border-bottom: 2px solid #000;
        font-weight: 600;
    }

    .profile-content {
        padding: 24px;
    }
    
    .profile-layout {
        display: flex;
        gap: 30px;
    }
    
    .profile-left-column {
        flex: 0 0 25%;
    }
    
    .profile-right-column {
        flex: 0 0 75%;
    }

    .profile-info {
        display: flex;
        margin-bottom: 24px;
    }

    .profile-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        overflow: hidden;
        margin-right: 24px;
        flex-shrink: 0;
    }

    .profile-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .profile-details {
        flex-grow: 1;
    }

    .profile-name {
        font-size: 20px;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .profile-role {
        color: #666;
        font-size: 14px;
        margin-bottom: 12px;
    }

    .profile-dates {
        display: flex;
        font-size: 14px;
        color: #666;
    }

    .profile-date {
        margin-right: 24px;
    }

    .profile-date i {
        margin-right: 8px;
    }

    .profile-section {
        margin-bottom: 32px;
    }

    .profile-section-title {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 16px;
    }

    .profile-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
    }

    .profile-field {
        margin-bottom: 16px;
    }

    .profile-field-label {
        font-size: 12px;
        color: #666;
        margin-bottom: 4px;
    }

    .profile-field-value {
        font-size: 14px;
        font-weight: 500;
    }

    .activity-section {
        margin-top: 20px;
    }

    .activity-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
    }

    .activity-title {
        font-size: 16px;
        font-weight: 600;
    }

    .activity-list {
        margin-top: 16px;
    }

    .activity-item {
        display: flex;
        margin-bottom: 16px;
        padding-bottom: 16px;
        border-bottom: 1px solid #eaeaea;
    }

    .activity-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        overflow: hidden;
        margin-right: 16px;
        flex-shrink: 0;
    }

    .activity-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .activity-content {
        flex-grow: 1;
    }

    .activity-user {
        font-weight: 500;
        margin-bottom: 4px;
    }

    .activity-action {
        color: #666;
        margin-left: 4px;
    }

    .activity-date {
        font-size: 12px;
        color: #666;
    }

    .activity-details {
        margin-top: 8px;
        font-size: 14px;
        color: #333;
    }

    .see-all {
        text-align: center;
        margin-top: 16px;
    }

    .see-all a {
        color: #666;
        font-size: 14px;
        text-decoration: none;
    }

    .see-all a:hover {
        text-decoration: underline;
    }

    .conversation-section {
        margin-top: 20px;
    }

    .conversation-item {
        display: flex;
        justify-content: space-between;
        padding: 16px 0;
        border-bottom: 1px solid #eaeaea;
    }

    .conversation-user {
        display: flex;
        align-items: center;
    }

    .conversation-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        overflow: hidden;
        margin-right: 16px;
    }

    .conversation-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .conversation-info {
        flex-grow: 1;
    }

    .conversation-name {
        font-weight: 500;
        margin-bottom: 4px;
    }

    .conversation-preview {
        font-size: 14px;
        color: #666;
    }

    .conversation-date {
        font-size: 12px;
        color: #666;
        text-align: right;
    }

    .edit-button {
        color: #007bff;
        background: none;
        border: none;
        font-size: 14px;
        cursor: pointer;
        padding: 0;
    }

    .edit-button:hover {
        text-decoration: underline;
    }
    
    .profile-card {
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        margin-bottom: 20px;
        overflow: hidden;
    }
    
    .profile-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 20px;
        border-bottom: 1px solid #eaeaea;
    }
    
    .profile-card-title {
        font-size: 16px;
        font-weight: 600;
        margin: 0;
    }
    
    .profile-card-content {
        padding: 20px;
    }
    
    .profile-tabs-container {
        display: flex;
        overflow-x: auto;
        border-bottom: 1px solid #eaeaea;
        margin-bottom: 20px;
    }
</style>
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
                                    <div class="profile-field-label">Marketing Team</div>
                                </td>
                                <td style="padding: 10px 0;">
                                    <div class="profile-field-value">Leadership</div>
                                </td>
                                <td style="padding: 10px 0;">
                                    <div class="profile-field-value">Jack Daniel</div>
                                </td>
                                <td style="padding: 10px 0;">
                                    <div class="profile-field-value">Sep 05, 2024</div>
                                </td>
                                <td style="padding: 10px 0;">
                                    <div class="profile-field-value">Bergen, NJ</div>
                                </td>
                                <td style="padding: 10px 0; text-align: right;">
                                    <i class="fas fa-ellipsis-h"></i>
                                </td>
                            </tr>
                            <tr style="border-bottom: 1px solid #eaeaea;">
                                <td style="padding: 10px 0;">
                                    <div class="profile-field-label">Team Lead</div>
                                </td>
                                <td style="padding: 10px 0;">
                                    <div class="profile-field-value">Creator</div>
                                </td>
                                <td style="padding: 10px 0;">
                                    <div class="profile-field-value">Anna Shasha</div>
                                </td>
                                <td style="padding: 10px 0;">
                                    <div class="profile-field-value">Jun 08, 2023</div>
                                </td>
                                <td style="padding: 10px 0;">
                                    <div class="profile-field-value">Miami, FL</div>
                                </td>
                                <td style="padding: 10px 0; text-align: right;">
                                    <i class="fas fa-ellipsis-h"></i>
                                </td>
                            </tr>
                            <tr style="border-bottom: 1px solid #eaeaea;">
                                <td style="padding: 10px 0;">
                                    <div class="profile-field-label">Finance & Accounting</div>
                                </td>
                                <td style="padding: 10px 0;">
                                    <div class="profile-field-value">Senior Consultant</div>
                                </td>
                                <td style="padding: 10px 0;">
                                    <div class="profile-field-value">John Miller</div>
                                </td>
                                <td style="padding: 10px 0;">
                                    <div class="profile-field-value">Sep 13, 2022</div>
                                </td>
                                <td style="padding: 10px 0;">
                                    <div class="profile-field-value">Chicago, IL</div>
                                </td>
                                <td style="padding: 10px 0; text-align: right;">
                                    <i class="fas fa-ellipsis-h"></i>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 10px 0;">
                                    <div class="profile-field-label">Team Lead</div>
                                </td>
                                <td style="padding: 10px 0;">
                                    <div class="profile-field-value">Creator</div>
                                </td>
                                <td style="padding: 10px 0;">
                                    <div class="profile-field-value">Mark Baldwin</div>
                                </td>
                                <td style="padding: 10px 0;">
                                    <div class="profile-field-value">Jul 07, 2023</div>
                                </td>
                                <td style="padding: 10px 0;">
                                    <div class="profile-field-value">Miami, FL</div>
                                </td>
                                <td style="padding: 10px 0; text-align: right;">
                                    <i class="fas fa-ellipsis-h"></i>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <div class="profile-layout" style="margin-top: 20px;">
                    <div class="profile-card" style="flex: 1;">
                        <div class="profile-card-header">
                            <h3 class="profile-card-title">Actividad</h3>
                            <a href="{{ url('admin/perfil/actividad-toda') }}" class="edit-button">Ver todo</a>
                        </div>
                        <div class="profile-card-content">
                            <div class="activity-item">
                                <div class="activity-avatar">
                                    <img src="{{ asset('assets/avatar1.png') }}" alt="Usuario">
                                </div>
                                <div class="activity-content">
                                    <div class="activity-user">
                                        John Miller <span class="activity-action">último inicio de sesión el</span> 13 Jul, 2024
                                    </div>
                                    <div class="activity-date">05:36 PM</div>
                                </div>
                            </div>
                            <div class="activity-item">
                                <div class="activity-avatar">
                                    <img src="{{ asset('assets/avatar2.png') }}" alt="Usuario">
                                </div>
                                <div class="activity-content">
                                    <div class="activity-user">
                                        Merva Sahin <span class="activity-action">fecha de creación el</span> 08 Sep, 2024
                                    </div>
                                    <div class="activity-date">03:12 PM</div>
                                </div>
                            </div>
                            <div class="activity-item">
                                <div class="activity-avatar">
                                    <img src="{{ asset('assets/avatar3.png') }}" alt="Usuario">
                                </div>
                                <div class="activity-content">
                                    <div class="activity-user">
                                        Tammy Collier <span class="activity-action">actualizado el</span> 15 Ago, 2023
                                    </div>
                                    <div class="activity-date">05:36 PM</div>
                                </div>
                            </div>
                            <div class="see-all">
                                <a href="#">Ver todo</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="profile-card" style="flex: 1;">
                        <div class="profile-card-header">
                            <h3 class="profile-card-title">Compensación</h3>
                            <a href="{{ url('admin/perfil/compensaciones-todas') }}" class="edit-button">Ver todo</a>
                        </div>
                        <div class="profile-card-content">
                            <div class="profile-field">
                                <div class="profile-field-value">862.00 USD al mes</div>
                                <div class="profile-field-label">Fecha efectiva el 10 de mayo de 2015</div>
                            </div>
                            <div class="profile-field" style="margin-top: 15px;">
                                <div class="profile-field-value">1560.00 USD cada trimestre</div>
                                <div class="profile-field-label">Fecha efectiva el 08 de junio de 2022</div>
                            </div>
                            <div class="profile-field" style="margin-top: 15px;">
                                <div class="profile-field-value">378.00 USD a la semana</div>
                                <div class="profile-field-label">Fecha efectiva el 08 de junio de 2022</div>
                            </div>
                            <div class="see-all">
                                <a href="#">Ver todo</a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tabs = document.querySelectorAll('.profile-tab');
        
        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                // Remove active class from all tabs
                tabs.forEach(t => t.classList.remove('active'));
                
                // Add active class to clicked tab
                this.classList.add('active');
                
                // Here you would typically show/hide content based on selected tab
                // For now, we'll just keep the first tab's content visible
            });
        });
    });
</script>
@endsection
