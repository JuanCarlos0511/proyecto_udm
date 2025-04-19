@extends('layouts.auth')

@section('title', 'Crear Cuenta')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/register.css') }}">
@endsection

@section('content')
<div class="register-container">
    <div class="register-left-panel">
        <div class="register-logo">
            <a href="{{ url('/') }}">Clínica Miel</a>
        </div>
        <div class="register-welcome">
            <h1>Comienza tu camino</h1>
            <h2>Hacia una mejor salud</h2>
            <p>Únete a nosotros para recibir atención médica de calidad, en clínica o a domicilio.</p>
        </div>
        <div class="register-testimonial">
            <p class="testimonial-text">"No te preocupes por los contratiempos; solo necesitas acertar una vez."</p>
            <p class="testimonial-author">- Dr. Martínez</p>
        </div>
    </div>
    
    <div class="register-right-panel">
        <div class="register-form-container">
            <h2>Crear tu cuenta</h2>
            
            <div class="login-register-link">
                <p>¿Ya tienes una cuenta? <a href="{{ url('/login') }}">Iniciar Sesión</a></p>
            </div>
            
            <div class="social-login-buttons fade-in-element">
                <a href="{{ route('auth.google') }}" class="social-login-btn google-btn">
                    <img src="{{ asset('assets/google-icon.png') }}" alt="Google">
                    Continuar con Google
                </a>
                
                <button class="social-login-btn apple-btn">
                    <img src="{{ asset('assets/apple-icon.png') }}" alt="Apple">
                    Continuar con Apple
                </button>
            </div>
            
            <div class="login-divider fade-in-element">
                <span>O</span>
            </div>
            
            <form class="register-form" action="{{ url('/register') }}" method="POST">
                @csrf
                
                <div class="form-group fade-in-element" style="animation-delay: 0.1s;">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                
                <div class="form-group fade-in-element" style="animation-delay: 0.2s;">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <div class="terms-privacy fade-in-element" style="animation-delay: 0.3s;">
                    <p>Al registrarte, aceptas nuestros <a href="{{ url('/terms') }}">Términos y Condiciones</a> y <a href="{{ url('/privacy') }}">Política de Privacidad</a></p>
                </div>
                
                <button type="submit" class="register-btn fade-in-element" style="animation-delay: 0.4s;">Registrarse</button>
            </form>
        </div>
    </div>
</div>
@endsection
