@extends('layouts.app')

@section('title', "Servicios - {{ env('APP_NAME', 'CaballosApp') }} | Servicios Ecuestres y Publicación de Caballos")

@section('content')

<div class="services-page">
    <!-- Hero Section -->
    @if(isset($sectionsData['hero']) && $sectionsData['hero'])
        @php $heroSection = $sectionsData['hero']; @endphp
        <section class="services-hero">
            <div class="hero-background">
                @if($heroSection->getImagesArray())
                    <img src="{{ Storage::url($heroSection->getImagesArray()[0]) }}" alt="Servicios {{ env('APP_NAME', 'CaballosApp') }}" class="hero-bg-image">
                @else
                    <img src="{{ asset('images/hero-services.jpg') }}" alt="Servicios {{ env('APP_NAME', 'CaballosApp') }}" class="hero-bg-image">
                @endif
                <div class="hero-overlay"></div>
            </div>
            <div class="container">
                <div class="row justify-content-center text-center">
                    <div class="col-lg-10">
                        <h1 class="hero-title">{{ $heroSection->title ?? 'Nuestros Servicios' }}</h1>
                        <p class="hero-subtitle">{{ $heroSection->content ?? 'Servicios ecuestres para compra, venta y bienestar de caballos' }}</p>
                    </div>
                </div>
            </div>
        </section>
    @else
        <section class="services-hero">
            <div class="hero-background">
                <img src="{{ asset('images/hero-services.jpg') }}" alt="Servicios {{ env('APP_NAME', 'CaballosApp') }}" class="hero-bg-image">
                <div class="hero-overlay"></div>
            </div>
            <div class="container">
                <div class="row justify-content-center text-center">
                    <div class="col-lg-10">
                        <h1 class="hero-title">Nuestros Servicios</h1>
                        <p class="hero-subtitle">Servicios ecuestres para compra, venta y bienestar de caballos</p>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- Main Services Section -->
    @if(isset($sectionsData['intro']) && $sectionsData['intro'])
        @php $introSection = $sectionsData['intro']; @endphp
        <section class="main-services">
            <div class="container">
                <div class="row text-center mb-5">
                    <div class="col-lg-8 mx-auto">
                        <h2 class="section-title">{{ $introSection->title ?? '¿Qué Ofrecemos?' }}</h2>
                        <p class="section-description">
                            {{ $introSection->content ?? 'Somos especialistas en compra-venta, asesoría y cuidado de caballos. Te acompañamos con información de pedigrí, salud, entrenamiento y logística para que tomes la mejor decisión.' }}
                        </p>
                    </div>
                </div>

                <div class="row g-4">
                    <!-- Servicio 1 -->
                    <div class="col-lg-4 col-md-6">
                        <div class="service-card">
                            <div class="service-icon">
                                <i class="fas fa-wrench"></i>
                            </div>
                            <h3 class="service-title">Venta de Caballos</h3>
                            <p class="service-description">
                                Publicación y promoción de ejemplares verificados con fotos, videos y detalle de pedigrí y estado de salud.
                            </p>
                            <ul class="service-features">
                                <li><i class="fas fa-check"></i>Anuncios verificados</li>
                                <li><i class="fas fa-check"></i>Galería foto/video</li>
                                <li><i class="fas fa-check"></i>Soporte durante la venta</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Servicio 2 -->
                    <div class="col-lg-4 col-md-6">
                        <div class="service-card">
                            <div class="service-icon">
                                <i class="fas fa-cogs"></i>
                            </div>
                            <h3 class="service-title">Cuidado y Bienestar</h3>
                            <p class="service-description">
                                Orientación en planes sanitarios, nutrición y herrado con proveedores aliados y profesionales del sector.
                            </p>
                            <ul class="service-features">
                                <li><i class="fas fa-check"></i>Plan sanitario</li>
                                <li><i class="fas fa-check"></i>Evaluación veterinaria</li>
                                <li><i class="fas fa-check"></i>Programas de bienestar</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Servicio 3 -->
                    <div class="col-lg-4 col-md-6">
                        <div class="service-card">
                            <div class="service-icon">
                                <i class="fas fa-tools"></i>
                            </div>
                            <h3 class="service-title">Entrenamiento y Preparación</h3>
                            <p class="service-description">
                                Acompañamiento en entrenamiento básico y preparación para disciplinas como salto, doma y enduro.
                            </p>
                            <ul class="service-features">
                                <li><i class="fas fa-check"></i>Planes por nivel</li>
                                <li><i class="fas fa-check"></i>Sesiones de evaluación</li>
                                <li><i class="fas fa-check"></i>Acompañamiento de entrenador</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @else
        <section class="main-services">
            <div class="container">
                <div class="row text-center mb-5">
                    <div class="col-lg-8 mx-auto">
                        <h2 class="section-title">¿Qué Ofrecemos?</h2>
                        <p class="section-description">
                            Somos especialistas en compra-venta, asesoría y cuidado de caballos. 
                            Te acompañamos con información de pedigrí, salud, entrenamiento y logística para que tomes la mejor decisión.
                        </p>
                    </div>
                </div>

                <div class="row g-4">
                    <!-- Servicio 1 -->
                    <div class="col-lg-4 col-md-6">
                        <div class="service-card">
                            <div class="service-icon">
                                <i class="fas fa-wrench"></i>
                            </div>
                            <h3 class="service-title">Venta de Caballos</h3>
                            <p class="service-description">
                                Publicación y promoción de ejemplares verificados con fotos, videos y detalle de pedigrí y estado de salud.
                            </p>
                            <ul class="service-features">
                                <li><i class="fas fa-check"></i>Anuncios verificados</li>
                                <li><i class="fas fa-check"></i>Galería foto/video</li>
                                <li><i class="fas fa-check"></i>Soporte durante la venta</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Servicio 2 -->
                    <div class="col-lg-4 col-md-6">
                        <div class="service-card">
                            <div class="service-icon">
                                <i class="fas fa-cogs"></i>
                            </div>
                            <h3 class="service-title">Cuidado y Bienestar</h3>
                            <p class="service-description">
                                Orientación en planes sanitarios, nutrición y herrado con proveedores aliados y profesionales del sector.
                            </p>
                            <ul class="service-features">
                                <li><i class="fas fa-check"></i>Plan sanitario</li>
                                <li><i class="fas fa-check"></i>Evaluación veterinaria</li>
                                <li><i class="fas fa-check"></i>Programas de bienestar</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Servicio 3 -->
                    <div class="col-lg-4 col-md-6">
                        <div class="service-card">
                            <div class="service-icon">
                                <i class="fas fa-tools"></i>
                            </div>
                            <h3 class="service-title">Entrenamiento y Preparación</h3>
                            <p class="service-description">
                                Acompañamiento en entrenamiento básico y preparación para disciplinas como salto, doma y enduro.
                            </p>
                            <ul class="service-features">
                                <li><i class="fas fa-check"></i>Planes por nivel</li>
                                <li><i class="fas fa-check"></i>Sesiones de evaluación</li>
                                <li><i class="fas fa-check"></i>Acompañamiento de entrenador</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- Services List Section -->
    @if(isset($sectionsData['services_list']) && $sectionsData['services_list'])
        @php $servicesSection = $sectionsData['services_list']; @endphp
        <section class="appliances-section">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6">
                        <h2 class="section-title">{{ $servicesSection->title ?? 'Servicios Ecuestres' }}</h2>
                        <p class="section-description">
                            {{ $servicesSection->content ?? 'Soluciones integrales para tu experiencia ecuestre: desde publicación y asesoría hasta documentación y logística.' }}
                        </p>

                        <div class="appliances-grid">
                            <div class="appliance-item">
                                <div class="appliance-icon">{{ $servicesSection->getCustomData('service_1_icon', '🏠') }}</div>
                                <div class="appliance-info">
                                    <h4>{{ $servicesSection->getCustomData('service_1_title', 'Haras y Criaderos') }}</h4>
                                    <p>{{ $servicesSection->getCustomData('service_1_desc', 'Alianzas con granjas de cría para ejemplares certificados y perfiles destacados') }}</p>
                                </div>
                            </div>

                            <div class="appliance-item">
                                <div class="appliance-icon">{{ $servicesSection->getCustomData('service_2_icon', '🎽') }}</div>
                                <div class="appliance-info">
                                    <h4>{{ $servicesSection->getCustomData('service_2_title', 'Accesorios y Equipamiento') }}</h4>
                                    <p>{{ $servicesSection->getCustomData('service_2_desc', 'Monturas, riendas, protecciones y artículos para el binomio') }}</p>
                                </div>
                            </div>

                            <div class="appliance-item">
                                <div class="appliance-icon">{{ $servicesSection->getCustomData('service_3_icon', '📄') }}</div>
                                <div class="appliance-info">
                                    <h4>{{ $servicesSection->getCustomData('service_3_title', 'Documentación y Traslados') }}</h4>
                                    <p>{{ $servicesSection->getCustomData('service_3_desc', 'Pedigrí, certificados de salud y coordinación logística de transporte') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="appliances-image">
                            @if($servicesSection->getImagesArray())
                                <img src="{{ Storage::url($servicesSection->getImagesArray()[0]) }}" alt="Servicios Ecuestres" class="img-fluid rounded">
                            @else
                                <img src="{{ asset('images/appliances-repair.jpg') }}" alt="Servicios Ecuestres" class="img-fluid rounded">
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @else
        <section class="appliances-section">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6">
                        <h2 class="section-title">Servicios Ecuestres</h2>
                        <p class="section-description">
                            Soluciones integrales para tu experiencia ecuestre: desde publicación y asesoría hasta documentación y logística.
                        </p>

                        <div class="appliances-grid">
                            <div class="appliance-item">
                                <div class="appliance-icon">🏠</div>
                                <div class="appliance-info">
                                    <h4>Haras y Criaderos</h4>
                                    <p>Alianzas con granjas de cría para ejemplares certificados y perfiles destacados</p>
                                </div>
                            </div>

                            <div class="appliance-item">
                                <div class="appliance-icon">🎽</div>
                                <div class="appliance-info">
                                    <h4>Accesorios y Equipamiento</h4>
                                    <p>Monturas, riendas, protecciones y artículos para el binomio</p>
                                </div>
                            </div>

                            <div class="appliance-item">
                                <div class="appliance-icon">📄</div>
                                <div class="appliance-info">
                                    <h4>Documentación y Traslados</h4>
                                    <p>Pedigrí, certificados de salud y coordinación logística de transporte</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="appliances-image">
                            <img src="{{ asset('images/appliances-repair.jpg') }}" alt="Servicios Ecuestres" class="img-fluid rounded">
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- Process Section -->
    @if(isset($sectionsData['process']) && $sectionsData['process'])
        @php $processSection = $sectionsData['process']; @endphp
        <section class="process-section">
            <div class="container">
                <div class="row text-center mb-5">
                    <div class="col-lg-8 mx-auto">
                        <h2 class="section-title">{{ $processSection->title ?? '¿Cómo Trabajamos?' }}</h2>
                        <p class="section-description">
                            {{ $processSection->content ?? 'Te acompañamos desde el primer contacto hasta la entrega segura del ejemplar, con asesoría transparente en cada paso.' }}
                        </p>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-lg-3 col-md-6">
                        <div class="process-step">
                            <div class="step-number">{{ $processSection->getCustomData('step_1_number', '1') }}</div>
                            <div class="step-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <h4 class="step-title">{{ $processSection->getCustomData('step_1_title', 'Contacto') }}</h4>
                            <p class="step-description">
                                {{ $processSection->getCustomData('step_1_desc', 'Cuéntanos qué buscas, tu disciplina y presupuesto para iniciar la búsqueda ideal.') }}
                            </p>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="process-step">
                            <div class="step-number">{{ $processSection->getCustomData('step_2_number', '2') }}</div>
                            <div class="step-icon">
                                <i class="fas fa-search"></i>
                            </div>
                            <h4 class="step-title">{{ $processSection->getCustomData('step_2_title', 'Selección y Visita') }}</h4>
                            <p class="step-description">
                                {{ $processSection->getCustomData('step_2_desc', 'Te presentamos opciones y coordinamos visitas o pruebas con el vendedor/haras.') }}
                            </p>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="process-step">
                            <div class="step-number">{{ $processSection->getCustomData('step_3_number', '3') }}</div>
                            <div class="step-icon">
                                <i class="fas fa-hammer"></i>
                            </div>
                            <h4 class="step-title">{{ $processSection->getCustomData('step_3_title', 'Negociación') }}</h4>
                            <p class="step-description">
                                {{ $processSection->getCustomData('step_3_desc', 'Apoyo en términos, reservas y medios de pago seguros para ambas partes.') }}
                            </p>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="process-step">
                            <div class="step-number">{{ $processSection->getCustomData('step_4_number', '4') }}</div>
                            <div class="step-icon">
                                <i class="fas fa-shield-check"></i>
                            </div>
                            <h4 class="step-title">{{ $processSection->getCustomData('step_4_title', 'Entrega y Seguimiento') }}</h4>
                            <p class="step-description">
                                {{ $processSection->getCustomData('step_4_desc', 'Coordinamos documentación, traslado y seguimiento postventa para tu tranquilidad.') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @else
        <section class="process-section">
            <div class="container">
                <div class="row text-center mb-5">
                    <div class="col-lg-8 mx-auto">
                        <h2 class="section-title">¿Cómo Trabajamos?</h2>
                        <p class="section-description">
                            Te acompañamos desde el primer contacto hasta la entrega segura del ejemplar, con asesoría transparente en cada paso.
                        </p>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-lg-3 col-md-6">
                        <div class="process-step">
                            <div class="step-number">1</div>
                            <div class="step-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <h4 class="step-title">Contacto</h4>
                            <p class="step-description">
                                Cuéntanos qué buscas, tu disciplina y presupuesto para iniciar la búsqueda ideal.
                            </p>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="process-step">
                            <div class="step-number">2</div>
                            <div class="step-icon">
                                <i class="fas fa-search"></i>
                            </div>
                            <h4 class="step-title">Selección y Visita</h4>
                            <p class="step-description">
                                Te presentamos opciones y coordinamos visitas o pruebas con el vendedor/haras.
                            </p>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="process-step">
                            <div class="step-number">3</div>
                            <div class="step-icon">
                                <i class="fas fa-hammer"></i>
                            </div>
                            <h4 class="step-title">Negociación</h4>
                            <p class="step-description">
                                Apoyo en términos, reservas y medios de pago seguros para ambas partes.
                            </p>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="process-step">
                            <div class="step-number">4</div>
                            <div class="step-icon">
                                <i class="fas fa-shield-check"></i>
                            </div>
                            <h4 class="step-title">Entrega y Seguimiento</h4>
                            <p class="step-description">
                                Coordinamos documentación, traslado y seguimiento postventa para tu tranquilidad.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- Section para Haras/Criaderos -->
    <section class="oster-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="oster-image">
                        <img src="{{ asset('images/oster-products.jpg') }}" alt="Equipamiento y Servicios Ecuestres" class="img-fluid rounded">
                    </div>
                </div>

                <div class="col-lg-6">
                    <h2 class="section-title">Para Haras y Criaderos</h2>
                    <p class="section-description">
                        Opciones de membresía y herramientas para perfiles de granjas de cría: gestión de ejemplares, publicaciones destacadas y visibilidad premium en el marketplace.
                    </p>

                    <div class="oster-services">
                        <div class="oster-service">
                            <i class="fas fa-shopping-cart"></i>
                            <div>
                                <h4>Publicación de Ejemplares</h4>
                                <p>Crea listados con pedigrí, videos y galería de imágenes</p>
                            </div>
                        </div>

                        <div class="oster-service">
                            <i class="fas fa-wrench"></i>
                            <div>
                                <h4>Acompañamiento en Transacciones</h4>
                                <p>Soporte en reservas, contratos y validaciones</p>
                            </div>
                        </div>

                        <div class="oster-service">
                            <i class="fas fa-medal"></i>
                            <div>
                                <h4>Perfil Verificado</h4>
                                <p>Construye confianza con insignias y reseñas</p>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('shop.index') }}" class="btn btn-primary btn-lg mt-4">
                        <i class="fas fa-eye me-2"></i>Ver Caballos
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Coverage Area Section -->
    <section class="coverage-section">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-lg-8 mx-auto">
                    <h2 class="section-title">Cobertura y Logística</h2>
                    <p class="section-description">
                        Coordinamos visitas y entregas en múltiples regiones. Consulta disponibilidad y rutas para traslados seguros.
                    </p>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="coverage-area">
                        <h4><i class="fas fa-map-marker-alt me-2"></i>Zona Norte</h4>
                        <p>Ciudades principales y alrededores</p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="coverage-area">
                        <h4><i class="fas fa-map-marker-alt me-2"></i>Zona Centro</h4>
                        <p>Áreas metropolitanas y centros ecuestres</p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="coverage-area">
                        <h4><i class="fas fa-map-marker-alt me-2"></i>Zona Sur</h4>
                        <p>Cobertura regional y áreas rurales</p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="coverage-area">
                        <h4><i class="fas fa-map-marker-alt me-2"></i>Regiones Rurales</h4>
                        <p>Coordinación bajo solicitud</p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="coverage-area">
                        <h4><i class="fas fa-map-marker-alt me-2"></i>Costa y Sierra</h4>
                        <p>Rutas frecuentes y disponibilidad</p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="coverage-area">
                        <h4><i class="fas fa-map-marker-alt me-2"></i>Zonas Especiales</h4>
                        <p>Consulta para traslados de larga distancia</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    @if(isset($sectionsData['cta']) && $sectionsData['cta'])
        @php $ctaSection = $sectionsData['cta']; @endphp
        <section class="cta-section">
            <div class="container">
                <div class="row justify-content-center text-center">
                    <div class="col-lg-8">
                        <h2 class="cta-title">{{ $ctaSection->title ?? '¿Listo para encontrar tu próximo caballo?' }}</h2>
                        <p class="cta-description">
                            {{ $ctaSection->content ?? 'Habla con nuestro equipo y recibe asesoría gratuita para elegir el ejemplar ideal según tu disciplina.' }}
                        </p>
                        
                        <div class="cta-buttons">
                            <a href="https://wa.me/593987654321" target="_blank" class="btn btn-whatsapp btn-lg me-3">
                                <i class="fab fa-whatsapp me-2"></i>{{ $ctaSection->getCustomData('button_primary_text', 'WhatsApp') }}
                            </a>
                            <a href="{{ route('contact.index') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-envelope me-2"></i>{{ $ctaSection->getCustomData('button_secondary_text', 'Contactar') }}
                            </a>
                        </div>

                        <div class="contact-info mt-4">
                            <div class="contact-item">
                                <i class="fas fa-clock"></i>
                                <span>Lunes a Viernes: 8:00 AM - 6:00 PM | Sábados: 8:00 AM - 4:00 PM</span>
                            </div>
                            <div class="contact-item">
                                <i class="fas fa-phone"></i>
                                <span>+593 2 234 5678</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @else
        <section class="cta-section">
            <div class="container">
                <div class="row justify-content-center text-center">
                    <div class="col-lg-8">
                        <h2 class="cta-title">¿Listo para encontrar tu próximo caballo?</h2>
                        <p class="cta-description">
                            Habla con nuestro equipo y recibe asesoría gratuita para elegir el ejemplar ideal según tu disciplina.
                        </p>
                        
                        <div class="cta-buttons">
                            <a href="https://wa.me/593987654321" target="_blank" class="btn btn-whatsapp btn-lg me-3">
                                <i class="fab fa-whatsapp me-2"></i>WhatsApp
                            </a>
                            <a href="{{ route('contact.index') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-envelope me-2"></i>Contactar
                            </a>
                        </div>

                        <div class="contact-info mt-4">
                            <div class="contact-item">
                                <i class="fas fa-clock"></i>
                                <span>Lunes a Viernes: 8:00 AM - 6:00 PM | Sábados: 8:00 AM - 4:00 PM</span>
                            </div>
                            <div class="contact-item">
                                <i class="fas fa-phone"></i>
                                <span>+593 2 234 5678</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
</div>

<style>
.services-page {
    font-family: 'Inter', sans-serif;
}

/* Hero Section */
.services-hero {
    position: relative;
    height: 60vh;
    min-height: 400px;
    display: flex;
    align-items: center;
    overflow: hidden;
}

.hero-background {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1;
}

.hero-bg-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.hero-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: #8B4513;
}

.hero-title {
    font-size: 3.5rem;
    font-weight: 900;
    color: white;
    margin-bottom: 20px;
    text-shadow: 0 4px 20px #DEB887;
    position: relative;
    z-index: 2;
}

.hero-subtitle {
    font-size: 1.3rem;
    color: rgba(255, 255, 255, 0.95);
    position: relative;
    z-index: 2;
    font-weight: 300;
}

/* Main Services */
.main-services {
    padding: 100px 0;
    background: #f8f9fa;
}

.section-title {
    font-size: 2.8rem;
    font-weight: 800;
    color: #101820;
    margin-bottom: 30px;
    line-height: 1.2;
}

.section-description {
    font-size: 1.2rem;
    line-height: 1.7;
    color: #666;
    margin-bottom: 50px;
}

.service-card {
    background: white;
    padding: 40px 30px;
    border-radius: 20px;
    text-align: center;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    transition: all 0.4s ease;
    height: 100%;
    border: 3px solid transparent;
}

.service-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 60px #DEB887;
    border-color: #DEB887;
}

.service-icon {
    background: linear-gradient(135deg, #DEB887, #DEB887);
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 30px;
    color: white;
    font-size: 2rem;
}

.service-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #101820;
    margin-bottom: 20px;
}

.service-description {
    color: #666;
    line-height: 1.6;
    margin-bottom: 25px;
}

.service-features {
    list-style: none;
    padding: 0;
    text-align: left;
}

.service-features li {
    padding: 8px 0;
    color: #555;
    font-size: 0.9rem;
}

.service-features i {
    color: #DEB887;
    margin-right: 10px;
    font-size: 0.8rem;
}

/* Appliances Section */
.appliances-section {
    padding: 100px 0;
    background: white;
}

.appliances-grid {
    display: flex;
    flex-direction: column;
    gap: 30px;
    margin-top: 40px;
}

.appliance-item {
    display: flex;
    align-items: center;
    gap: 20px;
    padding: 25px;
    background: #f8f9fa;
    border-radius: 15px;
    transition: all 0.3s ease;
}

.appliance-item:hover {
    background: rgba(0, 169, 224, 0.05);
    transform: translateX(10px);
}

.appliance-icon {
    font-size: 3rem;
    flex-shrink: 0;
}

.appliance-info h4 {
    color: #DEB887;
    font-weight: 700;
    margin-bottom: 10px;
}

.appliance-info p {
    color: #666;
    margin: 0;
    line-height: 1.5;
}

.appliances-image {
    position: relative;
}

.appliances-image::after {
    content: '';
    position: absolute;
    top: 20px;
    left: 20px;
    right: -20px;
    bottom: -20px;
    background: linear-gradient(135deg, #DEB887, #DEB887);
    border-radius: 15px;
    z-index: -1;
}

/* Process Section */
.process-section {
    padding: 100px 0;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.process-step {
    text-align: center;
    position: relative;
    padding: 30px 20px;
    background: white;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.process-step:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(0, 169, 224, 0.15);
}

.step-number {
    position: absolute;
    top: -20px;
    left: 50%;
    transform: translateX(-50%);
    background: linear-gradient(135deg, #DEB887, #DEB887);
    color: white;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1.2rem;
}

.step-icon {
    background: #DEB887;
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 20px auto 25px;
    color: #DEB887;
    font-size: 2rem;
}

.step-title {
    color: #101820;
    font-weight: 700;
    margin-bottom: 15px;
}

.step-description {
    color: #666;
    line-height: 1.5;
    font-size: 0.95rem;
}

/* Oster Section */
.oster-section {
    padding: 100px 0;
    background: white;
}

.oster-image {
    position: relative;
}

.oster-image::after {
    content: '';
    position: absolute;
    top: -20px;
    left: -20px;
    right: 20px;
    bottom: 20px;
    background: linear-gradient(135deg, #DEB887, #DEB887);
    border-radius: 15px;
    z-index: -1;
}

.oster-services {
    margin: 40px 0;
}

.oster-service {
    display: flex;
    align-items: center;
    gap: 20px;
    margin-bottom: 30px;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 15px;
    transition: all 0.3s ease;
}

.oster-service:hover {
    background: rgba(0, 169, 224, 0.05);
    transform: translateX(10px);
}

.oster-service i {
    background: linear-gradient(135deg, #DEB887, #DEB887);
    color: white;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    flex-shrink: 0;
}

.oster-service h4 {
    color: #101820;
    font-weight: 700;
    margin-bottom: 5px;
}

.oster-service p {
    color: #666;
    margin: 0;
    font-size: 0.9rem;
}

/* Coverage Section */
.coverage-section {
    padding: 100px 0;
    background: #f8f9fa;
}

.coverage-area {
    background: white;
    padding: 30px;
    border-radius: 15px;
    text-align: center;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
}

.coverage-area:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0, 169, 224, 0.15);
}

.coverage-area h4 {
    color: #DEB887;
    font-weight: 700;
    margin-bottom: 15px;
}

.coverage-area p {
    color: #666;
    margin: 0;
    line-height: 1.5;
}

/* CTA Section */
.cta-section {
    padding: 100px 0;
    background: linear-gradient(135deg, #8B4513 0%, #8B4513 100%);
    color: white;
}

.cta-title {
    font-size: 2.5rem;
    font-weight: 800;
    margin-bottom: 20px;
}

.cta-description {
    font-size: 1.2rem;
    line-height: 1.6;
    margin-bottom: 40px;
    opacity: 0.9;
}

.cta-buttons {
    margin-bottom: 40px;
}

.btn-whatsapp {
    background: #25d366;
    border-color: #25d366;
    color: white;
}

.btn-whatsapp:hover {
    background: #128c7e;
    border-color: #128c7e;
    color: white;
}

.btn-primary {
    background: linear-gradient(135deg, #DEB887, #DEB887);
    border: none;
    padding: 15px 30px;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #DEB887, #DEB887);
    transform: translateY(-2px);
}

.contact-info {
    opacity: 0.8;
}

.contact-item {
    display: inline-block;
    margin: 0 20px;
    font-size: 0.9rem;
}

.contact-item i {
    margin-right: 8px;
    color: #DEB887;
}

/* Responsive */
@media (max-width: 992px) {
    .hero-title {
        font-size: 2.8rem;
    }
    
    .section-title {
        font-size: 2.4rem;
    }
    
    .appliances-grid {
        gap: 20px;
    }
    
    .appliance-item {
        flex-direction: column;
        text-align: center;
        gap: 15px;
    }
}

@media (max-width: 768px) {
    .hero-title {
        font-size: 2.2rem;
    }
    
    .hero-subtitle {
        font-size: 1.1rem;
    }
    
    .section-title {
        font-size: 2rem;
    }
    
    .main-services, 
    .appliances-section, 
    .process-section, 
    .oster-section, 
    .coverage-section, 
    .cta-section {
        padding: 60px 0;
    }
    
    .service-card {
        padding: 30px 20px;
    }
    
    .cta-buttons .btn {
        display: block;
        margin: 10px auto;
        width: 80%;
    }
    
    .contact-item {
        display: block;
        margin: 10px 0;
    }
}

@media (max-width: 576px) {
    .hero-title {
        font-size: 1.8rem;
    }
    
    .section-title {
        font-size: 1.8rem;
    }
    
    .service-icon {
        width: 60px;
        height: 60px;
        font-size: 1.5rem;
    }
    
    .step-icon {
        width: 60px;
        height: 60px;
        font-size: 1.5rem;
    }
}
</style>

@endsection