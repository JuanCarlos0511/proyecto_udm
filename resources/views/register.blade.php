@extends('layouts.auth')

@section('title', 'Crear Cuenta')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/register.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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
        <div class="stage-indicators-wrapper">
            <div class="stage-indicators">
                <div class="stage-dot active" data-stage="1">
                    <span class="stage-number">1</span>
                    <span class="stage-label">Básica</span>
                </div>
                <div class="stage-dot" data-stage="2">
                    <span class="stage-number">2</span>
                    <span class="stage-label">Personal</span>
                </div>
                <div class="stage-dot" data-stage="3">
                    <span class="stage-number">3</span>
                    <span class="stage-label">Dirección</span>
                </div>
            </div>
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
            </div>
            
            <div class="login-divider fade-in-element">
                <span>O</span>
            </div>
            
            <form id="registerForm" class="register-form" action="{{ url('/register') }}" method="POST">
                @csrf
                
                <!-- Etapa 1: Información básica -->
                <div id="stage1" class="register-stage active">
                    <h3 class="stage-title">Información básica</h3>
                    
                    <!-- Mostrar errores generales -->
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    
                    <div class="form-group fade-in-element" style="animation-delay: 0.1s;">
                        <label for="name">Nombre completo</label>
                        <input type="text" id="name" name="name" placeholder="Ingresa tu nombre completo" required value="{{ old('name') }}">
                        <small class="form-hint">Tal como aparece en tu identificación oficial</small>
                        @error('name')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group fade-in-element" style="animation-delay: 0.2s;">
                        <label for="email">Correo electrónico</label>
                        <input type="email" id="email" name="email" placeholder="ejemplo@correo.com" required value="{{ old('email') }}">
                        <small class="form-hint">Usarás este correo para iniciar sesión</small>
                        @error('email')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group fade-in-element" style="animation-delay: 0.3s;">
                        <label for="password">Contraseña</label>
                        <input type="password" id="password" name="password" placeholder="Mínimo 8 caracteres" required>
                        <small class="form-hint">Debe tener al menos 8 caracteres</small>
                        @error('password')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="stage-buttons">
                        <button type="button" class="next-stage-btn" data-next="2">Siguiente <i class="fas fa-arrow-right"></i></button>
                    </div>
                </div>
                
                <!-- Etapa 2: Información personal obligatoria -->
                <div id="stage2" class="register-stage">
                    <h3 class="stage-title">Información personal</h3>
                    
                    <div class="form-group">
                        <label for="age">Edad</label>
                        <input type="number" id="age" name="age" min="0" max="120" placeholder="Ej: 35" required>
                        <small class="form-hint">Tu edad actual en años</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="phoneNumber">Número de teléfono</label>
                        <div class="input-with-icon">
                            <span class="input-icon">+52</span>
                            <input type="tel" id="phoneNumber" name="phoneNumber" pattern="[0-9]{10}" placeholder="10 dígitos" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '');" required>
                        </div>
                        <small class="form-hint">Ingresa exactamente 10 dígitos numéricos sin espacios ni guiones</small>
                    </div>
                    
                    <div class="stage-buttons">
                        <button type="button" class="prev-stage-btn" data-prev="1"><i class="fas fa-arrow-left"></i> Anterior</button>
                        <button type="button" class="next-stage-btn" data-next="3">Siguiente <i class="fas fa-arrow-right"></i></button>
                    </div>
                </div>
                
                <!-- Etapa 3: Dirección (opcional) -->
                <div id="stage3" class="register-stage">
                    <h3 class="stage-title">Dirección (opcional)</h3>
                    <p class="stage-description">Esta información nos ayuda a brindarte un mejor servicio. Puedes completarla más tarde desde tu perfil.</p>
                    
                    <div class="form-group">
                        <label for="colonia">Colonia</label>
                        <input type="text" id="colonia" name="colonia" placeholder="Ej: Centro">
                    </div>
                    
                    <div class="form-group">
                        <label for="calle">Calle</label>
                        <input type="text" id="calle" name="calle" placeholder="Ej: Av. Reforma">
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group half">
                            <label for="numExterior">Número exterior</label>
                            <input type="text" id="numExterior" name="numExterior" placeholder="Ej: 123">
                        </div>
                        
                        <div class="form-group half">
                            <label for="numInterior">Número interior</label>
                            <input type="text" id="numInterior" name="numInterior" placeholder="Ej: 4B">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="codigoPostal">Código Postal</label>
                        <input type="number" id="codigoPostal" name="codigoPostal" pattern="[0-9]{5}" placeholder="5 dígitos" min="10000" max="99999" oninput="javascript: if (this.value.length > 5) this.value = this.value.slice(0, 5);">
                        <small class="form-hint">Ingresa exactamente 5 dígitos numéricos</small>
                    </div>
                    
                    <div class="terms-privacy">
                        <div class="checkbox-container">
                            <input type="checkbox" id="terms" name="terms" required>
                            <label for="terms">Al registrarte, aceptas nuestros <a href="{{ url('/terms') }}">Términos y Condiciones</a> y <a href="{{ url('/privacy') }}">Política de Privacidad</a></label>
                        </div>
                    </div>
                    
                    <div class="stage-buttons">
                        <button type="button" class="prev-stage-btn" data-prev="2"><i class="fas fa-arrow-left"></i> Anterior</button>
                        <button type="submit" class="register-btn">Finalizar Registro</button>
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
        
        // Función para mostrar mensajes de error
        function showError(input, message) {
            const formGroup = input.closest('.form-group');
            let errorElement = formGroup.querySelector('.error-message');
            
            if (!errorElement) {
                errorElement = document.createElement('span');
                errorElement.className = 'error-message';
                formGroup.appendChild(errorElement);
            }
            
            errorElement.textContent = message;
            input.classList.add('input-error');
        }
        
        // Función para eliminar mensajes de error
        function clearError(input) {
            const formGroup = input.closest('.form-group');
            const errorElement = formGroup.querySelector('.error-message');
            
            if (errorElement) {
                errorElement.textContent = '';
            }
            
            input.classList.remove('input-error');
        }
        
        // Función para validar campos
        function validateField(input) {
            let isValid = true;
            const value = input.value.trim();
            
            // Eliminar errores previos
            clearError(input);
            
            // Validar según el tipo de campo
            if (input.required && value === '') {
                showError(input, 'Este campo es obligatorio');
                isValid = false;
            } else if (input.id === 'email' && value !== '') {
                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailPattern.test(value)) {
                    showError(input, 'Ingrese un correo electrónico válido');
                    isValid = false;
                }
            } else if (input.id === 'password' && value !== '') {
                if (value.length < 8) {
                    showError(input, 'La contraseña debe tener al menos 8 caracteres');
                    isValid = false;
                }
            } else if (input.id === 'phoneNumber' && value !== '') {
                const phonePattern = /^[0-9]{10}$/;
                if (!phonePattern.test(value)) {
                    showError(input, 'Ingrese exactamente 10 dígitos numéricos');
                    isValid = false;
                }
            } else if (input.id === 'age' && value !== '') {
                const age = parseInt(value);
                if (isNaN(age) || age < 0 || age > 120) {
                    showError(input, 'Ingrese una edad válida entre 0 y 120');
                    isValid = false;
                }
            } else if (input.id === 'codigoPostal' && value !== '') {
                const cpPattern = /^[0-9]{5}$/;
                if (!cpPattern.test(value)) {
                    showError(input, 'Ingrese exactamente 5 dígitos numéricos');
                    isValid = false;
                }
            } else if (input.id === 'codigoPostal' && value === '' && input.closest('.register-stage').id === 'stage3') {
                // Si estamos en la etapa 3 y el código postal está vacío, no es un error
                // ya que la dirección es opcional, pero si tiene algún valor debe ser válido
            }
            
            return isValid;
        }
        
        // Validar todos los campos de una etapa
        function validateStage(stageElement) {
            const inputs = stageElement.querySelectorAll('input');
            let isValid = true;
            
            inputs.forEach(input => {
                // Solo validamos los campos requeridos o los que tienen algún valor
                if (input.required || input.value.trim() !== '') {
                    const fieldValid = validateField(input);
                    if (!fieldValid) isValid = false;
                }
            });
            
            return isValid;
        }
        
        // Función para cambiar de etapa con validación
        function goToStage(currentStage, targetStage) {
            // Si estamos avanzando, validamos la etapa actual
            if (targetStage > currentStage) {
                const currentStageElement = document.getElementById(`stage${currentStage}`);
                if (!validateStage(currentStageElement)) {
                    // Si la validación falla, no avanzamos
                    return false;
                }
            }
            
            // Actualizar los puntos indicadores
            dots.forEach(dot => dot.classList.remove('active'));
            dots[targetStage-1].classList.add('active');
            
            // Ocultar la etapa actual y mostrar la etapa objetivo con animación
            stages.forEach(stage => stage.classList.remove('active'));
            stages[targetStage-1].classList.add('active');
            
            return true;
        }
        
        // Agregar validación en tiempo real a los campos
        const allInputs = document.querySelectorAll('.register-form input');
        allInputs.forEach(input => {
            input.addEventListener('blur', function() {
                validateField(this);
            });
            
            input.addEventListener('input', function() {
                if (this.classList.contains('input-error')) {
                    validateField(this);
                }
            });
        });
        
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
        
        // Event listeners para los puntos indicadores (con validación)
        dots.forEach((dot, index) => {
            dot.addEventListener('click', function() {
                const targetStage = parseInt(this.getAttribute('data-stage'));
                const currentStage = parseInt(document.querySelector('.register-stage.active').id.replace('stage', ''));
                
                // Solo permitimos saltar a etapas anteriores o a la actual sin validación
                if (targetStage <= currentStage) {
                    goToStage(currentStage, targetStage);
                } else {
                    // Para avanzar, validamos todas las etapas anteriores
                    let canAdvance = true;
                    for (let i = 1; i < targetStage; i++) {
                        const stageElement = document.getElementById(`stage${i}`);
                        if (!validateStage(stageElement)) {
                            goToStage(currentStage, i);
                            canAdvance = false;
                            break;
                        }
                    }
                    
                    if (canAdvance) {
                        goToStage(currentStage, targetStage);
                    }
                }
            });
        });
        
        // Manejar el envío del formulario con validación
        registerForm.addEventListener('submit', function(e) {
            // Validar todos los campos obligatorios
            const requiredInputs = this.querySelectorAll('input[required]');
            let isValid = true;
            
            requiredInputs.forEach(input => {
                if (!validateField(input)) {
                    e.preventDefault(); // Solo prevenimos si hay errores
                    isValid = false;
                    
                    // Mostrar la etapa que contiene el campo con error
                    const stageId = input.closest('.register-stage').id;
                    const stageNum = parseInt(stageId.replace('stage', ''));
                    goToStage(0, stageNum); // 0 es un valor ficticio para no hacer validación
                }
            });
            
            if (!isValid) {
                return false;
            }
            
            // Si todo está bien, desactivamos el botón para evitar envíos duplicados
            const submitButton = this.querySelector('button[type="submit"]');
            if (submitButton) {
                submitButton.disabled = true;
                submitButton.textContent = 'Procesando...';
            }
            
            // Permitir que el formulario se envíe normalmente
            // El controlador se encargará de la redirección
            return true;
        });
        
        // Ya no necesitamos el manejo del botón de omitir, ya que lo hemos eliminado
    });
</script>
@endsection
