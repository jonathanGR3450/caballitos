@extends('layouts.app')

@section('content')

<link rel="stylesheet" href="{{ asset('css/productos.css') }}">

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="" crossorigin="anonymous"></script>


{{-- <!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap Bundle con Popper (NECESARIO para el carrusel) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> --}}


<div class="electrahome-carousel-section">
    <div id="categoryCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            @foreach($categories as $index => $category)
                <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                    <div class="category-hero-slide">
                        <div class="slide-background">
                            <img src="{{ $category->image ? Storage::url($category->image) : asset('images/category-placeholder.png') }}"
                                 class="slide-bg-image" alt="{{ $category->name }}">
                            <div class="slide-overlay"></div>
                        </div>
                        <div class="slide-content">
                            <div class="container">
                                <div class="row align-items-center">
                                    <div class="col-lg-6">
                                        <div class="slide-text">
                                            <div class="category-label">{{ $category->products_count }} Productos Disponibles</div>
                                            <h1 class="slide-title">{{ strtoupper($category->name) }}</h1>
                                            <p class="slide-description">
                                                Descubre la mejor calidad en {{ strtolower($category->name) }} con garantía oficial. 
                                                Equipos duraderos y eficientes diseñados para hacer tu vida más fácil.
                                            </p>
                                            <a href="{{ route('shop.index', ['category' => $category->id]) }}" class="shop-category-btn">
                                                VER {{ strtoupper($category->name) }}
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 d-none d-lg-block">
                                        <div class="slide-image">
                                            <img src="{{ $category->image ? Storage::url($category->image) : asset('images/category-placeholder.png') }}"
                                                 alt="{{ $category->name }}" class="category-product-image">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Indicadores -->
        <div class="carousel-indicators">
            @foreach($categories as $index => $category)
                <button type="button" data-bs-target="#categoryCarousel" data-bs-slide-to="{{ $index }}" 
                        class="{{ $index == 0 ? 'active' : '' }}"></button>
            @endforeach
        </div>
        
        <!-- Controles -->
        <button class="carousel-control-prev" type="button" data-bs-target="#categoryCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Anterior</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#categoryCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Siguiente</span>
        </button>
    </div>
</div>

<div class="catalog-section">
    <div class="container">
        <h1 class="catalog-title">Nuestros Productos</h1>

        <!-- Filtros Arreglados -->
        <div class="filter-bar">
            <form method="GET" action="{{ route('shop.index') }}" class="d-flex gap-3 align-items-center flex-wrap justify-content-center">

                {{-- Categoría --}}
                <div class="filter-group">
                    <label class="filter-label">Categoría:</label>
                    <select name="category" class="filter-select" onchange="this.form.submit()">
                        <option value="">Todas</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }} ({{ $cat->products_count }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- País --}}
                @if($countries && $countries->count() > 0)
                <div class="filter-group">
                    <label class="filter-label">País:</label>
                    <select name="country" class="filter-select" onchange="this.form.submit()">
                        <option value="">Todos</option>
                        @foreach($countries as $country)
                            <option value="{{ $country }}" {{ request('country') == $country ? 'selected' : '' }}>
                                {{ $country }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif

                {{-- 🔎 Búsqueda --}}
                <div class="filter-group">
                    <label class="filter-label">Buscar:</label>
                    <input type="text" 
                        name="search" 
                        class="filter-select" 
                        placeholder="Buscar productos..."
                        value="{{ request('search') }}"
                        style="min-width: 200px;">
                </div>

                {{-- filtro por tipo listado --}}
                <div class="filter-group">
                    <label class="filter-label">Tipo de Listado:</label>
                    <select name="tipo_listado_id" class="form-control border-secondary">
                        <option value="">Todos</option>
                        @foreach($tipoListados as $listado)
                            <option value="{{ $listado->id }}"
                                {{ (string)request('tipo_listado_id') === (string)$listado->id ? 'selected' : '' }}>
                                {{ $listado->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- filtro por estado --}}
                <div class="filter-group">
                    <label class="filter-label">Estado:</label>
                    <select name="estado" class="filter-select">
                        <option value="">Todos</option>
                        @foreach(\App\Models\Product::getEstados() as $estado)
                            <option value="{{ $estado }}" {{ request('estado') == $estado ? 'selected' : '' }}>
                                {{ $estado }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- 📊 Rango de Precios --}}
                <div class="filter-group">
                    <label class="filter-label">Precio:</label>
                    <input type="number" name="price_min" class="filter-select" style="width: 90px;"
                        placeholder="Mín"
                        value="{{ request('price_min') }}">
                    -
                    <input type="number" name="price_max" class="filter-select" style="width: 90px;"
                        placeholder="Máx"
                        value="{{ request('price_max') }}">
                </div>

                {{-- 🐶 Campos Extras --}}
                <div class="filter-group">
                    <label class="filter-label">Ubicación:</label>
                    <input type="text" name="ubicacion" class="filter-select"
                        value="{{ request('ubicacion') }}" placeholder="Ej: Madrid">
                </div>

                <div class="filter-group">
                    <label class="filter-label">Raza:</label>
                    <input type="text" name="raza" class="filter-select"
                        value="{{ request('raza') }}" placeholder="Ej: Labrador">
                </div>

                <div class="filter-group">
                    <label class="filter-label">Edad:</label>
                    <input type="text" name="edad" class="filter-select"
                        value="{{ request('edad') }}" placeholder="Ej: 2 años">
                </div>

                <div class="filter-group">
                    <label class="filter-label">Género:</label>
                    <select name="genero" class="filter-select">
                        <option value="">Todos</option>
                        <option value="Macho" {{ request('genero') == 'Macho' ? 'selected' : '' }}>Macho</option>
                        <option value="Hembra" {{ request('genero') == 'Hembra' ? 'selected' : '' }}>Hembra</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label class="filter-label">Pedigrí:</label>
                    <select name="pedigri" class="filter-select">
                        <option value="">Todos</option>
                        <option value="Sí" {{ request('pedigri') == 'Sí' ? 'selected' : '' }}>Sí</option>
                        <option value="No" {{ request('pedigri') == 'No' ? 'selected' : '' }}>No</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label class="filter-label">Entrenamiento:</label>
                    <input type="text" name="entrenamiento" class="filter-select"
                        value="{{ request('entrenamiento') }}" placeholder="Ej: Básico">
                </div>

                <div class="filter-group">
                    <label class="filter-label">Historial Salud:</label>
                    <input type="text" name="historial_salud" class="filter-select"
                        value="{{ request('historial_salud') }}" placeholder="Ej: Vacunado">
                </div>

                {{-- Ordenar --}}
                <div class="filter-group">
                    <label class="filter-label">Ordenar por:</label>
                    <select name="sort" class="filter-select" onchange="this.form.submit()">
                        <option value="">Destacados</option>
                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Precio: Menor a Mayor</option>
                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Precio: Mayor a Menor</option>
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nombre A-Z</option>
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Más Nuevos</option>
                    </select>
                </div>

                {{-- Botón Buscar --}}
                <div class="filter-group">
                    <button type="submit" class="btn-solid" style="min-width: auto; padding: 10px 20px;">
                        <i class="fas fa-search"></i> Buscar
                    </button>
                </div>

                {{-- Limpiar --}}
                @if(request()->hasAny(['category','country','search','sort','price_min','price_max','ubicacion','raza','edad','genero','pedigri','entrenamiento','historial_salud']))
                <div class="filter-group">
                    <a href="{{ route('shop.index') }}" class="btn-ghost" style="min-width: auto; padding: 10px 20px;">
                        <i class="fas fa-times"></i> Limpiar
                    </a>
                </div>
                @endif

                <div class="filter-group">
                    <span class="filter-label">
                        <strong>{{ isset($totalProducts) ? $totalProducts : $products->total() }}</strong> productos
                        @if(isset($hasFilters) && $hasFilters)
                            <small style="color: #DEB887;">(filtrados)</small>
                        @endif
                    </span>
                </div>
            </form>
        </div>

        <!-- Grid de Productos -->
        <div class="products-grid">
            @forelse ($products as $product)
                @php
                    // Estilos visuales según tipoListado
                    $cardStyle = match($product->tipoListado?->slug) {
                        'premium' => 'background: #fff8e1; box-shadow: 0 4px 15px rgba(255, 193, 7, 0.6); border: 2px solid #ffc107;',
                        'destacado' => 'background: #e3f2fd; box-shadow: 0 4px 15px rgba(13, 110, 253, 0.6); border: 2px solid #0d6efd;',
                        default => 'background: #ffffff; box-shadow: 0 2px 5px rgba(0,0,0,0.1); border: 1px solid #ddd;',
                    };

                    $badgeClass = match($product->tipoListado?->slug) {
                        'premium' => 'badge bg-warning text-dark',
                        'destacado' => 'badge bg-primary',
                        default => 'badge bg-secondary',
                    };

                    $totalPrice = (float)($product->price ?? 0) + (float)($product->interest ?? 0);

                    $estadoColor = match(strtolower($product->estado)) {
                        'disponible' => 'color: green; font-weight: bold;',
                        'vendido' => 'color: red; font-weight: bold;',
                        'pendiente' => 'color: orange; font-weight: bold;',
                        default => 'color: gray; font-weight: bold;',
                    };
                @endphp

                <div class="product-card" style="{{ $cardStyle }}">
                    {{-- MEDIA --}}
                    <div class="pc-media">
                        @php $imgs = $product->images; @endphp
                        @if($imgs->count() > 1)
                            <div id="productCarousel-{{ $product->id }}" class="carousel slide product-carousel" data-bs-ride="carousel" data-bs-interval="3000">
                                <div class="carousel-inner">
                                    @foreach($imgs as $k => $img)
                                        <div class="carousel-item {{ $k === 0 ? 'active' : '' }}">
                                            <img src="{{ Storage::url($img->image) }}"
                                                class="pc-img"
                                                alt="{{ $product->name }}"
                                                loading="lazy"
                                                onerror="this.src='{{ asset('images/placeholder.png') }}'">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <a href="{{ route('product.show', $product) }}" class="pc-media-link">
                                <img src="{{ $imgs->first()?->image ? Storage::url($imgs->first()->image) : asset('images/placeholder.png') }}"
                                    class="pc-img"
                                    alt="{{ $product->name }}"
                                    loading="lazy">
                            </a>
                        @endif

                        <div class="pc-fav">
                            @auth
                                @php $isFav = in_array($product->id, $favoritesIds ?? []); @endphp
                                <form action="{{ route('favorites.toggle', $product) }}"
                                    method="POST"
                                    class="fav-toggle d-inline"
                                    data-product-id="{{ $product->id }}">
                                    @csrf
                                    <button type="submit"
                                            class="btn btn-sm {{ $isFav ? 'btn-danger' : 'btn-outline-danger' }}"
                                            aria-pressed="{{ $isFav ? 'true' : 'false' }}"
                                            aria-label="{{ $isFav ? 'Quitar de favoritos' : 'Agregar a favoritos' }}">
                                        <i class="{{ $isFav ? 'fas' : 'far' }} fa-heart"></i>
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-sm btn-outline-danger" title="Inicia sesión para guardar favoritos">
                                    <i class="far fa-heart"></i>
                                </a>
                            @endauth
                        </div>

                        @php
                            $compareIds = session('compare.products', []);
                            $inCompare = in_array($product->id, $compareIds);
                        @endphp
                        <div class="pc-fav" style="position:absolute;top:10px;right:50px;z-index:10;">
                            <form action="{{ route('compare.toggle', $product) }}"
                                method="POST"
                                class="compare-toggle d-inline"
                                data-product-id="{{ $product->id }}">
                                @csrf
                                <button type="submit"
                                        class="btn btn-sm {{ $inCompare ? 'btn-primary' : 'btn-outline-primary' }}"
                                        aria-pressed="{{ $inCompare ? 'true' : 'false' }}"
                                        aria-label="{{ $inCompare ? 'Quitar de comparar' : 'Agregar a comparar' }}">
                                    <i class="fas fa-balance-scale"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- BODY --}}
                    <div class="pc-body">
                        {{-- Badge tipo listado --}}
                        <span class="{{ $badgeClass }}">
                            {{ ucfirst($product->tipoListado?->nombre) }}
                        </span>

                        <div class="pc-category">{{ $product->category->name ?? 'Sin categoría' }}</div>

                        <h3 class="pc-title">
                            <a href="{{ route('product.show', $product) }}">{{ $product->name }}</a>
                        </h3>
                        
                        @isset($product->user_id)
                            <h3 class="pc-title">
                                <a href="{{ route('vendedor.perfil', $product->user) }}">Vendedor: {{ $product->user->name }}</a>
                            </h3>
                        @endisset

                        <div class="mb-2">
                            <strong>Estado:</strong>
                            <span style="{{ $estadoColor }}">{{ ucfirst($product->estado) }}</span>
                        </div>

                        <div class="mb-2">
                            <strong>Listado:</strong>
                            <span >{{ ucfirst($product->tipoListado?->nombre) }}</span>
                        </div>

                        <div class="pc-price-row">
                            <div class="pc-price">${{ number_format($totalPrice, 0, ',', '.') }}</div>
                            <div class="pc-weight">c/u</div>
                        </div>
                    </div>

                    {{-- ACTIONS --}}
                    <div class="pc-actions">
                        <a href="{{ route('product.show', $product) }}" class="btn-ghost">
                            <i class="fas fa-eye"></i> <span>Ver Detalles</span>
                        </a>
                        <form action="{{ route('cart.add') }}" method="POST" class="d-inline-block">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <button type="submit" class="btn-solid" {{ $product->stock <= 0 ? 'disabled' : '' }}>
                                <i class="fas fa-shopping-cart"></i> 
                                <span>{{ $product->stock <= 0 ? 'Agotado' : 'Agregar' }}</span>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="no-products">
                        <i class="fas fa-search mb-3" style="font-size: 3rem; color: #DEB887;"></i>
                        <h3>No se encontraron productos</h3>
                        <p>No hay productos disponibles con los filtros seleccionados.</p>
                        <a href="{{ route('shop.index') }}" class="btn-solid mt-3">
                            <i class="fas fa-refresh me-2"></i>Ver Todos los Productos
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        @if(isset($products) && $products->hasPages())
            <div class="pagination-wrapper">
                {{ $products->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        document.querySelectorAll('form.fav-toggle').forEach(form => {
            form.addEventListener('submit', async (e) => {
            e.preventDefault();

            const btn = form.querySelector('button[type="submit"]');
            if (!btn) return;

            btn.disabled = true;

            try {
                const res = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                });

                if (!res.ok) {
                // fallback: sin JSON (por si algo falla), hacemos submit normal
                form.submit();
                return;
                }

                const data = await res.json();
                // Actualiza botón
                const icon = btn.querySelector('i');
                const isAdded = data.status === 'added';

                btn.classList.toggle('btn-danger', isAdded);
                btn.classList.toggle('btn-outline-danger', !isAdded);
                btn.setAttribute('aria-pressed', isAdded ? 'true' : 'false');
                btn.setAttribute('aria-label', isAdded ? 'Quitar de favoritos' : 'Agregar a favoritos');

                if (icon) {
                icon.classList.toggle('fas', isAdded);
                icon.classList.toggle('far', !isAdded);
                }

                // Actualiza badge del navbar si tienes uno
                const navBadge = document.querySelector('[href="{{ route('favorites.index') }}"] .badge');
                if (navBadge) {
                navBadge.textContent = (data.count > 99) ? '99+' : data.count;
                if (data.count > 0) {
                    navBadge.classList.remove('d-none');
                } else {
                    navBadge.classList.add('d-none');
                }
                }
            } catch (err) {
                console.error(err);
                form.submit(); // fallback
            } finally {
                btn.disabled = false;
            }
            }, { passive: false });
        });
    });

    // Inicializar carrusel cuando el DOM esté listo
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar carruseles de productos
        document.querySelectorAll('.product-carousel').forEach(el => {
            new bootstrap.Carousel(el, {
                interval: 3000,
                ride: 'carousel',
                pause: 'hover',
                touch: true,
                wrap: true
            });
        });

        // Inicializar carrusel principal
        if (typeof bootstrap !== 'undefined') {
            const carouselElement = document.getElementById('categoryCarousel');
            if (carouselElement) {
                const carousel = new bootstrap.Carousel(carouselElement, {
                    interval: 5000,
                    ride: 'carousel',
                    wrap: true,
                    touch: true
                });
                
                console.log('Carrusel principal inicializado correctamente');
            }
        } else {
            console.warn('Bootstrap no está cargado. Verifica que Bootstrap JS esté incluido en tu layout.');
        }

        // Manejo de errores de imágenes
        const images = document.querySelectorAll('.slide-bg-image, .category-product-image, .pc-img');
        images.forEach(function(img) {
            img.addEventListener('error', function() {
                console.log('Error cargando imagen:', this.src);
                this.src = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAwIiBoZWlnaHQ9IjMwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjMDBBOUUwIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiIgZm9udC1zaXplPSIxOCIgZmlsbD0iI2ZmZiIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZHk9Ii4zZW0iPkltYWdlbiBubyBkaXNwb25pYmxlPC90ZXh0Pjwvc3ZnPg==';
            });
        });
    });

    // Función para reinicializar carrusel si es necesario
    function reinitializeCarousel() {
        const carouselElement = document.getElementById('categoryCarousel');
        if (carouselElement && typeof bootstrap !== 'undefined') {
            const existingCarousel = bootstrap.Carousel.getInstance(carouselElement);
            if (existingCarousel) {
                existingCarousel.dispose();
            }
            
            const newCarousel = new bootstrap.Carousel(carouselElement, {
                interval: 5000,
                ride: 'carousel',
                wrap: true,
                touch: true
            });
            
            console.log('Carrusel reinicializado');
            return newCarousel;
        }
        return null;
    }

    window.reinitializeCarousel = reinitializeCarousel;
</script>

@endsection