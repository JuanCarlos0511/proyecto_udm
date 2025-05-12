@extends('layouts.admin')

@section('title', 'Gestión de Doctores')

@section('page-title', 'Gestión de Doctores')

@section('breadcrumb')
    <span class="breadcrumb-separator">/</span>
    <span>Doctores</span>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/doctors.css') }}">
@endsection

@section('content')
<div class="doctors-container">
    <div class="doctors-header">
        <h2 class="doctors-title">Listado de Doctores</h2>
        <div class="doctors-actions">
            <button class="btn-add" id="addDoctorBtn">
                <i class="fas fa-plus"></i>
                Agregar Doctor
            </button>
        </div>
    </div>
    
    <div class="doctors-filters">
        <div class="search-box">
            <i class="fas fa-search search-icon"></i>
            <input type="text" class="search-input" placeholder="Buscar doctor...">
        </div>
        <div class="filter-options">
            <select class="filter-select">
                <option value="">Todas las especialidades</option>
                <option value="general">Medicina General</option>
                <option value="dental">Odontología</option>
                <option value="cardio">Cardiología</option>
                <option value="pediatria">Pediatría</option>
            </select>
            <select class="filter-select">
                <option value="">Todos los estados</option>
                <option value="active">Activo</option>
                <option value="inactive">Inactivo</option>
            </select>
        </div>
    </div>
    
    <table class="doctors-table">
        <thead>
            <tr>
                <th>Doctor</th>
                <th>Especialidad</th>
                <th>Correo</th>
                <th>Teléfono</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($doctors as $doctor)
            <tr>
                <td>
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div class="doctor-avatar">
                            <img src="{{ $doctor->avatar ? $doctor->avatar : asset('assets/profile.png') }}" alt="{{ $doctor->name }}">
                        </div>
                        <div>
                            <div class="doctor-name">{{ $doctor->name }}</div>
                            <div class="doctor-specialty">{{ ucfirst($doctor->role) }}</div>
                        </div>
                    </div>
                </td>
                <td>{{ ucfirst($doctor->role) }}</td>
                <td>{{ $doctor->email }}</td>
                <td>{{ $doctor->phoneNumber }}</td>
                <td><span class="status-badge status-{{ $doctor->status }}">{{ ucfirst($doctor->status) }}</span></td>
                <td>
                    <div class="doctor-actions">
                        <button class="action-btn edit-btn edit-doctor-btn" data-id="{{ $doctor->id }}" title="Editar doctor">
                            <i class="fas fa-edit"></i>
                        </button>
                    </div>
                </td>
            </tr>
            @endforeach

            @if(count($doctors) == 0)
            <tr>
                <td colspan="6" class="text-center">No hay doctores registrados</td>
            </tr>
            @endif
        </tbody>
    </table>
    
    <div class="pagination">
        <div class="page-item active">1</div>
        <div class="page-item">2</div>
        <div class="page-item">3</div>
        <div class="page-item">
            <i class="fas fa-chevron-right"></i>
        </div>
    </div>
</div>

<!-- Modal para agregar/editar doctor -->
<div class="modal-overlay" id="doctorModal">
    <div class="modal-container">
        <div class="modal-header">
            <h3 class="modal-title" id="modalTitle">Agregar Doctor</h3>
            <button class="modal-close" id="closeModal">&times;</button>
        </div>
        <div class="modal-body">
            <form id="doctorForm">
                <input type="hidden" id="doctorId">
                <div class="form-group">
                    <label for="doctorName" class="form-label">Nombre completo</label>
                    <input type="text" id="doctorName" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="doctorSpecialty" class="form-label">Especialidad</label>
                    <select id="doctorSpecialty" class="form-select" required>
                        <option value="">Seleccionar especialidad</option>
                        <option value="general">Medicina General</option>
                        <option value="dental">Odontología</option>
                        <option value="cardio">Cardiología</option>
                        <option value="pediatria">Pediatría</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="doctorEmail" class="form-label">Correo electrónico</label>
                    <input type="email" id="doctorEmail" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="doctorPhone" class="form-label">Teléfono</label>
                    <input type="tel" id="doctorPhone" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="doctorStatus" class="form-label">Estado</label>
                    <select id="doctorStatus" class="form-select" required>
                        <option value="active">Activo</option>
                        <option value="inactive">Inactivo</option>
                    </select>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" id="cancelBtn">Cancelar</button>
            <button class="btn btn-primary" id="saveBtn">Guardar</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="{{ asset('js/admin/doctors.js') }}"></script>
@endsection
