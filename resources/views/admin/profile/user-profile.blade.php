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
                
                <div class="profile-card salary-card">
                    <div class="profile-card-header">
                        <h3 class="profile-card-title">Mi Salario</h3>
                        <div class="period-selector">2025-05</div>
                    </div>
                    <div class="profile-card-content salary-content">
                        <div class="salary-overview">
                            <div class="total-salary">
                                <div class="salary-label">Monto Total del Mes</div>
                                <div class="salary-amount">$ <span style="color: #28a745; font-weight: bold; font-size: 28px;">41,000.00</span></div>
                                <div class="salary-period">/ año</div>
                            </div>
                            
                            <div class="salary-composition-chart">
                                <div class="chart-container" style="position: relative; width: 180px; height: 180px;">
                                    <!-- Aquí iría un gráfico circular, por ahora usamos un placeholder -->
                                    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border-radius: 50%; background: conic-gradient(#4e73df 0% 70%, #1cc88a 70% 85%, #36b9cc 85% 100%);"></div>
                                    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center; background: white; width: 120px; height: 120px; border-radius: 50%; display: flex; flex-direction: column; justify-content: center;">
                                        <div style="font-size: 14px; color: #666;">Salary</div>
                                        <div style="font-size: 16px; font-weight: bold;">Composition</div>
                                    </div>
                                </div>
                                
                                <div class="chart-legend">
                                    <div class="legend-item">
                                        <span class="legend-color" style="background-color: #4e73df;"></span>
                                        <span class="legend-text">Salario base</span>
                                    </div>
                                    <div class="legend-item">
                                        <span class="legend-color" style="background-color: #1cc88a;"></span>
                                        <span class="legend-text">Bonos</span>
                                    </div>
                                    <div class="legend-item">
                                        <span class="legend-color" style="background-color: #36b9cc;"></span>
                                        <span class="legend-text">Incentivos</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="salary-details">
                            <div class="salary-detail-item">
                                <div class="detail-icon" style="background-color: #4e73df;">
                                    <i class="fas fa-dollar-sign"></i>
                                </div>
                                <div class="detail-info">
                                    <div class="detail-label">Salario Base</div>
                                    <div class="detail-value">$30,000.00</div>
                                </div>
                            </div>
                            
                            <div class="salary-detail-item">
                                <div class="detail-icon" style="background-color: #1cc88a;">
                                    <i class="fas fa-gift"></i>
                                </div>
                                <div class="detail-info">
                                    <div class="detail-label">Bonos</div>
                                    <div class="detail-value">$5,000.00</div>
                                </div>
                            </div>
                            
                            <div class="salary-detail-item">
                                <div class="detail-icon" style="background-color: #36b9cc;">
                                    <i class="fas fa-award"></i>
                                </div>
                                <div class="detail-info">
                                    <div class="detail-label">Incentivos</div>
                                    <div class="detail-value">$6,000.00</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <style>
                        .salary-card {
                            margin-bottom: 30px;
                        }
                        .salary-content {
                            padding: 20px;
                        }
                        .period-selector {
                            background-color: #f8f9fc;
                            padding: 5px 10px;
                            border-radius: 4px;
                            font-size: 14px;
                            color: #666;
                        }
                        .salary-overview {
                            display: flex;
                            justify-content: space-between;
                            margin-bottom: 30px;
                            align-items: center;
                        }
                        .total-salary {
                            padding: 20px;
                            background-color: #f8f9fc;
                            border-radius: 8px;
                            width: 45%;
                        }
                        .salary-label {
                            font-size: 14px;
                            color: #666;
                            margin-bottom: 5px;
                        }
                        .salary-amount {
                            font-size: 20px;
                            font-weight: bold;
                            margin-bottom: 5px;
                        }
                        .salary-period {
                            font-size: 14px;
                            color: #666;
                        }
                        .salary-composition-chart {
                            display: flex;
                            align-items: center;
                            width: 50%;
                        }
                        .chart-legend {
                            margin-left: 15px;
                        }
                        .legend-item {
                            display: flex;
                            align-items: center;
                            margin-bottom: 8px;
                        }
                        .legend-color {
                            width: 12px;
                            height: 12px;
                            border-radius: 2px;
                            margin-right: 8px;
                        }
                        .legend-text {
                            font-size: 13px;
                            color: #666;
                        }
                        .salary-details {
                            display: flex;
                            justify-content: space-between;
                            flex-wrap: wrap;
                        }
                        .salary-detail-item {
                            display: flex;
                            align-items: center;
                            width: 30%;
                            background-color: #f8f9fc;
                            padding: 15px;
                            border-radius: 8px;
                            margin-bottom: 15px;
                        }
                        .detail-icon {
                            width: 40px;
                            height: 40px;
                            border-radius: 50%;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            margin-right: 15px;
                            color: white;
                        }
                        .detail-label {
                            font-size: 13px;
                            color: #666;
                            margin-bottom: 5px;
                        }
                        .detail-value {
                            font-weight: bold;
                            font-size: 16px;
                        }
                        
                        @media (max-width: 768px) {
                            .salary-overview {
                                flex-direction: column;
                            }
                            .total-salary, .salary-composition-chart {
                                width: 100%;
                                margin-bottom: 20px;
                            }
                            .salary-detail-item {
                                width: 100%;
                            }
                        }
                    </style>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
