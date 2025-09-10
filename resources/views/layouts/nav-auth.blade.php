<nav>
        <div class="navbar-container">
            <!-- Logo -->
            <div class="footer-logo mb-3">
                <a href="{{ route('home') }}" class="d-inline-block" aria-label="Ir al inicio">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" style="height: 60px;">
                </a>
            </div>

            <!-- Desktop Navigation Links -->
            <div class="nav-links">
                <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">
                    Inicio
                </a>

                @role('comprador')
                    <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        Mis Compras
                    </a>
                @endrole

                @role('vendedor')
                    <a href="{{ route('vendedor.products.index') }}" class="{{ request()->routeIs('vendedor.products.index') ? 'active' : '' }}">
                        Cargar Productos
                    </a>
                @endrole

                <!-- Productos Dropdown -->
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
                            <!-- Categorías por defecto -->
                            <div class="category-item">
                                <a href="{{ route('shop.index') }}">Todos los productos</a>
                            </div>
                        @endforelse
                    </div>
                </div>

                <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">
                    Quiénes Somos
                </a>
                
                <a href="{{ route('contact.index') }}" class="{{ request()->routeIs('contact.index') ? 'active' : '' }}">
                    Contacto
                </a>
                
                <a href="{{ route('recipes') }}" class="{{ request()->routeIs('recipes') ? 'active' : '' }}">
                    Servicios
                </a>
            </div>

            <!-- Desktop Icons -->
            <div class="nav-icons">
                @auth
                    <!-- Usuario autenticado - Dropdown -->
                    <div class="dropdown">
                        <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown" title="Mi cuenta">
                            <i class="fas fa-user-circle"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="{{ route('dashboard') }}">
                                    <i class="fas fa-user me-2"></i>Mi Cuenta
                                </a>
                            </li>
                            
                            {{-- profile --}}
                            <li>
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    <i class="fas fa-user-edit me-2"></i>Perfil
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('cart.index') }}">
                                    <i class="fas fa-shopping-cart me-2"></i>Mi Carrito
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="{{ route('logout') }}" 
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión
                                </a>
                            </li>
                        </ul>
                    </div>
                @else
                    <!-- Usuario no autenticado -->
                    <a href="{{ route('login') }}" title="Iniciar sesión">
                        <i class="fas fa-user-circle"></i>
                    </a>
                @endauth
                    
                <!-- Carrito -->
                <a href="{{ route('cart.index') }}" title="Ver carrito">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="badge bg-secondary ms-1">{{ Cart::count() }}</span>
                </a>
            </div>

            <!-- Mobile Menu Toggle -->
            <button class="mobile-menu-toggle" onclick="toggleMobileMenu()" aria-label="Abrir menú móvil">
                <i class="fas fa-bars" id="menuIcon"></i>
            </button>
        </div>

        <!-- Mobile Navigation -->
        <div class="mobile-nav" id="mobileNav">
            <div class="mobile-nav-links">
                <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">
                    Inicio
                </a>

                @role('comprador')
                    <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        Mis Compras
                    </a>
                @endrole
                
                <!-- Productos en móvil con submenu -->
                <div class="mobile-nav-item">
                    <a href="#" onclick="toggleMobileSubmenu(event)">
                        Productos <i class="fas fa-chevron-down ms-2"></i>
                    </a>
                    <div class="mobile-submenu" id="mobileProductsSubmenu">
                        @forelse(($categories ?? collect())->unique(fn($c) => mb_strtolower(trim($c->name))) as $category)
                            <a href="{{ route('shop.index', ['category' => $category->id]) }}">
                                {{ $category->name }}
                            </a>
                        @empty
                            <a href="{{ route('shop.index') }}">Todos los productos</a>
                        @endforelse
                    </div>
                </div>
                
                <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">
                    Quiénes Somos
                </a>
                
                <a href="{{ route('contact.index') }}" class="{{ request()->routeIs('contact.index') ? 'active' : '' }}">
                    Contacto
                </a>
                
                <a href="{{ route('recipes') }}" class="{{ request()->routeIs('recipes') ? 'active' : '' }}">
                    Servicios
                </a>

                <!-- Enlaces móviles adicionales para usuario -->
                @auth
                    <div class="mobile-user-section">
                        <hr class="my-3">
                        <a href="{{ route('dashboard') }}">
                            <i class="fas fa-user me-2"></i>Mi Cuenta
                        </a>
                        <a href="{{ route('cart.index') }}">
                            <i class="fas fa-shopping-cart me-2"></i>Mi Carrito
                            <span class="badge bg-secondary ms-1">{{ Cart::count() }}</span>
                        </a>
                        <a href="{{ route('logout') }}" 
                        onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();">
                            <i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión
                        </a>
                    </div>
                @else
                    <div class="mobile-user-section">
                        <hr class="my-3">
                        <a href="{{ route('login') }}">
                            <i class="fas fa-user-circle me-2"></i>Iniciar Sesión
                        </a>
                        <a href="{{ route('cart.index') }}">
                            <i class="fas fa-shopping-cart me-2"></i>Ver Carrito
                            <span class="badge bg-secondary ms-1">{{ Cart::count() }}</span>
                        </a>
                    </div>
                @endauth
            </div>
        </div>

        <!-- Formularios ocultos para logout -->
        @auth
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
            <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        @endauth
    </nav>