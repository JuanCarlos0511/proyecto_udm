@extends('layouts.app')

@section('title', 'Agenda tu Cita')

@section('content')
    <main>
        <section class="hero">
            <div class="hero-content">
                <h1>Agenda tu cita ahora</h1>
                <p>Agenda tu cita en línea y disfruta de la comodidad de ser atendido en la clínica o desde tu hogar. Nuestro equipo de profesionales está listo para ayudarte.</p>
            </div>
            
            <div class="appointment-options">
                <div class="appointment-card clinic">
                    <div class="icon-container">
                        <i class="fas fa-hospital-alt"></i>
                    </div>
                    <h3>Cita en Clínica</h3>
                    <p>Visítanos en nuestras instalaciones y recibe atención personalizada con todos nuestros servicios disponibles.</p>
                    <a href="{{ url('appointment-clinic') }}" class="appointment-btn">Agendar en Clínica</a>
                </div>
                
                <div class="appointment-card home">
                    <div class="icon-container">
                        <i class="fas fa-home"></i>
                    </div>
                    <h3>Cita a Domicilio</h3>
                    <p>Recibe atención profesional en la comodidad de tu hogar con nuestro servicio a domicilio.</p>
                    <a href="{{ url('appointment-home') }}" class="appointment-btn">Agendar a Domicilio</a>
                </div>
            </div>
        </section>

        <section class="specialties">
            <h2>Especialidades Médicas de Vanguardia</h2>
            <p>Nuestra misión es ofrecerte una atención médica integral y personalizada, con especialidades diseñadas para mejorar la calidad de vida.</p>
            
            <div class="services-grid">
                <div class="service-card">
                    <div class="service-image">
                        <img src="{{ asset('assets/electroterapia.jpg') }}" alt="Electroterapia">
                    </div>
                    <div class="service-icon">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <h3>Electroterapia: <span>Alivio Inmediato</span></h3>
                    <p>Utilizamos corrientes eléctricas terapéuticas para reducir el dolor, estimular los músculos y acelerar la recuperación de lesiones, proporcionando un alivio efectivo.</p>
                </div>

                <div class="service-card">
                    <div class="service-image">
                        <img src="{{ asset('assets/hidroterapia.jpg') }}" alt="Hidroterapia">
                    </div>
                    <div class="service-icon">
                        <i class="fas fa-water"></i>
                    </div>
                    <h3>Hidroterapia: <span>Sanación con Agua</span></h3>
                    <p>Aprovechamos las propiedades terapéuticas del agua para mejorar la movilidad, fortalecer los músculos y aliviar el dolor en lesiones musculoesqueléticas y neurológicas.</p>
                </div>
            </div>
            
            <div class="services-grid">
                <div class="service-card">
                    <div class="service-image">
                        <img src="{{ asset('assets/mecanoterapia.jpg') }}" alt="Mecanoterapia">
                    </div>
                    <div class="service-icon">
                        <i class="fas fa-dumbbell"></i>
                    </div>
                    <h3>Mecanoterapia: <span>Fortalece tu Cuerpo</span></h3>
                    <p>Utilizamos equipos especializados para mejorar la función muscular y articular, ideal para la rehabilitación de lesiones musculoesqueléticas y neurológicas.</p>
                </div>

                <div class="service-card">
                    <div class="service-image">
                        <img src="{{ asset('assets/atencion_lesiones.jpg') }}" alt="Atención Integral">
                    </div>
                    <div class="service-icon">
                        <i class="fas fa-user-md"></i>
                    </div>
                    <h3>Atención Integral <span>para Lesiones</span></h3>
                    <p>Ofrecemos tratamientos personalizados para todo tipo de lesiones musculoesqueléticas y neurológicas, con técnicas terapéuticas y un enfoque integral.</p>
                </div>
            </div>
        </section>
        
        <section class="why-choose-us">
            <h2>¿Por qué elegirnos?</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-user-md"></i>
                    </div>
                    <h3>Profesionales Certificados</h3>
                    <p>Nuestro equipo está formado por especialistas con amplia experiencia y certificaciones.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h3>Horarios Flexibles</h3>
                    <p>Ofrecemos horarios adaptados a tus necesidades, incluyendo fines de semana.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-hand-holding-medical"></i>
                    </div>
                    <h3>Atención Personalizada</h3>
                    <p>Cada tratamiento se adapta a las necesidades específicas de cada paciente.</p>
                </div>
            </div>
        </section>
    </main>
    <div class="attribution-container">
        <a href="https://www.flaticon.es/iconos-gratis/fisioterapeuta" class="attribution-link">Fisioterapeuta iconos creados por Freepik - Flaticon</a>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/main.js') }}"></script>
@endsection
