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
<style>
    .profile-edit-container {
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        padding: 20px;
        margin-bottom: 20px;
    }
    
    .profile-edit-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid #eaeaea;
    }
    
    .profile-edit-title {
        font-size: 18px;
        font-weight: 600;
        color: #333;
    }
    
    .profile-edit-form {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    .form-label {
        display: block;
        font-size: 14px;
        font-weight: 500;
        color: #333;
        margin-bottom: 8px;
    }
    
    .form-control {
        width: 100%;
        padding: 10px 15px;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        font-size: 14px;
        color: #333;
        transition: border-color 0.2s;
    }
    
    .form-control:focus {
        border-color: #6c5dd3;
        outline: none;
    }
    
    .form-actions {
        grid-column: span 2;
        display: flex;
        justify-content: flex-end;
        gap: 15px;
        margin-top: 10px;
    }
    
    .btn {
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .btn-cancel {
        background-color: #f5f6fa;
        border: 1px solid #e0e0e0;
        color: #666;
    }
    
    .btn-cancel:hover {
        background-color: #eef0f7;
    }
    
    .btn-save {
        background-color: #6c5dd3;
        border: none;
        color: white;
    }
    
    .btn-save:hover {
        background-color: #5a4cbe;
    }
    
    .profile-avatar-section {
        grid-column: span 2;
        display: flex;
        align-items: center;
        gap: 20px;
        margin-bottom: 20px;
    }
    
    .profile-avatar-container {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        overflow: hidden;
        position: relative;
    }
    
    .profile-avatar-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .profile-avatar-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.2s;
        cursor: pointer;
    }
    
    .profile-avatar-container:hover .profile-avatar-overlay {
        opacity: 1;
    }
    
    .profile-avatar-overlay i {
        color: white;
        font-size: 24px;
    }
    
    .profile-avatar-text {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }
    
    .profile-avatar-title {
        font-size: 16px;
        font-weight: 600;
        color: #333;
    }
    
    .profile-avatar-description {
        font-size: 14px;
        color: #666;
    }
    
    .profile-avatar-buttons {
        display: flex;
        gap: 10px;
        margin-top: 10px;
    }
    
    .avatar-btn {
        padding: 8px 15px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .avatar-btn-upload {
        background-color: #f5f6fa;
        border: 1px solid #e0e0e0;
        color: #333;
    }
    
    .avatar-btn-upload:hover {
        background-color: #eef0f7;
    }
    
    .avatar-btn-remove {
        background-color: #fee7e7;
        border: 1px solid #ffcaca;
        color: #ff4d4d;
    }
    
    .avatar-btn-remove:hover {
        background-color: #ffd5d5;
    }
</style>
@endsection

@section('content')
<div class="profile-edit-container">
    <div class="profile-edit-header">
        <h2 class="profile-edit-title">Editar Información del Perfil</h2>
    </div>
    
    <form class="profile-edit-form">
        <div class="profile-avatar-section">
            <div class="profile-avatar-container">
                <img src="{{ asset('assets/profile.png') }}" alt="Foto de perfil">
                <div class="profile-avatar-overlay">
                    <i class="fas fa-camera"></i>
                </div>
            </div>
            <div class="profile-avatar-text">
                <div class="profile-avatar-title">Foto de Perfil</div>
                <div class="profile-avatar-description">Esta foto será visible para otros usuarios</div>
                <div class="profile-avatar-buttons">
                    <button type="button" class="avatar-btn avatar-btn-upload">Subir nueva foto</button>
                    <button type="button" class="avatar-btn avatar-btn-remove">Eliminar</button>
                </div>
            </div>
        </div>
        
        <div class="form-group">
            <label class="form-label">Nombre</label>
            <input type="text" class="form-control" value="Nicholas" placeholder="Nombre">
        </div>
        
        <div class="form-group">
            <label class="form-label">Apellido</label>
            <input type="text" class="form-control" value="Swatz" placeholder="Apellido">
        </div>
        
        <div class="form-group">
            <label class="form-label">Correo Electrónico</label>
            <input type="email" class="form-control" value="nicholas.swatz@example.com" placeholder="Correo electrónico">
        </div>
        
        <div class="form-group">
            <label class="form-label">Teléfono</label>
            <input type="tel" class="form-control" value="(629) 555-0123" placeholder="Teléfono">
        </div>
        
        <div class="form-group">
            <label class="form-label">Fecha de Nacimiento</label>
            <input type="date" class="form-control" value="1988-09-20">
        </div>
        
        <div class="form-group">
            <label class="form-label">Cargo</label>
            <input type="text" class="form-control" value="Gerente de Proyecto" placeholder="Cargo">
        </div>
        
        <div class="form-group">
            <label class="form-label">Departamento</label>
            <select class="form-control">
                <option value="creative">Asociado Creativo</option>
                <option value="marketing">Marketing</option>
                <option value="sales">Ventas</option>
                <option value="development">Desarrollo</option>
            </select>
        </div>
        
        <div class="form-group">
            <label class="form-label">División</label>
            <select class="form-control">
                <option value="project-management">Gestión de Proyectos</option>
                <option value="operations">Operaciones</option>
                <option value="finance">Finanzas</option>
                <option value="hr">Recursos Humanos</option>
            </select>
        </div>
        
        <div class="form-actions">
            <button type="button" class="btn btn-cancel" onclick="window.location.href='{{ url('admin/perfil') }}'">Cancelar</button>
            <button type="submit" class="btn btn-save">Guardar Cambios</button>
        </div>
    </form>
</div>
@endsection
