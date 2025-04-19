@extends('layouts.auth')

@section('title', 'Verificar Correo Electrónico')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/verify.css') }}">
@endsection

@section('content')
<div class="verify-container">
    <div class="verify-card">
        <div class="verify-header">
            <div class="verify-logo">
                <a href="{{ url('/') }}">Clínica Miel</a>
            </div>
            <h1>Verificación de Correo</h1>
        </div>

        @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
        @endif

        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

        <div class="verify-content">
            <p class="verify-info">
                Hemos enviado un código de verificación a <strong>{{ session('registration_data')['email'] }}</strong>
            </p>
            
            <form class="verify-form" action="{{ route('verification.verify') }}" method="POST">
                @csrf
                
                <div class="code-input-container">
                    <label for="verification_code">Ingresa el código de 6 dígitos</label>
                    <input type="text" id="verification_code" name="verification_code" maxlength="6" required 
                           class="code-input" placeholder="000000" autocomplete="off">
                    @error('verification_code')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                
                <button type="submit" class="verify-btn">Verificar</button>
            </form>
            
            <div class="verify-options">
                <p>¿No recibiste el código? 
                    <a href="{{ route('verification.resend') }}" class="resend-link">Reenviar código</a>
                </p>
                <p>
                    <a href="{{ route('register') }}" class="back-link">Volver al registro</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Enfocar automáticamente el campo de código
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('verification_code').focus();
    });
</script>
@endsection
