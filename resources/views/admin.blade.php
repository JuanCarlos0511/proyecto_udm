@extends('layouts.app')

@section('title', 'Administrar médicos')

@section('content')
<div class="admin-container">
    <h1>Administrar medicos</h1>
    
    <div class="admin-controls">
        <button class="btn-add-doctor">
            Agregar medico <i class="fas fa-plus"></i>
        </button>
        
        <div class="search-container">
            <span class="search-arrow">&gt;</span>
            <input type="text" class="search-input" placeholder="Buscar">
            <button class="search-button">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </div>

    <div class="doctors-list">
        <div class="doctor-row">
            <div class="doctor-info">
                <i class="fas fa-user doctor-icon"></i>
                <span class="doctor-name">Dr. Alejandro Ramos</span>
            </div>
            <div class="doctor-actions">
                <button class="action-btn" title="Ver">
                    <i class="fas fa-eye"></i>
                </button>
                <button class="action-btn" title="Eliminar">
                    <i class="fas fa-trash"></i>
                </button>
                <button class="action-btn" title="Editar">
                    <i class="fas fa-pencil-alt"></i>
                </button>
            </div>
        </div>

        <div class="doctor-row">
            <div class="doctor-info">
                <i class="fas fa-user doctor-icon"></i>
                <span class="doctor-name">Dr. Laura Rodriguez</span>
            </div>
            <div class="doctor-actions">
                <button class="action-btn" title="Ver">
                    <i class="fas fa-eye"></i>
                </button>
                <button class="action-btn" title="Eliminar">
                    <i class="fas fa-trash"></i>
                </button>
                <button class="action-btn" title="Editar">
                    <i class="fas fa-pencil-alt"></i>
                </button>
            </div>
        </div>

        <div class="doctor-row">
            <div class="doctor-info">
                <i class="fas fa-user doctor-icon"></i>
                <span class="doctor-name">Dr. Pablo Martínez</span>
            </div>
            <div class="doctor-actions">
                <button class="action-btn" title="Ver">
                    <i class="fas fa-eye"></i>
                </button>
                <button class="action-btn" title="Eliminar">
                    <i class="fas fa-trash"></i>
                </button>
                <button class="action-btn" title="Editar">
                    <i class="fas fa-pencil-alt"></i>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="{{ asset('js/admin.js') }}"></script>
@endsection
