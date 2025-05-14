@extends('layouts.admin')

@section('title', 'Editar Seguimiento')

@section('page-title', 'Editar Seguimiento')

@section('breadcrumb')
    <span class="breadcrumb-separator">/</span>
    <span><a href="{{ url('admin/tablero') }}">Tablero</a></span>
    <span class="breadcrumb-separator">/</span>
    <span><a href="{{ route('admin.all-patient-followups') }}">Pacientes en Seguimiento</a></span>
    <span class="breadcrumb-separator">/</span>
    <span>Editar Seguimiento</span>
@endsection

@section('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        .form-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            padding: 30px;
            max-width: 800px;
            margin: 0 auto;
        }
        
        .form-title {
            font-size: 24px;
            font-weight: 600;
            color: #333;
            margin-bottom: 30px;
            text-align: center;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            display: block;
            font-weight: 500;
            margin-bottom: 8px;
            color: #4b5563;
        }
        
        .form-control {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.2s;
        }
        
        .form-control:focus {
            border-color: #6366f1;
            outline: none;
        }
        
        .form-select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.2s;
            background-color: white;
        }
        
        .form-textarea {
            min-height: 120px;
            resize: vertical;
        }
        
        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 30px;
        }
        
        .btn-cancel {
            padding: 10px 16px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            background-color: white;
            color: #4b5563;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .btn-cancel:hover {
            background-color: #f3f4f6;
        }
        
        .btn-submit {
            padding: 10px 16px;
            border: none;
            border-radius: 6px;
            background-color: #6366f1;
            color: white;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .btn-submit:hover {
            background-color: #4f46e5;
        }
        
        .error-message {
            color: #ef4444;
            font-size: 12px;
            margin-top: 4px;
        }
        
        .alert {
            padding: 12px 16px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
        
        .alert-success {
            background-color: #d1fae5;
            border: 1px solid #10b981;
            color: #065f46;
        }
        
        .alert-danger {
            background-color: #fee2e2;
            border: 1px solid #ef4444;
            color: #b91c1c;
        }
        
        .patient-info {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f3f4f6;
            border-radius: 8px;
        }
        
        .patient-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            overflow: hidden;
            margin-right: 15px;
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
            font-size: 18px;
            font-weight: 600;
            color: #111827;
            margin-bottom: 4px;
        }
        
        .patient-email {
            font-size: 14px;
            color: #6b7280;
        }
    </style>
@endsection

@section('content')
<div class="form-container">
    <h2 class="form-title">Editar Seguimiento</h2>
    
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    
    <div class="patient-info">
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
        </div>
    </div>
    
    <form id="followUpForm" action="{{ route('follow-ups.update', $followUp->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label for="status" class="form-label">Estado *</label>
            <select id="status" name="status" class="form-select" required>
                <option value="active" {{ $followUp->status == 'active' ? 'selected' : '' }}>Activo</option>
                <option value="inactive" {{ $followUp->status == 'inactive' ? 'selected' : '' }}>Inactivo</option>
                <option value="completed" {{ $followUp->status == 'completed' ? 'selected' : '' }}>Completado</option>
            </select>
            @error('status')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="notes" class="form-label">Notas</label>
            <textarea id="notes" name="notes" class="form-control form-textarea" placeholder="Escribe notas sobre el seguimiento del paciente">{{ old('notes', $followUp->notes) }}</textarea>
            @error('notes')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="start_date" class="form-label">Fecha de inicio *</label>
            <input type="text" id="start_date" name="start_date" class="form-control datepicker" value="{{ old('start_date', $followUp->start_date->format('Y-m-d')) }}" required>
            @error('start_date')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="end_date" class="form-label">Fecha de fin (opcional)</label>
            <input type="text" id="end_date" name="end_date" class="form-control datepicker" value="{{ old('end_date', $followUp->end_date ? $followUp->end_date->format('Y-m-d') : '') }}">
            @error('end_date')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-actions">
            <a href="{{ route('admin.all-patient-followups') }}" class="btn-cancel">Cancelar</a>
            <button type="submit" class="btn-submit">Actualizar</button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar datepickers
        const startDatePicker = flatpickr("#start_date", {
            dateFormat: "Y-m-d",
            locale: "es",
            maxDate: "today",
            onChange: function(selectedDates) {
                // Actualizar la fecha mínima del selector de fecha final
                if (selectedDates.length > 0) {
                    endDatePicker.set('minDate', selectedDates[0]);
                }
            }
        });
        
        const endDatePicker = flatpickr("#end_date", {
            dateFormat: "Y-m-d",
            locale: "es",
            allowInput: true
        });
        
        // Si hay una fecha de inicio, establecer la fecha mínima para la fecha de fin
        const startDate = document.getElementById('start_date').value;
        if (startDate) {
            endDatePicker.set('minDate', startDate);
        }
        
        // Validación del formulario
        const form = document.getElementById('followUpForm');
        form.addEventListener('submit', function(event) {
            let isValid = true;
            
            // Validar estado
            const statusSelect = document.getElementById('status');
            if (!statusSelect.value) {
                isValid = false;
                statusSelect.classList.add('error');
                const errorDiv = document.createElement('div');
                errorDiv.className = 'error-message';
                errorDiv.textContent = 'Debes seleccionar un estado';
                statusSelect.parentNode.appendChild(errorDiv);
            }
            
            // Validar fecha de inicio
            const startDate = document.getElementById('start_date');
            if (!startDate.value) {
                isValid = false;
                startDate.classList.add('error');
                const errorDiv = document.createElement('div');
                errorDiv.className = 'error-message';
                errorDiv.textContent = 'La fecha de inicio es requerida';
                startDate.parentNode.appendChild(errorDiv);
            }
            
            // Validar fecha de fin (si está presente)
            const endDate = document.getElementById('end_date');
            if (endDate.value && new Date(endDate.value) < new Date(startDate.value)) {
                isValid = false;
                endDate.classList.add('error');
                const errorDiv = document.createElement('div');
                errorDiv.className = 'error-message';
                errorDiv.textContent = 'La fecha de fin debe ser posterior a la fecha de inicio';
                endDate.parentNode.appendChild(errorDiv);
            }
            
            if (!isValid) {
                event.preventDefault();
            }
        });
    });
</script>
@endsection
