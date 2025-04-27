@extends('layouts.app')

@section('title', 'Sobre Nosotros')

@section('extra-css')
    <link rel="stylesheet" href="{{ asset('css/pages/about.css') }}">
@endsection

@section('content')

<section class="about-us-content">
    <div class="container">
        <h1 class="about-title">Sobre Nosotros: Su Bienestar, Nuestra Pasión</h1>
        
        <!-- Historia - Primera sección -->
        <div class="about-section">
            <div class="about-section-content">
                <h2>Nuestra Historia</h2>
                <p>
                    Todo comenzó con un sueño: crear un espacio dedicado a la recuperación y el bienestar, un lugar donde la ciencia de la fisioterapia se encontrara con la calidez humana. Nuestros inicios fueron humildes, creciendo de manera "rupestre" pero siempre impulsados por la firme convicción de poder marcar una diferencia en la vida de las personas.
                </p>
                <p>
                    Escuchando atentamente las necesidades de nuestra comunidad, hemos evolucionado hasta convertirnos en la clínica establecida que somos hoy, un referente de confianza comprometido con ofrecer soluciones efectivas para la salud y la movilidad de nuestros pacientes. Cada paso de nuestro crecimiento ha sido un reflejo de nuestro compromiso con usted y su bienestar.
                </p>
            </div>
            <div class="about-section-image">
                <img src="{{ asset('assets/clinica.jpg') }}" alt="Nuestras instalaciones" class="about-image">
            </div>
        </div>
        
        <!-- Valores - Segunda sección -->
        <div class="about-section">
            <div class="about-section-content full-width">
                <h2>Misión y Valores</h2>
                <p>
                    Nuestra misión es ser su aliado estratégico en el camino hacia la recuperación. Nos dedicamos a <strong>proporcionar atención de fisioterapia de la más alta calidad, impregnada de calidez humana, para fomentar su bienestar generalizado</strong>. 
                </p>
                <p>
                    Trabajamos incansablemente para reintegrar a cada persona a sus actividades diarias de manera eficaz, funcional y sostenible en el tiempo. Guiamos nuestra práctica diaria por estos valores fundamentales:
                </p>
                
                <div class="values-container">
                    <div class="value-row">
                        <div class="value-icon">
                            <img src="{{ asset('assets/valores_honestoidad.jpg') }}" alt="Honestidad">
                        </div>
                        <div class="value-content">
                            <h3>Honestidad</h3>
                            <p>Transparencia total en diagnósticos, tratamientos y expectativas.</p>
                        </div>
                    </div>
                    
                    <div class="value-row">
                        <div class="value-icon">
                            <img src="{{ asset('assets/valores_respeto.jpg') }}" alt="Respeto">
                        </div>
                        <div class="value-content">
                            <h3>Respeto</h3>
                            <p>Trato digno y empático hacia cada paciente, valorando su individualidad.</p>
                        </div>
                    </div>
                    
                    <div class="value-row">
                        <div class="value-icon">
                            <img src="{{ asset('assets/valores_compromiso.jpg') }}" alt="Compromiso">
                        </div>
                        <div class="value-content">
                            <h3>Compromiso</h3>
                            <p>Dedicación absoluta para alcanzar los objetivos de recuperación de cada paciente.</p>
                        </div>
                    </div>
                    
                    <div class="value-row">
                        <div class="value-icon">
                            <img src="{{ asset('assets/valores_profesionalismo.jpg') }}" alt="Profesionalismo">
                        </div>
                        <div class="value-content">
                            <h3>Profesionalismo</h3>
                            <p>Aplicación rigurosa del conocimiento científico y técnico más actualizado.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Servicios - Tercera sección -->
        <div class="about-section">
            <div class="about-section-content">
                <h2>Nuestros Servicios</h2>
                <p>
                    Ofrecemos una amplia gama de servicios y tratamientos especializados, utilizando equipamiento de vanguardia y técnicas probadas para asegurar resultados óptimos. Nuestras áreas de tratamiento incluyen:
                </p>
                <div class="services-grid">
                    <div class="service-item">
                        <h3>Terapia con Agentes Físicos</h3>
                        <p>Termoterapia, crioterapia, ultrasonido y terapias combinadas para tratar diferentes dolencias.</p>
                    </div>
                    <div class="service-item">
                        <h3>Hidroterapia</h3>
                        <p>Rehabilitación en entorno acuático controlado, aprovechando las propiedades terapéuticas del agua.</p>
                    </div>
                    <div class="service-item">
                        <h3>Electroterapia</h3>
                        <p>Tecnología avanzada con TENS, corrientes interferenciales y terapia combinada.</p>
                    </div>
                    <div class="service-item">
                        <h3>Mecanoterapia</h3>
                        <p>Ejercicios terapéuticos supervisados para fortalecer y reeducar el movimiento.</p>
                    </div>
                </div>
                <p>
                    Nos enorgullece contar con <strong>equipamiento de la más alta calidad</strong>, incluyendo avanzados equipos de Ultrasonido, Corrientes Eléctricas, Tracción Cervical, Compresas especializadas, Parafinero profesional y Tina de Hidromasaje, garantizando tratamientos precisos y efectivos.
                </p>
            </div>
            <div class="about-section-image">
                <img src="{{ asset('assets/servicios.jpg') }}" alt="Nuestros servicios" class="about-image">
            </div>
        </div>
        
        <!-- Equipo - Cuarta sección -->
        <div class="about-section reverse">
            <div class="about-section-content">
                <h2>Nuestro Equipo</h2>
                <p>
                    El corazón de nuestra clínica es nuestro <strong>equipo de fisioterapeutas altamente calificados</strong>. Cada miembro está no solo certificado y en constante formación, sino también profundamente dedicado a la atención personalizada y al bienestar integral de nuestros pacientes.
                </p>
                <p>
                    Su pasión por la fisioterapia y su compromiso con la excelencia nos permiten ofrecerle un cuidado cercano, humano y, sobre todo, efectivo. Estamos aquí para escucharle, entender sus necesidades y acompañarle en cada paso de su recuperación.
                </p>
            </div>
            <div class="about-section-image">
                <img src="{{ asset('assets/doctor.jpg') }}" alt="Nuestro equipo" class="about-image">
            </div>
        </div>

        <!-- Conclusión -->
        <div class="closing-container">
            <p class="closing-statement">
                En Clínica Miel, su salud y satisfacción son nuestra máxima prioridad. Le invitamos a conocernos y descubrir cómo podemos ayudarle a vivir una vida más plena y sin limitaciones.
            </p>
        </div>
    </div>
</section>

@endsection
