@extends('layouts.admin')

@section('title', 'Editar Cita')

@section('page-title', 'Editar Cita')

@section('breadcrumb')
    <span class="breadcrumb-separator">/</span>
    <span><a href="{{ url('admin/tablero') }}">Tablero</a></span>
    <span class="breadcrumb-separator">/</span>
    <span><a href="{{ route('admin.appointments.index') }}">Citas</a></span>
    <span class="breadcrumb-separator">/</span>
    <span>Editar Cita</span>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/appointments.css') }}">
@endsection

@section('content')
<div class="appointments-container">
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.appointments.update', $appointment->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label for="patient">Paciente</label>
                    <select name="patient_id" id="patient" class="form-control" required>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}" {{ $appointment->user_id == $patient->id ? 'selected' : '' }}>
                                {{ $patient->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="date">Fecha</label>
                    <input type="date" name="date" id="date" class="form-control" 
                           value="{{ \Carbon\Carbon::parse($appointment->date)->format('Y-m-d') }}" required>
                </div>

                <div class="form-group">
                    <label for="time">Hora</label>
                    <input type="time" name="time" id="time" class="form-control" 
                           value="{{ \Carbon\Carbon::parse($appointment->date)->format('H:i') }}" required>
                </div>

                <div class="form-group">
                    <label for="status">Estado</label>
                    <select name="status" id="status" class="form-control" required>
                        <option value="Solicitado" {{ $appointment->status == 'Solicitado' ? 'selected' : '' }}>Solicitado</option>
                        <option value="Agendado" {{ $appointment->status == 'Agendado' ? 'selected' : '' }}>Agendado</option>
                        <option value="Completado" {{ $appointment->status == 'Completado' ? 'selected' : '' }}>Completado</option>
                        <option value="Cancelado" {{ $appointment->status == 'Cancelado' ? 'selected' : '' }}>Cancelado</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="subject">Asunto</label>
                    <input type="text" name="subject" id="subject" class="form-control" 
                           value="{{ $appointment->subject }}" required>
                </div>

                <div class="form-group">
                    <label for="modality">Modalidad</label>
                    <select name="modality" id="modality" class="form-control" required>
                        <option value="Consultorio" {{ $appointment->modality == 'Consultorio' ? 'selected' : '' }}>Consultorio</option>
                        <option value="Domicilio" {{ $appointment->modality == 'Domicilio' ? 'selected' : '' }}>Domicilio</option>
                    </select>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    <a href="{{ route('admin.appointments.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Aquí puedes agregar cualquier JavaScript necesario para la página de edición
});
</script>
@endsection
