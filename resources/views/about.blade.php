{{-- Reemplaza tu vista about.blade.php con este código --}}
@extends('layouts.app')

@section('title', "Acerca de Nosotros - {{ env('APP_NAME', 'CaballosApp') }}")

@section('content')
<div class="about-page">
    {{-- HERO SECTION - Dinámico --}}
    @if(isset($sectionsData['hero']) && $sectionsData['hero'])
    @php $heroSection = $sectionsData['hero']; @endphp
    <section class="about-hero">
        <div class="hero-background">
            {{-- Imagen de fondo dinámica o logo por defecto --}}
            @if($heroSection->getImagesArray())
                <img src="{{ Storage::url($heroSection->getImagesArray()[0]) }}" alt="Marketplace Ecuestre {{ env('APP_NAME', 'CaballosApp') }}" class="hero-bg-image">
            @else
                <img src="{{ asset('images/logo.png') }}" alt="Marketplace Ecuestre {{ env('APP_NAME', 'CaballosApp') }}" class="hero-bg-image">
            @endif
            <div class="hero-overlay"></div>
        </div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <h1 class="hero-title">{{ $heroSection->title ?? 'Acerca de ' . env('APP_NAME', 'CaballosApp') }}</h1>
                    <p class="hero-subtitle">{{ $heroSection->content ?? 'Tradición en cría y comercio equino' }}</p>
                </div>
            </div>
        </div>
    </section>
    @else
    {{-- Fallback si no hay sección hero --}}
    <section class="about-hero">
        <div class="hero-background">
            <img src="{{ asset('images/logo.png') }}" alt="Marketplace Ecuestre {{ env('APP_NAME', 'CaballosApp') }}" class="hero-bg-image">
            <div class="hero-overlay"></div>
        </div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <h1 class="hero-title">Acerca de {{ env('APP_NAME', 'CaballosApp') }}</h1>
                    <p class="hero-subtitle">Tradición en cría y comercio equino</p>
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- Main Content -->
    <section class="about-content">
        <div class="container">

            {{-- LEGACY SECTION - Tradición y Calidad --}}
            @if(isset($sectionsData['legacy']) && $sectionsData['legacy'])
            @php $legacySection = $sectionsData['legacy']; @endphp
            <div class="content-section">
                <div class="row align-items-center mb-5">
                    <div class="col-lg-6 mb-4 mb-lg-0">
                        <div class="content-text">
                            <h2 class="section-title">{{ $legacySection->title ?? 'Tradición en cría y comercio equino' }}</h2>
                            
                            {{-- Párrafos dinámicos --}}
                            @if($legacySection->getCustomData('paragraph_1'))
                                <p class="section-description">{{ $legacySection->getCustomData('paragraph_1') }}</p>
                            @else
                                <p class="section-description">
                                    En {{ env('APP_NAME', 'CaballosApp') }}, cada caballo listado representa años de selección responsable,
                                    bienestar animal y pasión por el mundo ecuestre. Conectamos compradores con vendedores y haras verificados,
                                    priorizando la transparencia y la confianza.
                                </p>
                            @endif

                            @if($legacySection->getCustomData('paragraph_2'))
                                <p class="section-description">{{ $legacySection->getCustomData('paragraph_2') }}</p>
                            @else
                                <p class="section-description">
                                    Encontrarás ejemplares de distintas razas, edades y disciplinas (salto, doma, enduro y más),
                                    con información clave como pedigrí, historial de salud y entrenamiento.
                                </p>
                            @endif

                            {{-- Quote dinámico --}}
                            @if($legacySection->getCustomData('quote'))
                                <blockquote class="company-quote">
                                    "{{ $legacySection->getCustomData('quote') }}"
                                </blockquote>
                            @else
                                <blockquote class="company-quote">
                                    "No solo publicamos caballos: construimos relaciones de confianza entre compradores,
                                    vendedores y criadores, siempre con el bienestar del caballo como prioridad."
                                </blockquote>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="content-image">
                            {{-- Imagen dinámica --}}
                            @if($legacySection->getImagesArray())
                                <img src="{{ Storage::url($legacySection->getImagesArray()[0]) }}" alt="{{ $legacySection->title }}" class="section-img">
                            @else
                                <img src="{{ asset('images/logo.png') }}" alt="Caballos verificados y bienestar animal" class="section-img logo-placeholder">
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- QUALITY SECTION - Verificación y Seguridad --}}
            @if(isset($sectionsData['quality']) && $sectionsData['quality'])
            @php $qualitySection = $sectionsData['quality']; @endphp
            <div class="content-section bg-light-blue">
                <div class="row align-items-center mb-5">
                    <div class="col-lg-6 order-lg-2 mb-4 mb-lg-0">
                        <div class="content-text">
                            <h2 class="section-title">{{ $qualitySection->title ?? 'Verificación veterinaria y transparencia' }}</h2>
                            
                            {{-- Párrafos dinámicos --}}
                            @if($qualitySection->getCustomData('paragraph_1'))
                                <p class="section-description">{{ $qualitySection->getCustomData('paragraph_1') }}</p>
                            @else
                                <p class="section-description">
                                    Trabajamos con perfiles verificados y documentación actualizada: pedigrí, controles de salud
                                    y antecedentes de entrenamiento. Fomentamos procesos de transacción seguros y claros.
                                </p>
                            @endif

                            @if($qualitySection->getCustomData('paragraph_2'))
                                <p class="section-description">{{ $qualitySection->getCustomData('paragraph_2') }}</p>
                            @else
                                <p class="section-description">
                                    Ofrecemos soporte durante la negociación y facilitamos pagos seguros (p. ej., Mercado Pago),
                                    manteniendo la trazabilidad y la confianza entre las partes.
                                </p>
                            @endif

                            {{-- Badges dinámicos --}}
                            <div class="quality-badges">
                                <span class="badge-item">✓ {{ $qualitySection->getCustomData('badge_1', 'Vendedores verificados') }}</span>
                                <span class="badge-item">✓ {{ $qualitySection->getCustomData('badge_2', 'Exámenes veterinarios') }}</span>
                                <span class="badge-item">✓ {{ $qualitySection->getCustomData('badge_3', 'Pedigrí y documentación') }}</span>
                                <span class="badge-item">✓ {{ $qualitySection->getCustomData('badge_4', 'Pago seguro (Mercado Pago)') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 order-lg-1">
                        <div class="content-image">
                            {{-- Imagen dinámica --}}
                            @if($qualitySection->getImagesArray())
                                <img src="{{ Storage::url($qualitySection->getImagesArray()[0]) }}" alt="{{ $qualitySection->title }}" class="section-img">
                            @else
                                <img src="{{ asset('images/logo.png') }}" alt="Verificación y documentación equina" class="section-img logo-placeholder">
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- PASSION SECTION - Pasión del Equipo --}}
            @if(isset($sectionsData['passion']) && $sectionsData['passion'])
            @php $passionSection = $sectionsData['passion']; @endphp
            <div class="content-section">
                <div class="row align-items-center mb-5">
                    <div class="col-lg-6 mb-4 mb-lg-0">
                        <div class="content-text">
                            <h2 class="section-title">{{ $passionSection->title ?? 'Nuestra pasión por los caballos' }}</h2>
                            
                            {{-- Párrafos dinámicos --}}
                            @if($passionSection->getCustomData('paragraph_1'))
                                <p class="section-description">{{ $passionSection->getCustomData('paragraph_1') }}</p>
                            @else
                                <p class="section-description">
                                    Somos criadores, jinetes y amantes del mundo ecuestre. Conocemos las necesidades
                                    de cada disciplina y apostamos por el bienestar, el manejo responsable y
                                    el acompañamiento honesto durante todo el proceso.
                                </p>
                            @endif

                            @if($passionSection->getCustomData('paragraph_2'))
                                <p class="section-description">{{ $passionSection->getCustomData('paragraph_2') }}</p>
                            @else
                                <p class="section-description">
                                    Nuestra misión es conectar el caballo correcto con la persona adecuada,
                                    promoviendo transacciones seguras y relaciones a largo plazo entre compradores,
                                    vendedores y haras.
                                </p>
                            @endif

                            {{-- Quote del equipo dinámico --}}
                            <div class="team-quote">
                                <div class="quote-content">
                                    <p>"{{ $passionSection->getCustomData('team_quote', 'No solo publicamos caballos, construimos confianza y bienestar alrededor de cada ejemplar.') }}"</p>
                                    <cite>{{ $passionSection->getCustomData('quote_author', '- Equipo ' . env('APP_NAME', 'CaballosApp')) }}</cite>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="content-image">
                            {{-- Imagen dinámica --}}
                            @if($passionSection->getImagesArray())
                                <img src="{{ Storage::url($passionSection->getImagesArray()[0]) }}" alt="{{ $passionSection->title }}" class="section-img">
                            @else
                                <img src="{{ asset('images/logo.png') }}" alt="Equipo {{ env('APP_NAME', 'CaballosApp') }}" class="section-img logo-placeholder">
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- BENEFITS SECTION - Por Qué Elegir --}}
            @if(isset($sectionsData['benefits']) && $sectionsData['benefits'])
            @php $benefitsSection = $sectionsData['benefits']; @endphp
            <div class="content-section bg-dark-blue">
                <div class="row justify-content-center">
                    <div class="col-lg-10 text-center">
                        <h2 class="section-title text-white">{{ $benefitsSection->title ?? 'Por Qué Elegir ' . env('APP_NAME', 'CaballosApp') }}</h2>

                        {{-- Párrafos dinámicos --}}
                        @if($benefitsSection->getCustomData('paragraph_1'))
                            <p class="section-description text-white mb-4">{{ $benefitsSection->getCustomData('paragraph_1') }}</p>
                        @else
                            <p class="section-description text-white mb-4">
                                Elegir {{ env('APP_NAME', 'CaballosApp') }} significa apostar por la transparencia, el bienestar animal
                                y la seguridad en cada operación. Somos una comunidad ecuestre construida sobre la confianza.
                            </p>
                        @endif

                        @if($benefitsSection->getCustomData('paragraph_2'))
                            <p class="section-description text-white mb-5">{{ $benefitsSection->getCustomData('paragraph_2') }}</p>
                        @else
                            <p class="section-description text-white mb-5">
                                Acompañamos a compradores, vendedores y haras con asesoría, visibilidad y herramientas
                                para concretar transacciones responsables y exitosas.
                            </p>
                        @endif

                        {{-- Beneficios dinámicos --}}
                        <div class="benefits-grid">
                            <div class="benefit-item">
                                <div class="benefit-icon">{{ $benefitsSection->getCustomData('benefit_1_icon', '🐎') }}</div>
                                <h4>{{ $benefitsSection->getCustomData('benefit_1_title', 'Caballos verificados') }}</h4>
                                <p>{{ $benefitsSection->getCustomData('benefit_1_desc', 'Perfiles completos con documentación y transparencia') }}</p>
                            </div>
                            <div class="benefit-item">
                                <div class="benefit-icon">{{ $benefitsSection->getCustomData('benefit_2_icon', '🩺') }}</div>
                                <h4>{{ $benefitsSection->getCustomData('benefit_2_title', 'Seguridad y bienestar') }}</h4>
                                <p>{{ $benefitsSection->getCustomData('benefit_2_desc', 'Exámenes veterinarios, pedigrí y control de calidad') }}</p>
                            </div>
                            <div class="benefit-item">
                                <div class="benefit-icon">{{ $benefitsSection->getCustomData('benefit_3_icon', '🤝') }}</div>
                                <h4>{{ $benefitsSection->getCustomData('benefit_3_title', 'Acompañamiento integral') }}</h4>
                                <p>{{ $benefitsSection->getCustomData('benefit_3_desc', 'Soporte durante todo el proceso de compra/venta') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- CTA SECTION - Llamada a la Acción --}}
            @if(isset($sectionsData['cta']) && $sectionsData['cta'])
            @php $ctaSection = $sectionsData['cta']; @endphp
            <div class="content-section">
                <div class="row justify-content-center text-center">
                    <div class="col-lg-8">
                        <h2 class="section-title">{{ $ctaSection->title ?? 'Únete a la comunidad ' . env('APP_NAME', 'CaballosApp') }}</h2>
                        <p class="section-description mb-4">
                            {{ $ctaSection->content ?? 'Explora ejemplares verificados o publica tus caballos con visibilidad y soporte. Hagamos juntos un mercado equino más seguro y transparente.' }}
                        </p>
                        <a href="{{ route('shop.index') }}" class="cta-button">
                            {{ $ctaSection->getCustomData('button_text', 'Explorar Caballos Ahora') }}
                        </a>
                        
                        {{-- Pregunta final dinámica --}}
                        @if($ctaSection->getCustomData('final_question'))
                            <p class="cta-question">{{ $ctaSection->getCustomData('final_question') }}</p>
                        @else
                            <p class="cta-question">¿Qué tipo de caballo estás buscando hoy?</p>
                        @endif
                    </div>
                </div>
            </div>
            @endif

        </div>
    </section>
</div>

<style>
.about-page {
    font-family: 'Inter', sans-serif;
    overflow-x: hidden;
}

.about-hero {
    position: relative;
    height: 60vh;
    min-height: 400px;
    display: flex;
    align-items: center;
    overflow: hidden;
    background: #8B4513;
}

.hero-background {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1;
    display: flex;
    align-items: center;
    justify-content: center;
}

.hero-bg-image {
    max-width: 300px;
    max-height: 200px;
    object-fit: contain;
    opacity: 0.1;
    filter: brightness(0) invert(1);
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
    color: #FAF9F6;
    margin-bottom: 20px;
    text-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
    position: relative;
    z-index: 2;
    line-height: 1.1;
}

.hero-subtitle {
    font-size: 1.3rem;
    color: rgba(252, 250, 241, 0.9);
    position: relative;
    z-index: 2;
    font-weight: 300;
    line-height: 1.4;
}

.about-content {
    padding: 80px 0;
    background: #FAF9F6;
}

.content-section {
    padding: 60px 0;
    margin-bottom: 40px;
}

.bg-light-blue {
    background: linear-gradient(135deg, rgba(0, 169, 224, 0.05) 0%, rgba(0, 207, 180, 0.05) 100%);
    border: 2px solid #DEB887;
    border-radius: 20px;
    margin: 0 20px;
    padding: 50px 40px;
}

.bg-dark-blue {
    background: linear-gradient(135deg, #8B4513 0%, #8B4513 100%);
    border-radius: 20px;
    margin: 0 20px;
    color: white;
    padding: 50px 40px;
    position: relative;
    overflow: hidden;
}

.bg-dark-blue::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg, #DEB887, #CD853F);
    z-index: 1;
}

.bg-dark-blue > * {
    position: relative;
    z-index: 2;
}

.section-title {
    font-size: 2.5rem;
    font-weight: 800;
    color: #101820;
    margin-bottom: 30px;
    line-height: 1.2;
}

.section-title.text-white {
    color: #FAF9F6;
}

.section-description {
    font-size: 1.1rem;
    line-height: 1.7;
    color: #555;
    margin-bottom: 25px;
}

.section-description.text-white {
    color: rgba(252, 250, 241, 0.9);
}

.content-image {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 40px;
    background: linear-gradient(135deg, #DEB887, #CD853F);
    border-radius: 15px;
    border: 2px solid #DEB887;
}

.section-img {
    width: 100%;
    max-width: 300px;
    height: auto;
    border-radius: 15px;
    transition: transform 0.3s ease;
}

.logo-placeholder {
    opacity: 0.8;
    filter: drop-shadow(0 10px 20px #DEB887);
}

.section-img:hover {
    transform: scale(1.05);
}

.company-quote {
    background: linear-gradient(135deg, #DEB887, #CD853F);
    border-left: 4px solid #DEB887;
    padding: 25px 30px;
    margin: 30px 0;
    font-style: italic;
    font-size: 1.1rem;
    line-height: 1.6;
    border-radius: 0 15px 15px 0;
    color: #101820;
}

.quality-badges {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    margin-top: 30px;
}

.badge-item {
    background: linear-gradient(135deg, #DEB887, #DEB887);
    color: white;
    padding: 10px 18px;
    border-radius: 25px;
    font-size: 0.9rem;
    font-weight: 600;
    white-space: nowrap;
    box-shadow: 0 3px 10px #DEB887;
}

.team-quote {
    background: linear-gradient(135deg, #FAF9F6, #F8F6ED);
    border: 2px solid #DEB887;
    border-radius: 15px;
    padding: 30px;
    box-shadow: 0 10px 30px #DEB887;
    margin-top: 30px;
}

.quote-content p {
    font-size: 1.2rem;
    font-style: italic;
    color: #101820;
    margin-bottom: 15px;
    line-height: 1.5;
}

.quote-content cite {
    color: #DEB887;
    font-weight: 600;
    font-style: normal;
    font-size: 0.95rem;
}

.benefits-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 40px;
    margin-top: 50px;
}

.benefit-item {
    text-align: center;
    padding: 30px 20px;
    background: #DEB887;
    border-radius: 15px;
    border: 2px solid rgba(0, 207, 180, 0.3);
    transition: transform 0.3s ease;
}

.benefit-item:hover {
    transform: translateY(-5px);
}

.benefit-icon {
    font-size: 3rem;
    margin-bottom: 20px;
    display: block;
}

.benefit-item h4 {
    color: #FAF9F6;
    font-size: 1.3rem;
    font-weight: 700;
    margin-bottom: 15px;
}

.benefit-item p {
    color: rgba(252, 250, 241, 0.8);
    font-size: 1rem;
    line-height: 1.5;
}

.cta-button {
    display: inline-block;
    background: linear-gradient(135deg, #DEB887, #DEB887);
    color: white;
    padding: 18px 40px;
    border-radius: 50px;
    text-decoration: none;
    font-weight: 700;
    font-size: 1.1rem;
    text-transform: uppercase;
    letter-spacing: 1px;
    transition: all 0.3s ease;
    box-shadow: 0 8px 25px #DEB887;
    margin-bottom: 30px;
    text-align: center;
}

.cta-button:hover {
    background: linear-gradient(135deg, #DEB887, #DEB887);
    transform: translateY(-3px);
    box-shadow: 0 12px 35px #DEB887;
    color: white;
    text-decoration: none;
}

.cta-question {
    font-style: italic;
    color: #666;
    font-size: 1.1rem;
    margin-top: 20px;
}

/* RESPONSIVE STYLES */

/* Large Desktop */
@media (max-width: 1200px) {
    .hero-title {
        font-size: 3.2rem;
    }
    
    .section-title {
        font-size: 2.3rem;
    }
    
    .section-img {
        max-width: 250px;
    }
}

/* Desktop and Large Tablets */
@media (max-width: 992px) {
    .hero-title {
        font-size: 3rem;
    }
    
    .hero-subtitle {
        font-size: 1.2rem;
    }
    
    .section-title {
        font-size: 2.2rem;
    }
    
    .content-section {
        padding: 40px 0;
    }
    
    .about-content {
        padding: 60px 0;
    }
    
    .bg-light-blue,
    .bg-dark-blue {
        margin: 0 15px;
        padding: 35px 25px;
    }
    
    .benefits-grid {
        gap: 30px;
        margin-top: 40px;
    }
    
    .company-quote {
        padding: 20px 25px;
    }
    
    .team-quote {
        padding: 25px;
    }
}

/* Tablets */
@media (max-width: 768px) {
    .about-hero {
        height: 50vh;
        min-height: 350px;
    }
    
    .hero-title {
        font-size: 2.5rem;
        margin-bottom: 15px;
    }
    
    .hero-subtitle {
        font-size: 1.1rem;
    }
    
    .section-title {
        font-size: 2rem;
        margin-bottom: 25px;
        text-align: center;
    }
    
    .section-description {
        font-size: 1rem;
        margin-bottom: 20px;
    }
    
    .content-section {
        padding: 35px 0;
    }
    
    .about-content {
        padding: 40px 0;
    }
    
    .bg-light-blue,
    .bg-dark-blue {
        margin: 0 10px;
        padding: 30px 20px;
        border-radius: 15px;
    }
    
    .quality-badges {
        justify-content: center;
        gap: 12px;
    }
    
    .badge-item {
        font-size: 0.85rem;
        padding: 8px 16px;
    }
    
    .benefits-grid {
        grid-template-columns: 1fr;
        gap: 25px;
        margin-top: 35px;
    }
    
    .benefit-item {
        padding: 25px 15px;
    }
    
    .benefit-icon {
        font-size: 2.5rem;
        margin-bottom: 15px;
    }
    
    .benefit-item h4 {
        font-size: 1.2rem;
        margin-bottom: 12px;
    }
    
    .cta-button {
        padding: 15px 35px;
        font-size: 1rem;
    }
    
    .content-image {
        margin-top: 25px;
        margin-bottom: 25px;
        padding: 30px;
    }
    
    .section-img {
        max-width: 200px;
    }
    
    /* Row reordering for mobile */
    .row .col-lg-6.order-lg-2 {
        order: 1;
    }
    
    .row .col-lg-6.order-lg-1 {
        order: 2;
    }
}

/* Mobile Landscape and Large Mobile */
@media (max-width: 576px) {
    .about-hero {
        height: 45vh;
        min-height: 300px;
    }
    
    .hero-title {
        font-size: 2rem;
        margin-bottom: 12px;
        line-height: 1.2;
    }
    
    .hero-subtitle {
        font-size: 1rem;
    }
    
    .section-title {
        font-size: 1.8rem;
        margin-bottom: 20px;
    }
    
    .section-description {
        font-size: 0.95rem;
        line-height: 1.6;
        margin-bottom: 18px;
    }
    
    .content-section {
        padding: 30px 0;
        margin-bottom: 30px;
    }
    
    .about-content {
        padding: 30px 0;
    }
    
    .bg-light-blue,
    .bg-dark-blue {
        margin: 0 5px;
        padding: 25px 15px;
        border-radius: 12px;
    }
    
    .company-quote {
        padding: 18px 20px;
        font-size: 1rem;
        margin: 25px 0;
        border-radius: 0 8px 8px 0;
    }
    
    .team-quote {
        padding: 20px;
        margin-top: 25px;
    }
    
    .quote-content p {
        font-size: 1.1rem;
        margin-bottom: 12px;
    }
    
    .quote-content cite {
        font-size: 0.9rem;
    }
    
    .quality-badges {
        gap: 10px;
        margin-top: 25px;
    }
    
    .badge-item {
        font-size: 0.8rem;
        padding: 6px 14px;
    }
    
    .benefits-grid {
        gap: 20px;
        margin-top: 30px;
    }
    
    .benefit-item {
        padding: 20px 12px;
    }
    
    .benefit-icon {
        font-size: 2.2rem;
        margin-bottom: 12px;
    }
    
    .benefit-item h4 {
        font-size: 1.1rem;
        margin-bottom: 10px;
    }
    
    .benefit-item p {
        font-size: 0.9rem;
    }
    
    .cta-button {
        padding: 12px 30px;
        font-size: 0.95rem;
        letter-spacing: 0.5px;
        width: 100%;
        max-width: 300px;
    }
    
    .cta-question {
        font-size: 1rem;
        margin-top: 15px;
        line-height: 1.4;
    }
    
    .content-image {
        padding: 25px;
    }
    
    .section-img {
        max-width: 150px;
    }
}

/* Small Mobile */
@media (max-width: 480px) {
    .about-hero {
        height: 40vh;
        min-height: 280px;
    }
    
    .hero-title {
        font-size: 1.8rem;
    }
    
    .hero-subtitle {
        font-size: 0.95rem;
    }
    
    .section-title {
        font-size: 1.6rem;
    }
    
    .section-description {
        font-size: 0.9rem;
    }
    
    .bg-light-blue,
    .bg-dark-blue {
        margin: 0;
        padding: 20px 10px;
        border-radius: 10px;
    }
    
    .company-quote {
        padding: 15px 18px;
        font-size: 0.95rem;
    }
    
    .team-quote {
        padding: 18px;
    }
    
    .quote-content p {
        font-size: 1rem;
    }
    
    .quality-badges {
        gap: 8px;
    }
    
    .badge-item {
        font-size: 0.75rem;
        padding: 5px 12px;
    }
    
    .benefit-icon {
        font-size: 2rem;
        margin-bottom: 10px;
    }
    
    .benefit-item h4 {
        font-size: 1rem;
    }
    
    .benefit-item p {
        font-size: 0.85rem;
    }
    
    .cta-button {
        padding: 10px 25px;
        font-size: 0.9rem;
    }
    
    .content-image {
        padding: 20px;
    }
    
    .section-img {
        max-width: 120px;
    }
}

/* Extra Small Mobile */
@media (max-width: 360px) {
    .hero-title {
        font-size: 1.6rem;
    }
    
    .section-title {
        font-size: 1.5rem;
    }
    
    .container {
        padding-left: 10px;
        padding-right: 10px;
    }
    
    .bg-light-blue,
    .bg-dark-blue {
        padding: 15px 8px;
    }
}

/* Reduced motion for accessibility */
@media (prefers-reduced-motion: reduce) {
    .section-img {
        transition: none;
    }
    
    .cta-button {
        transition: none;
    }
    
    .section-img:hover {
        transform: none;
    }
    
    .cta-button:hover {
        transform: none;
    }
    
    .benefit-item:hover {
        transform: none;
    }
}
</style>
@endsection