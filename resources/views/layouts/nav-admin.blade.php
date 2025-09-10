<nav>
    <div class="navbar-container">
        <div class="logo">
                <a href="{{ route('home') }}" class="d-inline-block" aria-label="Ir al inicio">
                    <img src="{{ asset('images/logo.png') }}" alt="{{ env('APP_NAME', 'CaballosApp') }} Logo" style="height: 70px;">
                </a>
        </div>

        <!-- Desktop Navigation -->
        <div class="nav-links">
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
            <a href="{{ route('admin.index') }}" class="{{ request()->routeIs('admin.pages.index') ? 'active' : '' }}">Páginas</a>
            <a href="{{ route('admin.countries.index') }}" class="{{ request()->routeIs('admin.countries.index') ? 'active' : '' }}">Paises</a>
            <a href="{{ route('admin.cities.index') }}" class="{{ request()->routeIs('admin.cities.index') ? 'active' : '' }}">Ciudades</a>
            <a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.index') ? 'active' : '' }}">Categorías</a>
            <a href="{{ route('admin.products.index') }}" class="{{ request()->routeIs('admin.products.index') ? 'active' : '' }}">Productos</a>

            <a href="{{ route('admin.roles.index') }}" class="{{ request()->routeIs('admin.roles.index') ? 'active' : '' }}">Roles</a>
            <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.index') ? 'active' : '' }}">Usuarios</a>
        </div>

        <!-- Desktop Icons -->
        <div class="nav-icons">
            @auth
            <div class="dropdown">
                <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown" title="Mi cuenta">
                    <i class="fas fa-user-circle"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    
                    <li><hr class="dropdown-divider"></li>
                    {{-- profile --}}
                    <li>
                        <a class="dropdown-item" href="{{ route('profile.edit') }}">
                            <i class="fas fa-user-edit me-2"></i>Perfil
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('logout') }}" 
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión
                        </a>
                    </li>
                </ul>
                
                <!-- Formulario oculto para logout -->
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
            @else
            <a href="{{ route('login') }}" title="Iniciar sesión">
                <i class="fas fa-user"></i>
            </a>
            @endauth

        </div>

        <!-- Mobile Menu Toggle -->
        <button class="mobile-menu-toggle" onclick="toggleMobileMenu()">
            <i class="fas fa-bars" id="menuIcon"></i>
        </button>
    </div>
</nav>