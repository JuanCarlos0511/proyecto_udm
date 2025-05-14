@extends('layouts.app')

@section('title', 'Seguimiento')

@section('container-class', '')

@section('extra-css')
    <link rel="stylesheet" href="{{ asset('css/components/history.css') }}">
    <style>
        .doctors-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 30px 20px;
        }
        
        .doctors-header {
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
        
        .filter-tabs {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            padding: 15px 20px;
            margin-bottom: 20px;
        }
        
        .tab-buttons {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
        }
        
        .tab-btn {
            padding: 8px 16px;
            border: none;
            background-color: #f3f4f6;
            border-radius: 6px;
            font-weight: 500;
            color: #6b7280;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .tab-btn:hover {
            background-color: #e5e7eb;
        }
        
        .tab-btn.active {
            background-color: #6366f1;
            color: white;
        }
        
        .doctors-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }
        
        .doctor-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .doctor-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .doctor-header {
            position: relative;
            height: 100px;
            background-color: #6366f1;
        }
        
        .doctor-avatar {
            position: absolute;
            bottom: -40px;
            left: 20px;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            border: 4px solid white;
            overflow: hidden;
            background-color: white;
        }
        
        .doctor-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .doctor-body {
            padding: 50px 20px 20px;
        }
        
        .doctor-name {
            font-size: 18px;
            font-weight: 600;
            color: #111827;
            margin-bottom: 5px;
        }
        
        .doctor-specialty {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 15px;
        }
        
        .doctor-info {
            margin-bottom: 15px;
        }
        
        .info-item {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
            font-size: 14px;
            color: #4b5563;
        }
        
        .info-item i {
            width: 20px;
            color: #6366f1;
            margin-right: 8px;
        }
        
        .doctor-actions {
            display: flex;
            gap: 10px;
        }
        
        .doctor-btn {
            flex: 1;
            padding: 8px 0;
            border: none;
            border-radius: 6px;
            font-weight: 500;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            transition: all 0.2s;
        }
        
        .btn-primary {
            background-color: #6366f1;
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #4f46e5;
        }
        
        .btn-secondary {
            background-color: #f3f4f6;
            color: #4b5563;
        }
        
        .btn-secondary:hover {
            background-color: #e5e7eb;
        }
        
        .follow-since {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 15px;
        }
        
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        
        .empty-state i {
            font-size: 48px;
            color: #d1d5db;
            margin-bottom: 20px;
        }
        
        .empty-state h3 {
            font-size: 20px;
            font-weight: 600;
            color: #111827;
            margin-bottom: 10px;
        }
        
        .empty-state p {
            font-size: 16px;
            color: #6b7280;
            margin-bottom: 20px;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }
    </style>
@endsection

@section('content')
    <div class="doctors-container">
        <div class="doctors-header">
            <div class="title-section">
                <h1>Seguimiento</h1>
                <p class="subtitle">Doctores que te están dando seguimiento</p>
            </div>
        </div>
        
        <div class="filter-tabs">
            <div class="tab-buttons">
                <button class="tab-btn active" data-filter="all">Todos</button>
                <button class="tab-btn" data-filter="recent">Agregados recientemente</button>
                <button class="tab-btn" data-filter="appointment">Con cita próxima</button>
            </div>
        </div>
        
        @if(isset($followUps) && $followUps->count() > 0)
            <div class="doctors-grid">
                @foreach($followUps as $followUp)
                    <div class="doctor-card" data-doctor-id="{{ $followUp->doctor->id }}">
                        <div class="doctor-header">
                            <div class="doctor-avatar">
                                @if($followUp->doctor->photo_path)
                                    <img src="{{ asset($followUp->doctor->photo_path) }}" alt="{{ $followUp->doctor->name }}">
                                @else
                                    <img src="{{ asset('assets/default-doctor.png') }}" alt="{{ $followUp->doctor->name }}">
                                @endif
                            </div>
                        </div>
                        <div class="doctor-body">
                            <h3 class="doctor-name">{{ $followUp->doctor->name }}</h3>
                            <p class="doctor-specialty">Doctor</p>
                            <p class="follow-since">En seguimiento desde {{ $followUp->start_date->format('d/m/Y') }}</p>
                            
                            <div class="doctor-info">
                                <div class="info-item">
                                    <i class="fas fa-envelope"></i>
                                    <span>{{ $followUp->doctor->email }}</span>
                                </div>
                                @if($followUp->doctor->phoneNumber)
                                    <div class="info-item">
                                        <i class="fas fa-phone"></i>
                                        <span>{{ $followUp->doctor->phoneNumber }}</span>
                                    </div>
                                @endif
                                @if($followUp->notes)
                                    <div class="info-item">
                                        <i class="fas fa-sticky-note"></i>
                                        <span>{{ \Illuminate\Support\Str::limit($followUp->notes, 50) }}</span>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="doctor-actions">
                                <a href="{{ route('appointment.clinic') }}?doctor_id={{ $followUp->doctor->id }}" class="doctor-btn btn-primary">
                                    <i class="fas fa-calendar-plus"></i> Agendar Cita
                                </a>
                                <a href="{{ url('seguimiento', $followUp->id) }}" class="doctor-btn btn-secondary">
                                    <i class="fas fa-eye"></i> Ver Detalles
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-user-md"></i>
                <h3>No tienes doctores en seguimiento</h3>
                <p>Cuando un doctor te agregue a su lista de seguimiento, aparecerá aquí. Esto te permitirá ver tus citas con ese doctor y agendar nuevas citas fácilmente.</p>
            </div>
        @endif
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Filtrado de doctores
            const tabButtons = document.querySelectorAll('.tab-btn');
            const doctorCards = document.querySelectorAll('.doctor-card');
            
            tabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Remover clase activa de todos los botones
                    tabButtons.forEach(btn => btn.classList.remove('active'));
                    
                    // Agregar clase activa al botón clickeado
                    this.classList.add('active');
                    
                    // Obtener el filtro
                    const filter = this.dataset.filter;
                    
                    // Aplicar filtro
                    if (filter === 'all') {
                        doctorCards.forEach(card => card.style.display = 'block');
                    } else if (filter === 'recent') {
                        // Aquí podrías implementar la lógica para filtrar por doctores recientes
                        // Por ahora, solo mostramos todos
                        doctorCards.forEach(card => card.style.display = 'block');
                    } else if (filter === 'appointment') {
                        // Aquí podrías implementar la lógica para filtrar por doctores con cita próxima
                        // Por ahora, solo mostramos todos
                        doctorCards.forEach(card => card.style.display = 'block');
                    }
                });
            });
        });
    </script>
@endsection