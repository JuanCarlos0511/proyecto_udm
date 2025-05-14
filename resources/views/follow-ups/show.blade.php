@extends('layouts.app')

@section('title', 'Detalles de Seguimiento')

@section('container-class', '')

@section('extra-css')
    <link rel="stylesheet" href="{{ asset('css/components/history.css') }}">
    <style>
        .follow-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 30px 20px;
        }
        
        .follow-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        
        .title-section h1 {
            font-size: 28px;
            color: #111827;
            margin-bottom: 8px;
        }
        
        .title-section .subtitle {
            color: #6b7280;
            font-size: 16px;
        }
        
        .doctor-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            margin-bottom: 30px;
        }
        
        .doctor-header {
            display: flex;
            align-items: center;
            padding: 20px;
            background-color: #f9fafb;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .doctor-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            overflow: hidden;
            margin-right: 20px;
        }
        
        .doctor-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .doctor-info {
            flex: 1;
        }
        
        .doctor-name {
            font-size: 20px;
            font-weight: 600;
            color: #111827;
            margin-bottom: 5px;
        }
        
        .doctor-specialty {
            font-size: 14px;
            color: #6b7280;
        }
        
        .doctor-contact {
            margin-left: 20px;
            text-align: right;
        }
        
        .contact-item {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            margin-bottom: 8px;
            font-size: 14px;
            color: #4b5563;
        }
        
        .contact-item i {
            color: #6366f1;
            margin-right: 8px;
        }
        
        .follow-body {
            padding: 20px;
        }
        
        .follow-section {
            margin-bottom: 30px;
        }
        
        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: #4b5563;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .follow-details {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 15px;
        }
        
        .detail-item {
            background-color: #f9fafb;
            border-radius: 8px;
            padding: 15px;
        }
        
        .detail-label {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 5px;
        }
        
        .detail-value {
            font-size: 16px;
            font-weight: 500;
            color: #111827;
        }
        
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            color: white;
            font-weight: 500;
            font-size: 14px;
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
        
        .notes-box {
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 20px;
            margin-top: 10px;
            white-space: pre-line;
        }
        
        .appointments-list {
            margin-top: 20px;
        }
        
        .appointment-item {
            background-color: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            transition: all 0.2s;
        }
        
        .appointment-item:hover {
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }
        
        .appointment-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        
        .appointment-date {
            font-weight: 500;
            color: #111827;
        }
        
        .appointment-status {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
            color: white;
        }
        
        .status-solicitado {
            background-color: #f59e0b;
        }
        
        .status-agendado {
            background-color: #3b82f6;
        }
        
        .status-completado {
            background-color: #10b981;
        }
        
        .status-cancelado {
            background-color: #ef4444;
        }
        
        .appointment-subject {
            font-weight: 500;
            margin-bottom: 5px;
        }
        
        .appointment-details {
            font-size: 14px;
            color: #6b7280;
        }
        
        .no-appointments {
            text-align: center;
            padding: 30px;
            background-color: #f9fafb;
            border-radius: 8px;
            color: #6b7280;
        }
        
        .action-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }
        
        .btn {
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            transition: all 0.2s;
        }
        
        .btn i {
            margin-right: 8px;
        }
        
        .btn-primary {
            background-color: #6366f1;
            color: white;
            border: none;
        }
        
        .btn-primary:hover {
            background-color: #4f46e5;
        }
        
        .btn-secondary {
            background-color: #f3f4f6;
            color: #4b5563;
            border: 1px solid #e5e7eb;
        }
        
        .btn-secondary:hover {
            background-color: #e5e7eb;
        }
    </style>
@endsection

@section('content')
    <div class="follow-container">
        <div class="follow-header">
            <div class="title-section">
                <h1>Detalles de Seguimiento</h1>
                <p class="subtitle">Información sobre tu seguimiento médico</p>
            </div>
        </div>
        
        <div class="doctor-card">
            <div class="doctor-header">
                <div class="doctor-avatar">
                    @if($followUp->doctor->photo_path)
                        <img src="{{ asset($followUp->doctor->photo_path) }}" alt="{{ $followUp->doctor->name }}">
                    @else
                        <img src="{{ asset('assets/default-doctor.png') }}" alt="{{ $followUp->doctor->name }}">
                    @endif
                </div>
                <div class="doctor-info">
                    <h3 class="doctor-name">{{ $followUp->doctor->name }}</h3>
                    <p class="doctor-specialty">Doctor</p>
                </div>
                <div class="doctor-contact">
                    @if($followUp->doctor->email)
                        <div class="contact-item">
                            <i class="fas fa-envelope"></i>
                            <span>{{ $followUp->doctor->email }}</span>
                        </div>
                    @endif
                    @if($followUp->doctor->phoneNumber)
                        <div class="contact-item">
                            <i class="fas fa-phone"></i>
                            <span>{{ $followUp->doctor->phoneNumber }}</span>
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="follow-body">
                <div class="follow-section">
                    <h3 class="section-title">Información del Seguimiento</h3>
                    <div class="follow-details">
                        <div class="detail-item">
                            <div class="detail-label">Estado</div>
                            <div class="detail-value">
                                @if($followUp->status == 'active')
                                    <span class="status-badge status-active">Activo</span>
                                @elseif($followUp->status == 'inactive')
                                    <span class="status-badge status-inactive">Inactivo</span>
                                @else
                                    <span class="status-badge status-completed">Completado</span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="detail-item">
                            <div class="detail-label">Fecha de inicio</div>
                            <div class="detail-value">{{ $followUp->start_date->format('d/m/Y') }}</div>
                        </div>
                        
                        <div class="detail-item">
                            <div class="detail-label">Fecha de fin</div>
                            <div class="detail-value">{{ $followUp->end_date ? $followUp->end_date->format('d/m/Y') : 'No definida' }}</div>
                        </div>
                        
                        <div class="detail-item">
                            <div class="detail-label">Última actualización</div>
                            <div class="detail-value">{{ $followUp->updated_at->format('d/m/Y') }}</div>
                        </div>
                    </div>
                    
                    @if($followUp->notes)
                        <div class="follow-section">
                            <h3 class="section-title">Notas del Doctor</h3>
                            <div class="notes-box">
                                {{ $followUp->notes }}
                            </div>
                        </div>
                    @endif
                </div>
                
                <div class="follow-section">
                    <h3 class="section-title">Citas Relacionadas</h3>
                    
                    @if(isset($appointments) && count($appointments) > 0)
                        <div class="appointments-list">
                            @foreach($appointments as $appointment)
                                <div class="appointment-item">
                                    <div class="appointment-header">
                                        <span class="appointment-date">{{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y H:i') }}</span>
                                        <span class="appointment-status status-{{ strtolower($appointment->status) }}">{{ $appointment->status }}</span>
                                    </div>
                                    <div class="appointment-subject">{{ $appointment->subject }}</div>
                                    <div class="appointment-details">
                                        <strong>Modalidad:</strong> {{ $appointment->modality }} | 
                                        <strong>Precio:</strong> ${{ number_format($appointment->price, 2) }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="no-appointments">
                            <p>No hay citas relacionadas con este seguimiento.</p>
                        </div>
                    @endif
                </div>
                
                <div class="action-buttons">
                    <a href="{{ url('seguimiento') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Volver a Seguimiento
                    </a>
                    <a href="{{ route('appointment.clinic') }}?doctor_id={{ $followUp->doctor->id }}" class="btn btn-primary">
                        <i class="fas fa-calendar-plus"></i> Agendar Cita
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
