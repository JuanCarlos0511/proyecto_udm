@extends('layouts.app')

@section('title', 'Perfil de Usuario')

@section('extra-css')
<link rel="stylesheet" href="{{ asset('css/pages/profile.css') }}">
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
            <div class="profile-avatar">
                <img src="{{ asset('assets/profile.png') }}" alt="Foto de perfil">
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

        <form action="{{ route('profile.update') }}" method="POST" class="profile-form">
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
                <label for="email">Correo electrónico</label>
                <input type="email" id="email" value="{{ $user->email }}" disabled>
                <small>El correo electrónico no se puede modificar</small>
            </div>
            
            <div class="form-group">
                <label for="age">Edad</label>
                <input type="number" id="age" name="age" value="{{ $user->age }}" min="1" max="120" required>
                @error('age')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="phoneNumber">Número de teléfono</label>
                <input type="tel" id="phoneNumber" name="phoneNumber" value="{{ $user->phoneNumber }}" pattern="[0-9]{10}" maxlength="10" required>
                <small class="form-text">Ingrese exactamente 10 dígitos numéricos</small>
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
                <input type="text" id="adress" name="adress" value="{{ $user->adress }}">
                <small class="form-text">Este campo es opcional</small>
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
                <input type="text" id="emergency_contact_name" name="emergency_contact_name" value="{{ $user->emergency_contact_name }}">
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
                <input type="tel" id="emergency_contact_phone" name="emergency_contact_phone" value="{{ $user->emergency_contact_phone }}" pattern="[0-9]{10}" maxlength="10">
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
            
            <div class="form-actions">
                <button type="button" class="btn btn-secondary" onclick="window.location.href='{{ url('/') }}'">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar cambios</button>
            </div>
        </form>
    </div>
</div>
@endsection
