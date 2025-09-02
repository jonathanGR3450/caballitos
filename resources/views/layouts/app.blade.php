<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ env('APP_NAME', 'CaballosApp') }}</title>

    {{-- Favicon y meta para iconos --}}
    <link rel="icon" type="image/png" href="{{ asset('images/favicon-96x96.png') }}" sizes="96x96">
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/favicon.svg') }}">
    <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ asset('images/site.webmanifest') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">


    {{-- CSS y JS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
/* Navigation Responsive Styles - VERSIÓN PROFESIONAL */
nav {
    background: linear-gradient(135deg, #CD853F 50%, #8B4513 100%);
    padding: 12px 20px;
    border-bottom: 3px solid #DEB887;
    position: relative;
    box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
}

.navbar-container {
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
    position: relative;
}

.logo {
    display: flex;
    align-items: center;
    gap: 12px;
    z-index: 1000;
}

.logo img {
    height: 75px;
    filter: brightness(0) invert(1);
}

.logo span {
    font-size: 1.2rem;
    letter-spacing: 2px;
    font-weight: 600;
    color: #FAF9F6;
    text-shadow: none;
}

/* Desktop Navigation */
.nav-links {
    display: flex;
    align-items: center;
    gap: 30px;
    margin: 0;
    padding: 0;
    list-style: none;
}

.nav-links a {
    color: #DEB887;
    text-decoration: none;
    font-size: 0.95rem;
    font-weight: 500;
    position: relative;
    transition: all 0.3s ease;
    text-shadow: none;
}

.nav-links a:hover,
.nav-links a.active {
    color: #8B4513;
    transform: translateY(-1px);
}

.nav-links a::after {
    content: '';
    position: absolute;
    bottom: -5px;
    left: 0;
    width: 0;
    height: 2px;
    background: #DEB887;
    transition: width 0.3s ease;
}

.nav-links a:hover::after,
.nav-links a.active::after {
    width: 100%;
}

.nav-icons {
    display: flex;
    align-items: center;
    gap: 18px;
}

.nav-icons i {
    color: #FAF9F6;
    font-size: 1.1rem;
    cursor: pointer;
    transition: all 0.3s ease;
    text-shadow: none;
}

.nav-icons i:hover {
    color: #DEB887;
    transform: scale(1.1);
}

.nav-icons a {
    color: #FAF9F6;
    text-decoration: none;
    transition: all 0.3s ease;
    text-shadow: none;
}

.nav-icons a:hover {
    color: #8B4513;
    transform: translateY(-1px);
}

/* Mobile Menu Toggle */
.mobile-menu-toggle {
    display: none;
    background: none;
    border: none;
    color: #FAF9F6;
    font-size: 1.5rem;
    cursor: pointer;
    padding: 5px;
    z-index: 1001;
    transition: all 0.3s ease;
}

.mobile-menu-toggle:hover {
    color: #DEB887;
    transform: scale(1.1);
}

/* Mobile Navigation */
.mobile-nav {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100vh;
    background: linear-gradient(135deg, rgba(16, 24, 32, 0.97) 0%, rgba(26, 37, 47, 0.97) 100%);
    backdrop-filter: blur(15px);
    z-index: 999;
    padding-top: 80px;
}

.mobile-nav.active {
    display: block;
    animation: slideInDown 0.3s ease-out;
}

@keyframes slideInDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.mobile-nav-links {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 30px;
    margin-top: 50px;
}

.mobile-nav-links a {
    color: #FAF9F6;
    text-decoration: none;
    font-size: 1.2rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    transition: all 0.3s ease;
    padding: 15px 25px;
    border-radius: 8px;
    border: 2px solid transparent;
    text-shadow: none;
}

.mobile-nav-links a:hover,
.mobile-nav-links a.active {
    color: #101820;
    background: #FAF9F6;
    border-color: #DEB887;
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

.mobile-nav-icons {
    display: flex;
    justify-content: center;
    gap: 25px;
    margin-top: 50px;
    padding: 0 20px;
}

.mobile-nav-icons a,
.mobile-nav-icons .dropdown {
    color: #FAF9F6;
    font-size: 1.3rem;
    text-decoration: none;
    transition: all 0.3s ease;
    padding: 15px;
    border-radius: 50%;
    background: rgba(252, 250, 241, 0.1);
    border: 2px solid rgba(252, 250, 241, 0.2);
    width: 55px;
    height: 55px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.mobile-nav-icons a:hover,
.mobile-nav-icons .dropdown:hover {
    color: #101820;
    background: #FAF9F6;
    border-color: #DEB887;
    transform: scale(1.15);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

/* Dropdown Styles */
.dropdown-toggle {
    color: inherit;
    text-decoration: none;
    position: relative;
}

.dropdown-toggle::after {
    display: none;
}

.dropdown-menu {
    background: #FAF9F6;
    border: 2px solid #DEB887;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    min-width: 200px;
    padding: 15px 0;
    margin-top: 10px;
}

.dropdown-item {
    padding: 12px 25px;
    font-size: 0.9rem;
    color: #101820;
    font-weight: 500;
    transition: all 0.3s ease;
    border-left: 3px solid transparent;
}

.dropdown-item:hover {
    background: linear-gradient(90deg, #DEB887, #DEB887);
    color: #FAF9F6;
    border-left-color: #101820;
    transform: translateX(5px);
}

.dropdown-divider {
    margin: 10px 0;
    border-color: #DEB887;
}

/* Footer Styles */
footer {
    background: #8B4513 !important;
    border-top: 3px solid #DEB887;
    position: relative;
}

footer::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 2px;
    background: linear-gradient(90deg, #DEB887, #DEB887, #DEB887);
}

.footer-logo img {
    transition: all 0.3s ease;
    filter: brightness(0) invert(1);
}

.footer-logo:hover img {
    transform: scale(1.05);
}

.social-links {
    display: flex;
    align-items: center;
}

.social-link {
    color: rgba(252, 250, 241, 0.8);
    transition: all 0.3s ease;
    width: 45px;
    height: 45px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: #DEB887;
    margin: 0 5px;
    border: 2px solid transparent;
}

.social-link:hover {
    color: #101820;
    background: #DEB887;
    border-color: #FAF9F6;
    transform: translateY(-3px) scale(1.1);
    box-shadow: 0 5px 15px #DEB887;
}

.footer-links {
    margin: 0;
}

.footer-links li {
    margin-bottom: 10px;
}

.footer-link {
    color: rgba(252, 250, 241, 0.8);
    text-decoration: none;
    font-size: 0.9rem;
    font-weight: 400;
    transition: all 0.3s ease;
    position: relative;
    padding-left: 0;
}

.footer-link:hover {
    color: #DEB887;
    padding-left: 12px;
    font-weight: 500;
}

.footer-link::before {
    content: '';
    position: absolute;
    left: 0;
    top: 50%;
    transform: translateY(-50%);
    width: 0;
    height: 2px;
    background: linear-gradient(90deg, #DEB887, #DEB887);
    transition: width 0.3s ease;
}

.footer-link:hover::before {
    width: 8px;
}

.newsletter-form {
    position: relative;
}

.newsletter-input {
    background: rgba(252, 250, 241, 0.1);
    border: 2px solid #DEB887;
    border-radius: 25px 0 0 25px;
    color: #FAF9F6;
    padding: 14px 22px;
    font-size: 0.9rem;
    transition: all 0.3s ease;
}

.newsletter-input::placeholder {
    color: rgba(252, 250, 241, 0.6);
}

.newsletter-input:focus {
    background: rgba(252, 250, 241, 0.15);
    border-color: #DEB887;
    box-shadow: 0 0 0 3px #DEB887;
    color: #FAF9F6;
}

.newsletter-btn {
    background: linear-gradient(135deg, #DEB887, #DEB887);
    border: 2px solid #DEB887;
    border-radius: 0 25px 25px 0;
    color: #FAF9F6;
    padding: 14px 22px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.newsletter-btn:hover {
    background: linear-gradient(135deg, #DEB887, #DEB887);
    border-color: #DEB887;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px #DEB887;
}

.contact-info .footer-link {
    color: rgba(252, 250, 241, 0.9);
    text-decoration: none;
}

.contact-info .footer-link:hover {
    color: #DEB887;
    padding-left: 0;
}

.certification-badge {
    background: #DEB887;
    border: 1px solid #DEB887;
    border-radius: 15px;
    padding: 8px 16px;
    font-size: 0.8rem;
    color: #FAF9F6;
    display: inline-block;
    transition: all 0.3s ease;
    font-weight: 500;
}

.certification-badge:hover {
    background: linear-gradient(135deg, #DEB887, #DEB887);
    border-color: #FAF9F6;
    transform: translateY(-2px);
    box-shadow: 0 3px 10px #DEB887;
}

/* Responsive Media Queries */
@media (max-width: 992px) {
    nav {
        padding: 12px 15px;
    }
    
    .nav-links {
        gap: 20px;
    }
    
    .nav-icons {
        gap: 15px;
    }
}

@media (max-width: 768px) {
    .nav-links {
        display: none;
    }
    
    .mobile-menu-toggle {
        display: block;
    }
    
    .nav-icons {
        gap: 12px;
    }
    
    .nav-icons a {
        font-size: 1rem;
    }
    
    .logo img {
        height: 45px;
    }
    
    /* Footer responsive */
    .social-links {
        justify-content: center;
        margin-top: 20px;
    }
    
    .footer-certifications {
        text-align: center !important;
        margin-top: 20px;
    }
    
    .certification-badge {
        display: block;
        margin: 8px auto;
        max-width: 200px;
    }
    
    .newsletter-input,
    .newsletter-btn {
        border-radius: 25px;
        margin-bottom: 10px;
    }
    
    .input-group {
        flex-direction: column;
    }
}

@media (max-width: 576px) {
    nav {
        padding: 8px 15px;
    }
    
    .logo img {
        height: 35px;
    }
    
    .nav-icons {
        gap: 8px;
    }
    
    .nav-icons a {
        font-size: 0.9rem;
    }
    
    /* Footer responsive */
    footer {
        padding-top: 3rem !important;
    }
    
    .footer-logo {
        text-align: center;
    }
    
    .col-lg-2 {
        margin-bottom: 2rem;
    }
}

@media (max-width: 480px) {
    .mobile-nav-links a {
        font-size: 1.1rem;
        padding: 12px 20px;
    }
    
    .mobile-nav-icons a,
    .mobile-nav-icons .dropdown {
        width: 50px;
        height: 50px;
        font-size: 1.2rem;
    }
}

/* ===== SUBMENU STYLES - PROFESIONALES ===== */
.nav-item {
    position: relative;
    display: inline-block;
}

.nav-item > a {
    color: #FAF9F6;
    text-decoration: none;
    font-size: 0.95rem;
    font-weight: 500;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 5px;
    text-shadow: none;
}

.nav-item:hover > a {
    color: #DEB887;
    transform: translateY(-1px);
}

/* Primer nivel del submenu */
.submenu {
    position: absolute;
    top: calc(100% + 15px);
    left: 50%;
    transform: translateX(-50%);
    background: linear-gradient(135deg, #FAF9F6 0%, #F8F6ED 100%);
    border: 2px solid #DEB887;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    min-width: 240px;
    opacity: 0;
    visibility: hidden;
    transform: translateX(-50%) translateY(-15px);
    transition: all 0.4s ease;
    z-index: 1000;
    padding: 10px 0;
}

.nav-item:hover .submenu {
    opacity: 1;
    visibility: visible;
    transform: translateX(-50%) translateY(0);
}

/* Items de categorías */
.category-item {
    position: relative;
}

.category-item > a {
    display: block;
    padding: 14px 25px;
    color: #101820;
    text-decoration: none;
    transition: all 0.3s ease;
    border-left: 3px solid transparent;
    font-weight: 500;
    font-size: 14px;
}

.category-item:hover > a {
    background: linear-gradient(90deg, #DEB887, #CD853F);
    color: #101820;
    border-left-color: #DEB887;
    padding-left: 30px;
    font-weight: 600;
}

/* Segundo nivel del submenu (si lo necesitas) */
.categories-submenu {
    position: absolute;
    top: 0;
    left: calc(100% + 10px);
    background: linear-gradient(135deg, #FAF9F6 0%, #F8F6ED 100%);
    border: 2px solid #DEB887;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    min-width: 280px;
    opacity: 0;
    visibility: hidden;
    transform: translateX(-15px);
    transition: all 0.4s ease;
    z-index: 1001;
    padding: 20px 0;
}

.country-item:hover .categories-submenu {
    opacity: 1;
    visibility: visible;
    transform: translateX(0);
}

/* Header del submenu de categorías */
.categories-header {
    padding: 0 25px 15px;
    border-bottom: 3px solid #DEB887;
    margin-bottom: 15px;
}

.categories-header h4 {
    margin: 0;
    color: #DEB887;
    font-size: 16px;
    font-weight: bold;
    text-transform: uppercase;
    letter-spacing: 1px;
}

/* Items de categorías del segundo nivel */
.category-item a {
    display: block;
    padding: 12px 25px;
    color: rgba(16, 24, 32, 0.8);
    text-decoration: none;
    font-size: 13px;
    transition: all 0.3s ease;
    border-left: 3px solid transparent;
}

.category-item a:hover {
    background: linear-gradient(90deg, rgba(0, 169, 224, 0.15), rgba(0, 207, 180, 0.15));
    color: #101820;
    border-left-color: #DEB887;
    padding-left: 32px;
    font-weight: 600;
}

/* Mobile submenu */
.mobile-submenu {
    display: none;
    background: rgba(252, 250, 241, 0.1);
    border: 1px solid #DEB887;
    padding: 20px 25px;
    margin-top: 15px;
    border-radius: 8px;
    margin-left: 15px;
}

.mobile-submenu.active {
    display: block;
}

.mobile-nav .categories-submenu {
    display: none;
    margin-left: 25px;
    margin-top: 15px;
    background: rgba(252, 250, 241, 0.1);
    border: 1px solid #DEB887;
    border-radius: 8px;
    padding: 15px;
}

/* Responsive para el submenu */
@media (max-width: 768px) {
    .submenu {
        min-width: 280px;
        left: 0;
        transform: translateX(0);
    }
    
    .nav-item:hover .submenu {
        transform: translateX(0) translateY(0);
    }
}

/* ===== FIN SUBMENU STYLES ===== */

/* Badge del carrito actualizado */
.badge {
    background: linear-gradient(135deg, #DEB887, #DEB887) !important;
    color: #FAF9F6 !important;
    border: 1px solid rgba(252, 250, 241, 0.3);
    font-weight: 600;
    border-radius: 12px;
    padding: 4px 8px;
    font-size: 0.75rem;
}

/* Efectos hover mejorados para enlaces principales */
.nav-links a,
.mobile-nav-links a {
    position: relative;
    overflow: hidden;
}

.nav-links a::before,
.mobile-nav-links a::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, #DEB887, transparent);
    transition: left 0.5s ease;
}

.nav-links a:hover::before,
.mobile-nav-links a:hover::before {
    left: 100%;
}
</style>

    @vite(['resources/js/app.js'])
</head>
<body>
   <nav>
    <div class="navbar-container">
         <div class="footer-logo mb-3">
                    <a href="{{ route('home') }}" class="d-inline-block" aria-label="Ir al inicio">
                        <img src="{{ asset('images/logo.png') }}" alt="{{ env('APP_NAME', 'CaballosApp') }} Logo" style="height: 60px;">
                    </a>
                </div>

        <!-- Desktop Navigation -->
        <div class="nav-links">
            <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Inicio</a>

            {{-- NAV ITEM CON SUBMENU DE PRODUCTOS --}}
            <div class="nav-item">
                <a href="#" class="has-submenu">
                    Productos <i class="fas fa-chevron-down" style="font-size: .75rem;"></i>
                </a>

                <div class="submenu">
                    @forelse(($categories ?? collect())->unique(fn($c) => mb_strtolower(trim($c->name))) as $category)
                        <div class="category-item">
                            <a href="{{ route('shop.index', ['category' => $category->id]) }}">
                                {{ $category->name }}
                            </a>
                        </div>
                    @empty
                        {{-- Categorías por defecto para electrodomésticos --}}
                        <div class="category-item">
                            <a href="{{ route('shop.index') }}">Todos los productos</a>
                        </div>
                        <div class="category-item">
                            <a href="{{ route('shop.index', ['category' => 'licuadoras']) }}">Licuadoras</a>
                        </div>
                        <div class="category-item">
                            <a href="{{ route('shop.index', ['category' => 'freidoras']) }}">Freidoras de Aire</a>
                        </div>
                        <div class="category-item">
                            <a href="{{ route('shop.index', ['category' => 'sanducheras']) }}">Sanducheras</a>
                        </div>
                        <div class="category-item">
                            <a href="{{ route('shop.index', ['category' => 'pequenos']) }}">Pequeños Electrodomésticos</a>
                        </div>
                    @endforelse
                </div>
            </div>

            <a href="{{ route('about') }}">Quiénes Somos</a>
            <a href="{{ route('contact.index') }}" class="{{ request()->routeIs('contact.index') ? 'active' : '' }}">Contacto</a>
            <a href="{{ route('recipes') }}">Servicios</a>
        </div>

        <!-- Desktop Icons -->
        <div class="nav-icons">
           
        </div>

        <!-- Mobile Menu Toggle -->
        <button class="mobile-menu-toggle" onclick="toggleMobileMenu()">
            <i class="fas fa-bars" id="menuIcon"></i>
        </button>
    </div>

    <!-- Mobile Navigation -->
    <div class="mobile-nav" id="mobileNav">
        <div class="mobile-nav-links">
            <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Inicio</a>
            
            {{-- Productos en móvil con submenu --}}
            <div class="mobile-nav-item">
                <a href="{{ route('shop.index') }}" onclick="toggleMobileSubmenu(event)">
                    Productos <i class="fas fa-chevron-down ms-2"></i>
                </a>
                <div class="mobile-submenu" id="mobileProductsSubmenu">
                    @forelse(($categories ?? collect())->unique(fn($c) => mb_strtolower(trim($c->name))) as $category)
                        <a href="{{ route('shop.index', ['category' => $category->id]) }}">{{ $category->name }}</a>
                    @empty
                        <a href="{{ route('shop.index') }}">Todos los productos</a>
                        <a href="{{ route('shop.index', ['category' => 'licuadoras']) }}">Licuadoras</a>
                        <a href="{{ route('shop.index', ['category' => 'freidoras']) }}">Freidoras de Aire</a>
                        <a href="{{ route('shop.index', ['category' => 'sanducheras']) }}">Sanducheras</a>
                        <a href="{{ route('shop.index', ['category' => 'pequenos']) }}">Pequeños Electrodomésticos</a>
                    @endforelse
                </div>
            </div>
            
            <a href="{{ route('about') }}">Quiénes Somos</a>
            <a href="{{ route('contact.index') }}" class="{{ request()->routeIs('contact.index') ? 'active' : '' }}">Contacto</a>
            <a href="{{ route('recipes') }}">Servicios</a>
        </div>

    
    </div>
</nav>

    @include('admin.products._flash')
    <main class="">
        @yield('content')
    </main>

    <!-- Footer -->
   <!-- Footer -->
<!-- Footer -->
<footer class="text-white pt-5 pb-4">
    <div class="container">
        <div class="row">
            <!-- Logo & descripción -->
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="footer-logo mb-3">
                    <img src="{{ asset('images/logo.png') }}" alt="{{ env('APP_NAME', 'CaballosApp') }} Logo" style="height: 50px;">
                </div>
                <h4 class="fw-bold mb-3">{{ env('APP_NAME', 'CaballosApp') }}</h4>
                <p class="text-white small mb-3">
                    Tu tienda especializada en electrodomésticos de calidad. Ofrecemos las mejores marcas con garantía, servicio técnico especializado y atención personalizada.
                </p>
                <div class="social-links">
                    <a href="#" class="social-link me-3" title="Facebook">
                        <i class="fab fa-facebook fa-lg"></i>
                    </a>
                    <a href="#" class="social-link me-3" title="Instagram">
                        <i class="fab fa-instagram fa-lg"></i>
                    </a>
                    <a href="#" class="social-link me-3" title="WhatsApp">
                        <i class="fab fa-whatsapp fa-lg"></i>
                    </a>
                    <a href="mailto:info@electrahome.com" class="social-link" title="Email">
                        <i class="fas fa-envelope fa-lg"></i>
                    </a>
                </div>
            </div>

            <!-- Navigation Links -->
            <div class="col-lg-2 col-md-6 mb-4">
                <h5 class="text-uppercase fw-semibold mb-3">Navegación</h5>
                <ul class="list-unstyled footer-links">
                    <li><a href="{{ route('home') }}" class="footer-link">Inicio</a></li>
                    <li><a href="{{ route('shop.index') }}" class="footer-link">Productos</a></li>
                    <li><a href="{{ route('about') }}" class="footer-link">Quiénes Somos</a></li>
                    <li><a href="{{ route('contact.index') }}" class="footer-link">Contacto</a></li>
                    <li><a href="{{ route('recipes') }}" class="footer-link">Servicios</a></li>
                </ul>
            </div>

            <!-- Categorías de Productos -->
            <div class="col-lg-2 col-md-6 mb-4">
                <h5 class="text-uppercase fw-semibold mb-3">Categorías</h5>
                <ul class="list-unstyled footer-links">
                    <li><a href="{{ route('shop.index', ['category' => 'licuadoras']) }}" class="footer-link">Licuadoras</a></li>
                    <li><a href="{{ route('shop.index', ['category' => 'freidoras']) }}" class="footer-link">Freidoras de Aire</a></li>
                    <li><a href="{{ route('shop.index', ['category' => 'sanducheras']) }}" class="footer-link">Sanducheras</a></li>
                    <li><a href="{{ route('shop.index', ['category' => 'pequenos']) }}" class="footer-link">Pequeños Electrodomésticos</a></li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div class="col-lg-4 col-md-6 mb-4">
                <h5 class="text-uppercase fw-semibold mb-3">Información de Contacto</h5>
                
                <div class="contact-info">
                    <p class="text-white small mb-2">
                        <i class="fas fa-envelope me-2 text-info"></i>
                        <a href="mailto:info@electrahome.com" class="footer-link">info@electrahome.com</a>
                    </p>
                    <p class="text-white small mb-2">
                        <i class="fas fa-phone me-2 text-info"></i>
                        <a href="tel:+584121234567" class="footer-link">+58 (412) 123-4567</a>
                    </p>
                    <p class="text-white small mb-3">
                        <i class="fas fa-map-marker-alt me-2 text-info"></i>
                        <span class="text-white"></span>
                    </p>
                </div>

                <!-- Horarios de Atención -->
                <div class="business-hours">
                    <h6 class="text-white mb-2">
                        <i class="fas fa-clock me-2 text-warning"></i>
                        Horarios de Atención
                    </h6>
                    <p class="text-white small mb-1">Lunes a Viernes: 8:00 AM - 6:00 PM</p>
                    <p class="text-white small mb-1">Sábados: 8:00 AM - 4:00 PM</p>
                    <p class="text-white small">Domingos: Cerrado</p>
                </div>
            </div>
        </div>

        <!-- Divider -->
        <hr class="border-light my-4 opacity-25">

        <!-- Bottom Footer -->
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="text-white small">
                    &copy; {{ date('Y') }} {{ env('APP_NAME', 'CaballosApp') }}. Todos los derechos reservados.
                </div>
            </div>
            <div class="col-md-6">
                <div class="footer-certifications text-md-end">
                    <span class="certification-badge me-2">
                        <i class="fas fa-shield-check me-1"></i>
                        <small>Garantía Oficial</small>
                    </span>
                    <span class="certification-badge me-2">
                        <i class="fas fa-tools me-1"></i>
                        <small>Servicio Técnico</small>
                    </span>
                    <span class="certification-badge">
                        <i class="fas fa-star me-1"></i>
                        <small>Calidad Certificada</small>
                    </span>
                </div>
            </div>
        </div>
    </div>
</footer>

<script>

     function toggleMobileMenu() {
            const mobileNav = document.getElementById('mobileNav');
            const menuIcon = document.getElementById('menuIcon');
            
            if (mobileNav.classList.contains('active')) {
                mobileNav.classList.remove('active');
                menuIcon.classList.remove('fa-times');
                menuIcon.classList.add('fa-bars');
                document.body.style.overflow = 'auto';
            } else {
                mobileNav.classList.add('active');
                menuIcon.classList.remove('fa-bars');
                menuIcon.classList.add('fa-times');
                document.body.style.overflow = 'hidden';
            }
        }

        // Cerrar menú móvil al hacer click en un enlace
        document.querySelectorAll('.mobile-nav-links a').forEach(link => {
            link.addEventListener('click', () => {
                toggleMobileMenu();
            });
        });

        // Cerrar menú móvil al cambiar tamaño de pantalla
        window.addEventListener('resize', () => {
            if (window.innerWidth > 768) {
                const mobileNav = document.getElementById('mobileNav');
                const menuIcon = document.getElementById('menuIcon');
                
                mobileNav.classList.remove('active');
                menuIcon.classList.remove('fa-times');
                menuIcon.classList.add('fa-bars');
                document.body.style.overflow = 'auto';
            }
        });
let currentImages = [];
let currentImageIndex = 0;

document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('quickViewModal');
    
    if (modal) {
        modal.addEventListener('show.bs.modal', function (event) {
            const trigger = event.relatedTarget;
            
            if (trigger) {
                const name = trigger.getAttribute('data-name');
                const desc = trigger.getAttribute('data-desc');
                const price = trigger.getAttribute('data-price');
                const weight = trigger.getAttribute('data-weight');
                const stock = trigger.getAttribute('data-stock');
                const category = trigger.getAttribute('data-category');
                const imagesData = trigger.getAttribute('data-images');
                const id = trigger.getAttribute('data-id');
                
                // Procesar imágenes
                try {
                    currentImages = JSON.parse(imagesData) || [];
                } catch (e) {
                    currentImages = [];
                }
                
                // Si no hay imágenes, usar placeholder
                if (currentImages.length === 0) {
                    currentImages = ["{{ asset('images/placeholder.png') }}"];
                }
                
                currentImageIndex = 0;
                setupImageGallery();
                
                // ===== RESTO DE VALIDACIONES (sin cambios) =====
                // Nombre
                const nameElement = document.getElementById('quickViewName');
                if (name && name.trim() !== '') {
                    nameElement.textContent = name;
                    nameElement.style.color = 'var(--accent)';
                } else {
                    nameElement.textContent = 'Product name not available';
                    nameElement.style.color = '#888';
                }
                
                // Descripción
                const descContainer = document.getElementById('quickViewDescriptionContainer');
                const descElement = document.getElementById('quickViewDescription');
                if (desc && desc.trim() !== '' && desc !== 'null') {
                    descElement.textContent = desc;
                    descContainer.style.display = 'block';
                } else {
                    descContainer.style.display = 'none';
                }
                
                // Precio
                const priceElement = document.getElementById('quickViewPrice');
                if (price && price !== '0' && price !== '' && price !== 'null') {
                    priceElement.textContent = `$${price}`;
                    priceElement.style.color = 'var(--highlight)';
                } else {
                    priceElement.textContent = 'Price not set';
                    priceElement.style.color = '#888';
                }
                
                // Stock
                const stockElement = document.getElementById('quickViewStock');
                const addToCartBtn = document.getElementById('addToCartBtn');
                
                if (stock !== null && stock !== '' && stock !== 'null') {
                    const stockValue = parseInt(stock) || 0;
                    
                    if (stockValue === 0) {
                        stockElement.textContent = 'Out of Stock';
                        stockElement.style.color = '#dc3545';
                        addToCartBtn.disabled = true;
                        addToCartBtn.innerHTML = '<i class="fas fa-times me-2"></i>Out of Stock';
                        addToCartBtn.style.backgroundColor = '#6c757d';
                    } else if (stockValue <= 5) {
                        stockElement.textContent = `${stockValue} units`;
                        stockElement.style.color = '#ffc107';
                        addToCartBtn.disabled = false;
                        addToCartBtn.innerHTML = '<i class="fas fa-shopping-cart me-2"></i>Add to Cart';
                        addToCartBtn.style.backgroundColor = 'var(--highlight)';
                    } else {
                        stockElement.textContent = `${stockValue} units`;
                        stockElement.style.color = 'var(--accent)';
                        addToCartBtn.disabled = false;
                        addToCartBtn.innerHTML = '<i class="fas fa-shopping-cart me-2"></i>Add to Cart';
                        addToCartBtn.style.backgroundColor = 'var(--highlight)';
                    }
                } else {
                    stockElement.textContent = 'Stock unknown';
                    stockElement.style.color = '#888';
                }
                
                // Peso
                const weightContainer = document.getElementById('weightContainer');
                const weightElement = document.getElementById('quickViewWeight');
                
                if (weight && weight.trim() !== '' && weight !== 'null') {
                    weightElement.textContent = weight;
                    weightContainer.style.display = 'block';
                } else {
                    weightContainer.style.display = 'none';
                }
                
                // Categoría
                const categoryBadge = document.getElementById('quickViewCategoryBadge');
                if (category && category.trim() !== '' && category !== 'N/A' && category !== 'null') {
                    categoryBadge.textContent = category;
                    categoryBadge.style.display = 'inline-block';
                } else {
                    categoryBadge.style.display = 'none';
                }
                
                // ID del producto
                const productIdElement = document.getElementById('quickViewProductId');
                if (id && id !== '' && id !== 'null') {
                    productIdElement.value = id;
                }
            }
        });
    }
});

function setupImageGallery() {
    const mainImage = document.getElementById('quickViewMainImage');
    const imageCounter = document.getElementById('imageCounter');
    const thumbnailContainer = document.getElementById('thumbnailContainer');
    const thumbnailImages = document.getElementById('thumbnailImages');
    const prevBtn = document.getElementById('prevImageBtn');
    const nextBtn = document.getElementById('nextImageBtn');
    
    // Mostrar imagen principal
    mainImage.src = currentImages[currentImageIndex];
    
    // Configurar contador y controles
    if (currentImages.length > 1) {
        imageCounter.textContent = `${currentImageIndex + 1} / ${currentImages.length}`;
        imageCounter.style.display = 'block';
        prevBtn.style.display = 'block';
        nextBtn.style.display = 'block';
        
        // Crear thumbnails
        thumbnailImages.innerHTML = '';
        currentImages.forEach((imageUrl, index) => {
            const thumbnail = document.createElement('img');
            thumbnail.src = imageUrl;
            thumbnail.className = 'img-thumbnail';
            thumbnail.style.cssText = `
                width: 60px; 
                height: 60px; 
                object-fit: cover; 
                cursor: pointer; 
                border: 2px solid ${index === currentImageIndex ? 'var(--highlight)' : 'var(--accent)'};
                opacity: ${index === currentImageIndex ? '1' : '0.7'};
                transition: all 0.3s ease;
            `;
            
            thumbnail.addEventListener('click', () => {
                currentImageIndex = index;
                setupImageGallery();
            });
            
            thumbnail.addEventListener('mouseenter', () => {
                if (index !== currentImageIndex) {
                    thumbnail.style.opacity = '1';
                }
            });
            
            thumbnail.addEventListener('mouseleave', () => {
                if (index !== currentImageIndex) {
                    thumbnail.style.opacity = '0.7';
                }
            });
            
            thumbnailImages.appendChild(thumbnail);
        });
        
        thumbnailContainer.style.display = 'block';
    } else {
        imageCounter.style.display = 'none';
        prevBtn.style.display = 'none';
        nextBtn.style.display = 'none';
        thumbnailContainer.style.display = 'none';
    }
}

function changeImage(direction) {
    currentImageIndex += direction;
    
    if (currentImageIndex >= currentImages.length) {
        currentImageIndex = 0;
    } else if (currentImageIndex < 0) {
        currentImageIndex = currentImages.length - 1;
    }
    
    setupImageGallery();
}

// Navegación con teclado (opcional)
document.addEventListener('keydown', function(e) {
    const modal = document.getElementById('quickViewModal');
    if (modal.classList.contains('show')) {
        if (e.key === 'ArrowLeft') {
            changeImage(-1);
        } else if (e.key === 'ArrowRight') {
            changeImage(1);
        }
    }
});
</script>
</body>

</html>
