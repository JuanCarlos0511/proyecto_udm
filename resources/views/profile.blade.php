@extends('layouts.app')

@section('title', 'Perfil de Usuario')

@section('extra-css')
<link rel="stylesheet" href="{{ asset('css/pages/profile.css') }}">
<style>
    /* Estilos para la superposición de la foto de perfil */
    .profile-avatar {
        position: relative;
        cursor: pointer;
        width: 100px;
        height: 100px;
        border-radius: 50%;
        overflow: hidden;
    }
    
    .profile-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .avatar-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        color: white;
        opacity: 0;
        transition: opacity 0.3s ease;
        border-radius: 50%;
    }
    
    .profile-avatar:hover .avatar-overlay {
        opacity: 1;
    }
    
    .avatar-overlay i {
        font-size: 24px;
        margin-bottom: 5px;
    }
    
    .avatar-overlay span {
        font-size: 12px;
        text-align: center;
    }
    
    /* Estilos para el modal de foto */
    .photo-modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.4);
    }
    
    .photo-modal-content {
        background-color: #fefefe;
        margin: 10% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
        max-width: 500px;
        border-radius: 8px;
        position: relative;
    }
    
    .close-modal {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }
    
    .close-modal:hover,
    .close-modal:focus {
        color: black;
        text-decoration: none;
    }
    
    /* Estilos para las opciones de foto */
    .photo-options {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 20px;
        margin: 20px 0;
    }
    
    .photo-option {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 15px;
        border: 1px solid #ddd;
        border-radius: 8px;
        cursor: pointer;
        width: calc(33% - 10px);
        min-width: 120px;
        transition: all 0.3s ease;
    }

    .delete-option {
        border-color: #ff4444;
    }

    .delete-photo-btn {
        background: none;
        border: none;
        color: #ff4444;
        cursor: pointer;
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
        padding: 0;
    }

    .delete-photo-btn:hover {
        color: #cc0000;
    }
    
    .photo-option:hover {
        background-color: #f5f5f5;
    }
    
    .photo-option i {
        font-size: 24px;
        margin-bottom: 10px;
    }
    
    .upload-option {
        position: relative;
        overflow: hidden;
    }
    
    .file-input {
        position: absolute;
        font-size: 100px;
        opacity: 0;
        right: 0;
        top: 0;
        width: 100%;
        height: 100%;
        cursor: pointer;
    }
    
    /* Estilos para la cámara y vista previa */
    .camera-container {
        text-align: center;
        margin: 20px 0;
    }
    
    .camera-buttons,
    .preview-buttons {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin-top: 15px;
    }
    
    #photo-preview {
        text-align: center;
    }
    
    #preview-image {
        border-radius: 8px;
        border: 1px solid #ddd;
    }
</style>
@endsection

@section('content')
<div class="profile-container">
    <h1 class="profile-title">Mi Perfil</h1>
    
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <div class="profile-card">
        <div class="profile-header">
            <div class="profile-avatar" id="profile-avatar-container">
                <img src="{{ Auth::user()->photo_path ? asset(Auth::user()->photo_path) : asset('assets/profile.png') }}" alt="{{ Auth::user()->name }}" id="profile-photo">
                @if($user->role === 'paciente')
                <div class="avatar-overlay">
                    <i class="fas fa-camera"></i>
                    <span>Cambiar foto</span>
                </div>
                @endif
            </div>
            <div class="profile-info">
                <h2>{{ $user->name }}</h2>
                <p>{{ $user->email }}</p>
                @if(isset($hasOptionalFieldsMissing) && $hasOptionalFieldsMissing)
                <div class="profile-suggestion">
                    <i class="fas fa-info-circle"></i>
                    <span>Completa tu perfil para mejorar tu experiencia. Faltan {{ count($missingFields) }} campos opcionales.</span>
                </div>
                @endif
            </div>
        </div>

        <form action="{{ route('profile.update') }}" method="POST" class="profile-form" id="profile-form" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label for="name">Nombre completo</label>
                <input type="text" id="name" name="name" value="{{ $user->name }}" required>
                @error('name')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
      
            <div class="form-group">
                <label for="age">Edad</label>
                <input type="number" id="age" name="age" value="{{ $user->age == 0 ? '' : $user->age }}" min="1" max="120" required placeholder="Ej. 25">
                @if($user->age == 0)
                    <small class="form-text text-warning"><i class="fas fa-exclamation-triangle"></i> Por favor, ingresa tu edad real</small>
                @endif
                @error('age')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="phoneNumber">Número de teléfono</label>
                <input type="tel" id="phoneNumber" name="phoneNumber" value="{{ $user->phoneNumber == '0000000000' ? '' : $user->phoneNumber }}" pattern="[0-9]{10}" maxlength="10" required placeholder="Ej. 8332144067">
                @if($user->phoneNumber == '0000000000')
                    <small class="form-text text-warning"><i class="fas fa-exclamation-triangle"></i> Por favor, ingresa tu número telefónico real</small>
                @else
                    <small class="form-text">Ingrese exactamente 10 dígitos numéricos</small>
                @endif
                @error('phoneNumber')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="adress">Dirección
                    @if(isset($missingFields) && array_key_exists('adress', $missingFields))
                    <span class="profile-optional-missing" title="Campo opcional incompleto">
                        <i class="fas fa-exclamation-circle"></i>
                    </span>
                    @endif
                </label>
                <input type="text" id="adress" name="adress" value="{{ $user->adress }}" placeholder="Ej. Pedro José Méndez, 89240 Tampico, Tamaulipas">
                <small class="form-text">Este campo es necesario si requiere de citas a domicilio</small>
                @error('adress')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            
            <h3 class="section-title">Contacto de Emergencia</h3>
            
            <div class="form-group">
                <label for="emergency_contact_name">Nombre del contacto de emergencia
                    @if(isset($missingFields) && array_key_exists('emergency_contact_name', $missingFields))
                    <span class="profile-optional-missing" title="Campo opcional incompleto">
                        <i class="fas fa-exclamation-circle"></i>
                    </span>
                    @endif
                </label>
                <input type="text" id="emergency_contact_name" name="emergency_contact_name" value="{{ $user->emergency_contact_name }}" placeholder="Ej. Juan Carlos Martinez Rodriguez">
                <small class="form-text">Este campo es opcional</small>
                @error('emergency_contact_name')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="emergency_contact_phone">Teléfono del contacto de emergencia
                    @if(isset($missingFields) && array_key_exists('emergency_contact_phone', $missingFields))
                    <span class="profile-optional-missing" title="Campo opcional incompleto">
                        <i class="fas fa-exclamation-circle"></i>
                    </span>
                    @endif
                </label>
                <input type="tel" id="emergency_contact_phone" name="emergency_contact_phone" value="{{ $user->emergency_contact_phone }}" pattern="[0-9]{10}" maxlength="10" placeholder="Ej. 8332144067">
                <small class="form-text">Ingrese exactamente 10 dígitos numéricos (opcional)</small>
                @error('emergency_contact_phone')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="emergency_contact_relationship">Relación con el contacto
                    @if(isset($missingFields) && array_key_exists('emergency_contact_relationship', $missingFields))
                    <span class="profile-optional-missing" title="Campo opcional incompleto">
                        <i class="fas fa-exclamation-circle"></i>
                    </span>
                    @endif
                </label>
                <select id="emergency_contact_relationship" name="emergency_contact_relationship" class="form-control">
                    <option value="" {{ $user->emergency_contact_relationship == '' ? 'selected' : '' }}>Seleccionar...</option>
                    <option value="Familiar" {{ $user->emergency_contact_relationship == 'Familiar' ? 'selected' : '' }}>Familiar</option>
                    <option value="Cónyuge" {{ $user->emergency_contact_relationship == 'Cónyuge' ? 'selected' : '' }}>Cónyuge</option>
                    <option value="Amigo/a" {{ $user->emergency_contact_relationship == 'Amigo/a' ? 'selected' : '' }}>Amigo/a</option>
                    <option value="Vecino/a" {{ $user->emergency_contact_relationship == 'Vecino/a' ? 'selected' : '' }}>Vecino/a</option>
                    <option value="Otro" {{ $user->emergency_contact_relationship == 'Otro' ? 'selected' : '' }}>Otro</option>
                </select>
                <small class="form-text">Este campo es opcional</small>
                @error('emergency_contact_relationship')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            
            <!-- El campo de foto se ha movido al modal -->
            
            <div class="form-actions">
                <button type="button" class="btn btn-secondary" onclick="window.location.href='{{ url('/') }}'">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar cambios</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal para cambiar la foto de perfil -->
@if($user->role === 'paciente')
<div id="photo-modal" class="photo-modal">
    <div class="photo-modal-content">
        <span class="close-modal">&times;</span>
        <h3>Cambiar foto de perfil</h3>
        
        <div class="photo-options">
            <div class="photo-option upload-option">
                <i class="fas fa-upload"></i>
                <span>Subir foto</span>
                <input type="file" id="photo" name="photo" accept="image/*" class="file-input" form="profile-form">
            </div>
            
            <div class="photo-option camera-option">
                <i class="fas fa-camera"></i>
                <span>Tomar foto</span>
            </div>

            @if(Auth::user()->photo_path)
            <div class="photo-option delete-option">
                <form action="{{ route('profile.delete-photo') }}" method="POST" style="width: 100%;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="delete-photo-btn" onclick="return confirm('¿Estás seguro de que deseas eliminar tu foto de perfil?')">
                        <i class="fas fa-trash"></i>
                        <span>Eliminar foto</span>
                    </button>
                </form>
            </div>
            @endif
        </div>
        
        <div class="camera-container" style="display:none;">
            <video id="camera-preview" style="width:100%; max-width:300px;"></video>
            <canvas id="camera-canvas" style="display:none;"></canvas>
            <div class="camera-buttons">
                <button type="button" id="capture-button" class="btn btn-primary">Capturar</button>
                <button type="button" id="cancel-camera-button" class="btn btn-secondary">Cancelar</button>
            </div>
        </div>
        
        <div id="photo-preview" style="margin-top: 20px; display: none;">
            <h4>Vista previa</h4>
            <img id="preview-image" src="#" alt="Vista previa" style="max-width: 200px; max-height: 200px;">
            <div class="preview-buttons">
                <button type="button" id="save-photo-button" class="btn btn-primary">Guardar</button>
                <button type="button" id="cancel-photo-button" class="btn btn-secondary">Cancelar</button>
            </div>
            @if(Auth::user()->photo_path)
            <div class="delete-photo-section" style="margin-top: 20px; text-align: center;">
                <form action="{{ route('profile.delete-photo') }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('¿Estás seguro de que deseas eliminar tu foto de perfil?')">
                        <i class="fas fa-trash"></i> Eliminar foto de perfil
                    </button>
                </form>
            </div>
            @endif
        </div>
        
        <input type="hidden" id="camera-input" name="camera_photo" form="profile-form">
    </div>
</div>
@endif

@endsection

@section('scripts')
<script src="{{ asset('js/photo-upload.js') }}"></script>
@endsection
