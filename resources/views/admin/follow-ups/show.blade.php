@extends('layouts.admin')

@section('title', 'Detalles del Seguimiento')

@section('page-title', 'Detalles del Seguimiento')

@section('breadcrumb')
    <span class="breadcrumb-separator">/</span>
    <span><a href="{{ url('admin/tablero') }}">Tablero</a></span>
    <span class="breadcrumb-separator">/</span>
    <span><a href="{{ route('admin.all-patient-followups') }}">Pacientes en Seguimiento</a></span>
    <span class="breadcrumb-separator">/</span>
    <span>Detalles del Seguimiento</span>
@endsection

@section('styles')
    <style>
        .detail-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            padding: 30px;
            max-width: 800px;
            margin: 0 auto;
        }
        
        .detail-title {
            font-size: 24px;
            font-weight: 600;
            color: #333;
            margin-bottom: 30px;
            text-align: center;
        }
        
        .patient-card {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
            padding: 20px;
            background-color: #f3f4f6;
            border-radius: 8px;
        }
        
        .patient-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            overflow: hidden;
            margin-right: 20px;
        }
        
        .patient-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .patient-details {
            flex: 1;
        }
        
        .patient-name {
            font-size: 20px;
            font-weight: 600;
            color: #111827;
            margin-bottom: 6px;
        }
        
        .patient-email {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 4px;
        }
        
        .patient-phone {
            font-size: 14px;
            color: #6b7280;
        }
        
        .detail-section {
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
        
        .detail-row {
            display: flex;
            margin-bottom: 12px;
        }
        
        .detail-label {
            width: 150px;
            font-weight: 500;
            color: #6b7280;
        }
        
        .detail-value {
            flex: 1;
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
            border-radius: 6px;
            padding: 15px;
            margin-top: 10px;
            white-space: pre-line;
        }
        
        .action-buttons {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 30px;
        }
        
        .btn {
            padding: 10px 16px;
            border-radius: 6px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
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
            background-color: white;
            color: #4b5563;
            border: 1px solid #d1d5db;
        }
        
        .btn-secondary:hover {
            background-color: #f3f4f6;
        }
        
        .btn-danger {
            background-color: #ef4444;
            color: white;
            border: none;
        }
        
        .btn-danger:hover {
            background-color: #dc2626;
        }
        
        .appointments-section {
            margin-top: 40px;
        }
        
        .appointment-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .appointment-item {
            padding: 15px;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            margin-bottom: 10px;
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
            padding: 20px;
            color: #6b7280;
            background-color: #f9fafb;
            border-radius: 6px;
        }
    </style>
@endsection

@section('content')
<div class="detail-container">
    <h2 class="detail-title">Detalles del Seguimiento</h2>
    
    <div class="patient-card">
        <div class="patient-avatar">
            @if($followUp->patient->photo_path)
                <img src="{{ asset($followUp->patient->photo_path) }}" alt="{{ $followUp->patient->name }}">
            @else
                <img src="{{ asset('assets/default-avatar.png') }}" alt="{{ $followUp->patient->name }}">
            @endif
        </div>
        <div class="patient-details">
            <div class="patient-name">{{ $followUp->patient->name }}</div>
            <div class="patient-email">{{ $followUp->patient->email }}</div>
            <div class="patient-phone">{{ $followUp->patient->phoneNumber }}</div>
        </div>
    </div>
    
    <div class="detail-section">
        <h3 class="section-title">Información del Seguimiento</h3>
        
        <div class="detail-row">
            <div class="detail-label">Estado:</div>
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
        
        <div class="detail-row">
            <div class="detail-label">Fecha de inicio:</div>
            <div class="detail-value">{{ $followUp->start_date->format('d/m/Y') }}</div>
        </div>
        
        <div class="detail-row">
            <div class="detail-label">Fecha de fin:</div>
            <div class="detail-value">{{ $followUp->end_date ? $followUp->end_date->format('d/m/Y') : 'No definida' }}</div>
        </div>
        
        <div class="detail-row">
            <div class="detail-label">Doctor:</div>
            <div class="detail-value">{{ $followUp->doctor->name }}</div>
        </div>
        
        <div class="detail-row">
            <div class="detail-label">Creado:</div>
            <div class="detail-value">{{ $followUp->created_at->format('d/m/Y H:i') }}</div>
        </div>
        
        <div class="detail-row">
            <div class="detail-label">Última actualización:</div>
            <div class="detail-value">{{ $followUp->updated_at->format('d/m/Y H:i') }}</div>
        </div>
        
        <div class="detail-row">
            <div class="detail-label">Notas:</div>
            <div class="detail-value">
                @if($followUp->notes)
                    <div class="notes-box">{{ $followUp->notes }}</div>
                @else
                    <em>Sin notas</em>
                @endif
            </div>
        </div>
    </div>
    
    <div class="appointments-section">
        <h3 class="section-title">Citas Relacionadas</h3>
        
        @if(isset($appointments) && count($appointments) > 0)
            <ul class="appointment-list">
                @foreach($appointments as $appointment)
                    <li class="appointment-item">
                        <div class="appointment-header">
                            <span class="appointment-date">{{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y H:i') }}</span>
                            <span class="appointment-status status-{{ strtolower($appointment->status) }}">{{ $appointment->status }}</span>
                        </div>
                        <div class="appointment-subject">{{ $appointment->subject }}</div>
                        <div class="appointment-details">
                            <strong>Modalidad:</strong> {{ $appointment->modality }} | 
                            <strong>Precio:</strong> ${{ number_format($appointment->price, 2) }}
                        </div>
                    </li>
                @endforeach
            </ul>
        @else
            <div class="no-appointments">
                <p>No hay citas relacionadas con este seguimiento.</p>
            </div>
        @endif
    </div>
    
    <div class="action-buttons">
        <a href="{{ route('admin.all-patient-followups') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
        <a href="{{ route('follow-ups.edit', $followUp->id) }}" class="btn btn-primary">
            <i class="fas fa-edit"></i> Editar
        </a>
        <form action="{{ route('follow-ups.destroy', $followUp->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de eliminar este seguimiento?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">
                <i class="fas fa-trash"></i> Eliminar
            </button>
        </form>
    </div>
</div>
@endsection
