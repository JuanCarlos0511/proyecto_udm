@extends('layouts.app')

@section('title', 'Agenda tu Cita')

@section('content')
    <main>
        <section class="hero">
            <h1>Agenda tu cita ahora</h1>
            <p>Agenda tu cita en línea y disfruta de la comodidad de ser atendido desde tu hogar. Nuestro equipo de profesionales está listo para ayudarte.</p>
            
            <div class="search-container">
                <div class="dropdown">
                    <button id="scheduleAppointment" class="schedule-btn">Agendar cita</button>
                    <div id="appointmentOptions" class="dropdown-content">
                        <button id="scheduleInPerson" class="dropdown-btn">Agendar presencialmente</button>
                        <button id="scheduleAtHome" class="dropdown-btn">Agendar a domicilio</button>
                    </div>
                </div>
            </div>
        </section>

        <section class="specialties">
            <h2>Especialidades Médicas de Vanguardia</h2>
            <p>Nuestro equipo de expertos usa atención médica integral y personalizada, con especialidades diseñadas para mejorar la calidad de vida.</p>
            
            <div class="services-grid">
                <div class="service-card">
                    <img src="{{ asset('assets/electroterapia.jpg') }}" alt="Electroterapia">
                    <h3>Electroterapia: Alivio Inmediato</h3>
                    <p>Aprovechamos las propiedades terapéuticas de la electricidad para rehabilitar las lesiones y aliviar el dolor en diferentes áreas anatómicas.</p>
                </div>

                <div class="service-card">
                    <img src="{{ asset('assets/hidroterapia.jpg') }}" alt="Hidroterapia">
                    <h3>Hidroterapia: Sanación con Agua</h3>
                    <p>Aprovechamos las propiedades terapéuticas del agua para rehabilitar los músculos y aliviar el dolor en diferentes áreas anatómicas y neurológicas.</p>
                </div>

                <div class="service-card">
                    <img src="{{ asset('assets/mecanoterapia.jpg') }}" alt="Mecanoterapia">
                    <h3>Mecanoterapia: Fortalece tu Cuerpo</h3>
                    <p>Utilizamos equipos especializados para fortalecer los músculos y mejorar la movilidad en diferentes articulaciones.</p>
                </div>

                <div class="service-card">
                    <img src="{{ asset('assets/atencion-integral.jpg') }}" alt="Atención Integral">
                    <h3>Atención Integral para Lesiones</h3>
                    <p>Nuestros especialistas personalizados están para ti con todo tipo de técnicas terapéuticas y masajes.</p>
                </div>
            </div>
        </section>
    </main>
@endsection

@section('scripts')
    <script src="{{ asset('js/main.js') }}"></script>
@endsection
