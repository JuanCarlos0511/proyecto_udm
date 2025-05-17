@extends('layouts.public')

@section('title', 'Mis Seguimientos')

@section('styles')
<style>
    .followup-card {
        background-color: #ffffff;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        padding: 20px;
        margin-bottom: 20px;
    }
    
    .followup-header {
        display: flex;
        justify-content: space-between;
        border-bottom: 1px solid #f1f1f1;
        padding-bottom: 10px;
        margin-bottom: 15px;
    }
    
    .followup-title {
        font-size: 18px;
        font-weight: 600;
        color: #3b82f6;
    }
    
    .followup-status {
        padding: 4px 10px;
        border-radius: 50px;
        font-size: 14px;
        font-weight: 500;
        color: white;
    }
    
    .status-active {
        background-color: #10b981;
    }
    
    .status-inactive {
        background-color: #9ca3af;
    }
    
    .status-completed {
        background-color: #3b82f6;
    }
    
    .doctor-info {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
    }
    
    .doctor-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        overflow: hidden;
        margin-right: 10px;
    }
    
    .doctor-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .doctor-name {
        font-weight: 500;
    }
    
    .followup-details {
        margin-bottom: 15px;
    }
    
    .detail-item {
        margin-bottom: 8px;
        display: flex;
    }
    
    .detail-label {
        width: 120px;
        font-weight: 500;
        color: #4b5563;
    }
    
    .next-appointment {
        background-color: #f9fafb;
        border-radius: 6px;
        padding: 12px;
        margin-top: 15px;
    }
    
    .appointment-header {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 10px;
        color: #4f46e5;
    }
    
    .empty-state {
        text-align: center;
        padding: 40px 0;
        color: #6b7280;
    }
    
    .empty-state p {
        margin-top: 10px;
    }
</style>
@endsection

@section('content')
<div class="container py-4">
    <h1 class="text-2xl font-bold mb-6">Mis Seguimientos</h1>
    
    @if(count($followUpGroups) > 0)
        <div class="followups-container">
            @foreach($followUpGroups as $groupId => $group)
                <div class="followup-card">
                    <div class="followup-header">
                        <div class="followup-title">{{ $group['treatment'] }}</div>
                        <div class="followup-status status-{{ $group['status'] }}">
                            @if($group['status'] == 'active')
                                Activo
                            @elseif($group['status'] == 'inactive')
                                Inactivo
                            @elseif($group['status'] == 'completed')
                                Completado
                            @endif
                        </div>
                    </div>
                    
                    <div class="doctor-info">
                        <div class="doctor-avatar">
                            @if($group['doctor']->photo_path)
                                <img src="{{ asset($group['doctor']->photo_path) }}" alt="{{ $group['doctor']->name }}">
                            @else
                                <img src="{{ asset('assets/default-avatar.png') }}" alt="{{ $group['doctor']->name }}">
                            @endif
                        </div>
                        <div>
                            <div class="doctor-name">Dr. {{ $group['doctor']->name }}</div>
                            <div class="doctor-email">{{ $group['doctor']->email }}</div>
                        </div>
                    </div>
                    
                    <div class="followup-details">
                        <div class="detail-item">
                            <div class="detail-label">Tratamiento:</div>
                            <div>{{ $group['treatment'] }}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Fecha inicio:</div>
                            <div>{{ $group['start_date'] }}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Fecha fin:</div>
                            <div>{{ $group['end_date'] }}</div>
                        </div>
                    </div>
                    
                    @if($group['next_appointment'])
                        <div class="next-appointment">
                            <div class="appointment-header">
                                <i class="fas fa-calendar-alt mr-2"></i> Próxima Cita
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Fecha:</div>
                                <div>{{ $group['next_appointment']['date'] }}</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Hora:</div>
                                <div>{{ $group['next_appointment']['time'] }}</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Modalidad:</div>
                                <div>{{ $group['next_appointment']['modality'] }}</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Estado:</div>
                                <div>{{ $group['next_appointment']['status'] }}</div>
                            </div>
                        </div>
                    @else
                        <div class="next-appointment">
                            <div class="appointment-header">
                                <i class="fas fa-calendar-times mr-2"></i> Sin citas programadas
                            </div>
                            <p>No hay citas programadas para este seguimiento.</p>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <i class="fas fa-clipboard-list fa-3x"></i>
            <p>No tienes seguimientos activos en este momento.</p>
        </div>
    @endif
</div>
@endsection
