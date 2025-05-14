@extends('layouts.admin')

@section('title', 'Editar Perfil')

@section('page-title', 'Editar Perfil')

@section('breadcrumb')
    <span class="breadcrumb-separator">/</span>
    <span><a href="{{ url('admin/perfil') }}">Perfil</a></span>
    <span class="breadcrumb-separator">/</span>
    <span>Editar</span>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/profile-edit.css') }}">
@endsection

@section('content')
    <div class="profile-edit-container">
        <div class="profile-edit-header">
            <h2 class="profile-edit-title">Información Personal</h2>
        </div>
        <form id="profile-edit-form" action="{{ url('admin/perfil/actualizar') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="profile-photo-container">
                <img src="{{ Auth::user()->photo_path ? asset(Auth::user()->photo_path) : asset('img/doctor-avatar.jpg') }}" 
                     alt="Foto de perfil" 
                     class="profile-photo">
                <div class="photo-actions">
                    <input type="file" 
                           id="photo-upload" 
                           name="photo" 
                           accept="image/*" 
                           style="display: none;" 
                           aria-label="Subir foto de perfil">
                    <button type="button" class="photo-action-button upload-button" title="Subir foto">
                        <i class="fas fa-upload"></i>
                        <span>Subir Foto</span>
                    </button>
                    <button type="button" class="photo-action-button remove-button" title="Eliminar foto">
                        <i class="fas fa-trash"></i>
                        <span>Eliminar Foto</span>
                    </button>
                </div>
            </div>
            <div class="profile-edit-form">
                <div class="form-group">
                    <label for="name" class="form-label">Nombre Completo</label>
                    <input type="text" id="name" name="name" class="form-control" value="Dr. Juan Pérez" required>
                </div>
                <div class="form-group">
                    <label for="specialty" class="form-label">Especialidad</label>
                    <input type="text" id="specialty" name="specialty" class="form-control" value="Médico General">
                </div>
                <div class="form-group">
                    <label for="email" class="form-label">Correo Electrónico</label>
                    <input type="email" id="email" name="email" class="form-control" value="juan.perez@clinica.com" required>
                </div>
                <div class="form-group">
                    <label for="phone" class="form-label">Teléfono</label>
                    <input type="tel" id="phone" name="phone" class="form-control" value="5551234567" required>
                </div>
                <div class="form-group-full">
                    <label for="address" class="form-label">Dirección</label>
                    <input type="text" id="address" name="address" class="form-control" value="Av. Principal #123, Ciudad">
                </div>
                <div class="form-group">
                    <label for="birthday" class="form-label">Fecha de Nacimiento</label>
                    <input type="date" id="birthday" name="birthday" class="form-control" value="1985-06-15">
                </div>
                <div class="form-group">
                    <label for="gender" class="form-label">Género</label>
                    <select id="gender" name="gender" class="form-control">
                        <option value="male" selected>Masculino</option>
                        <option value="female">Femenino</option>
                        <option value="other">Otro</option>
                    </select>
                </div>
                <div class="form-group-full">
                    <label for="bio" class="form-label">Biografía</label>
                    <textarea id="bio" name="bio" class="form-control" rows="4">Médico general con más de 10 años de experiencia en atención primaria. Especializado en medicina preventiva y atención integral de pacientes.</textarea>
                </div>
                <div class="form-actions">
                    <div class="form-button cancel-button">Cancelar</div>
                    <button type="submit" class="form-button save-button">Guardar Cambios</button>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/admin/profile-edit.js') }}"></script>
@endsection
