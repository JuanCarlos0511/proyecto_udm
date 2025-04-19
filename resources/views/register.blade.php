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
        <!-- Indicadores de etapa -->
        <div class="stage-indicators">
            <div class="stage-dot active" data-stage="1"></div>
            <div class="stage-dot" data-stage="2"></div>
            <div class="stage-dot" data-stage="3"></div>
        </div>
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
            
            <form id="registerForm" class="register-form" action="{{ url('/register') }}" method="POST">
                @csrf
                
                <!-- Etapa 1: Información básica -->
                <div id="stage1" class="register-stage active">
                    <h3 class="stage-title">Información básica</h3>
                    
                    <div class="form-group fade-in-element" style="animation-delay: 0.1s;">
                        <label for="name">Nombre</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    
                    <div class="form-group fade-in-element" style="animation-delay: 0.2s;">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    
                    <div class="form-group fade-in-element" style="animation-delay: 0.3s;">
                        <label for="password">Contraseña</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    
                    <div class="stage-buttons">
                        <button type="button" class="next-stage-btn" data-next="2">Siguiente</button>
                    </div>
                </div>
                
                <!-- Etapa 2: Información personal obligatoria -->
                <div id="stage2" class="register-stage">
                    <h3 class="stage-title">Información personal</h3>
                    
                    <div class="form-group">
                        <label for="age">Edad</label>
                        <input type="number" id="age" name="age" min="0" max="120" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="phoneNumber">Número de teléfono</label>
                        <input type="tel" id="phoneNumber" name="phoneNumber" pattern="[0-9]{10}" required>
                        <small>Ingrese exactamente 10 dígitos numéricos</small>
                    </div>
                    
                    <div class="stage-buttons">
                        <button type="button" class="prev-stage-btn" data-prev="1">Anterior</button>
                        <button type="button" class="next-stage-btn" data-next="3">Siguiente</button>
                    </div>
                </div>
                
                <!-- Etapa 3: Dirección (opcional) -->
                <div id="stage3" class="register-stage">
                    <h3 class="stage-title">Dirección (opcional)</h3>
                    
                    <div class="form-group">
                        <label for="colonia">Colonia</label>
                        <input type="text" id="colonia" name="colonia">
                    </div>
                    
                    <div class="form-group">
                        <label for="calle">Calle</label>
                        <input type="text" id="calle" name="calle">
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group half">
                            <label for="numExterior">Número exterior</label>
                            <input type="text" id="numExterior" name="numExterior">
                        </div>
                        
                        <div class="form-group half">
                            <label for="numInterior">Número interior</label>
                            <input type="text" id="numInterior" name="numInterior">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="codigoPostal">Código Postal</label>
                        <input type="text" id="codigoPostal" name="codigoPostal" pattern="[0-9]{5}">
                        <small>5 dígitos numéricos</small>
                    </div>
                    
                    <div class="terms-privacy">
                        <p>Al registrarte, aceptas nuestros <a href="{{ url('/terms') }}">Términos y Condiciones</a> y <a href="{{ url('/privacy') }}">Política de Privacidad</a></p>
                    </div>
                    
                    <div class="stage-buttons">
                        <button type="button" class="prev-stage-btn" data-prev="2">Anterior</button>
                        <button type="submit" class="register-btn">Registrarse</button>
                        <button type="button" class="skip-btn" id="skipToVerify">Omitir</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Referencias a elementos
        const stages = document.querySelectorAll('.register-stage');
        const dots = document.querySelectorAll('.stage-dot');
        const nextButtons = document.querySelectorAll('.next-stage-btn');
        const prevButtons = document.querySelectorAll('.prev-stage-btn');
        const skipButton = document.getElementById('skipToVerify');
        const registerForm = document.getElementById('registerForm');
        const leftPanel = document.querySelector('.register-left-panel');
        const rightPanel = document.querySelector('.register-right-panel');
        
        // Función para cambiar de etapa
        function goToStage(currentStage, targetStage) {
            // Actualizar los puntos indicadores
            dots.forEach(dot => dot.classList.remove('active'));
            dots[targetStage-1].classList.add('active');
            
            // Ocultar la etapa actual y mostrar la etapa objetivo con animación
            stages.forEach(stage => stage.classList.remove('active'));
            stages[targetStage-1].classList.add('active');
        }
        
        // Event listeners para los botones de siguiente
        nextButtons.forEach(button => {
            button.addEventListener('click', function() {
                const nextStage = parseInt(this.getAttribute('data-next'));
                const currentStage = parseInt(this.closest('.register-stage').id.replace('stage', ''));
                goToStage(currentStage, nextStage);
            });
        });
        
        // Event listeners para los botones de anterior
        prevButtons.forEach(button => {
            button.addEventListener('click', function() {
                const prevStage = parseInt(this.getAttribute('data-prev'));
                const currentStage = parseInt(this.closest('.register-stage').id.replace('stage', ''));
                goToStage(currentStage, prevStage);
            });
        });
        
        // Event listeners para los puntos indicadores
        dots.forEach((dot, index) => {
            dot.addEventListener('click', function() {
                const targetStage = parseInt(this.getAttribute('data-stage'));
                const currentStage = document.querySelector('.register-stage.active').id.replace('stage', '');
                goToStage(currentStage, targetStage);
            });
        });
        
        // Manejar el envío del formulario
        registerForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Animar el desplazamiento del panel izquierdo
            leftPanel.classList.add('slide-left');
            rightPanel.classList.add('expand-right');
            
            // Esperar a que termine la animación y enviar el formulario
            setTimeout(() => {
                this.submit();
            }, 800);
        });
        
        // Manejar el botón de omitir
        if (skipButton) {
            skipButton.addEventListener('click', function() {
                // Animar el desplazamiento del panel izquierdo
                leftPanel.classList.add('slide-left');
                rightPanel.classList.add('expand-right');
                
                // Esperar a que termine la animación y enviar el formulario
                setTimeout(() => {
                    registerForm.submit();
                }, 800);
            });
        }
    });
</script>
@endsection
