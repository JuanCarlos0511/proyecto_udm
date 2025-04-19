@extends('layouts.auth')

@section('title', 'Iniciar Sesión')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/login.css') }}">
@endsection

@section('content')
<div class="login-container">
    <div class="login-left-panel">
        <div class="login-logo">
            <a href="{{ url('/') }}">Clínica Miel</a>
        </div>
        <div class="login-welcome">
            <h1>¡Bienvenido de nuevo!</h1>
            <h2>Cuidamos tu salud</h2>
            <p>Accede a tu cuenta para gestionar tus citas médicas y seguimiento de tratamientos.</p>
        </div>
        <div class="login-testimonial">
            <p class="testimonial-text">"Si brindas una gran experiencia, los pacientes se lo cuentan entre ellos. El boca a boca es muy poderoso."</p>
            <p class="testimonial-author">- Dr. García</p>
        </div>
    </div>
    
    <div class="login-right-panel">
        <div class="login-form-container">
            <h2>Iniciar sesión en tu cuenta</h2>
            
            <div class="login-register-link">
                <p>¿No tienes una cuenta? <a href="{{ url('/register') }}">Regístrate</a></p>
            </div>
            
            <div class="social-login-buttons">
                <a href="{{ route('auth.google') }}" class="social-login-btn google-btn">
                    <img src="{{ asset('assets/google-icon.png') }}" alt="Google">
                    Continuar con Google
                </a>
                
                <button class="social-login-btn apple-btn">
                    <img src="{{ asset('assets/apple-icon.png') }}" alt="Apple">
                    Continuar con Apple
                </button>
            </div>
            
            <div class="login-divider">
                <span>O</span>
            </div>
            
            <form class="login-form" action="{{ url('/login') }}" method="POST">
                @csrf
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <div class="forgot-password">
                    <a href="{{ url('/forgot-password') }}">¿Olvidaste tu contraseña?</a>
                </div>
                
                <button type="submit" class="login-btn">Iniciar Sesión</button>
            </form>
        </div>
    </div>
</div>
@endsection
