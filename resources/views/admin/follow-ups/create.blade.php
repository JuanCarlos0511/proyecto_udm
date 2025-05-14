@extends('layouts.admin')

@section('title', 'Agregar Paciente a Seguimiento')

@section('page-title', 'Agregar Paciente a Seguimiento')

@section('breadcrumb')
    <span class="breadcrumb-separator">/</span>
    <span><a href="{{ url('admin/tablero') }}">Tablero</a></span>
    <span class="breadcrumb-separator">/</span>
    <span><a href="{{ route('admin.all-patient-followups') }}">Pacientes en Seguimiento</a></span>
    <span class="breadcrumb-separator">/</span>
    <span>Agregar Paciente</span>
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
    </style>
@endsection

@section('content')
<div class="form-container">
    <h2 class="form-title">Agregar Paciente a Seguimiento</h2>
    
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
    
    <form id="followUpForm" action="{{ route('follow-ups.store') }}" method="POST">
        @csrf
        
        <div class="form-group">
            <label for="patient_id" class="form-label">Paciente *</label>
            <select id="patient_id" name="patient_id" class="form-select" required>
                <option value="">Selecciona un paciente</option>
                @foreach($patients as $patient)
                    <option value="{{ $patient->id }}">{{ $patient->name }} - {{ $patient->email }}</option>
                @endforeach
            </select>
            @error('patient_id')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="notes" class="form-label">Notas</label>
            <textarea id="notes" name="notes" class="form-control form-textarea" placeholder="Escribe notas sobre el seguimiento del paciente">{{ old('notes') }}</textarea>
            @error('notes')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="start_date" class="form-label">Fecha de inicio *</label>
            <input type="text" id="start_date" name="start_date" class="form-control datepicker" value="{{ old('start_date', date('Y-m-d')) }}" required>
            @error('start_date')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="end_date" class="form-label">Fecha de fin (opcional)</label>
            <input type="text" id="end_date" name="end_date" class="form-control datepicker" value="{{ old('end_date') }}">
            @error('end_date')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-actions">
            <a href="{{ route('admin.all-patient-followups') }}" class="btn-cancel">Cancelar</a>
            <button type="submit" class="btn-submit">Guardar</button>
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
        
        // Validación del formulario
        const form = document.getElementById('followUpForm');
        form.addEventListener('submit', function(event) {
            let isValid = true;
            
            // Validar paciente
            const patientSelect = document.getElementById('patient_id');
            if (!patientSelect.value) {
                isValid = false;
                patientSelect.classList.add('error');
                const errorDiv = document.createElement('div');
                errorDiv.className = 'error-message';
                errorDiv.textContent = 'Debes seleccionar un paciente';
                patientSelect.parentNode.appendChild(errorDiv);
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
