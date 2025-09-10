@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Imágenes del producto -->
        <div class="col-md-6">
            <div class="product-images">
                @if($product->images->count() > 0)
                <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">

                    <!-- Indicadores tipo puntos -->
                    <div class="carousel-indicators" style="bottom: 10px;">
                        @foreach($product->images as $index => $image)
                        <button type="button" data-bs-target="#productCarousel" data-bs-slide-to="{{ $index }}" class="{{ $index === 0 ? 'active' : '' }}" aria-current="{{ $index === 0 ? 'true' : 'false' }}" aria-label="Slide {{ $index + 1 }}">
                        </button>
                        @endforeach
                    </div>

                    <!-- Slides -->
                    <div class="carousel-inner rounded" style="max-height: 500px; overflow: hidden;">
                        @foreach($product->images as $index => $image)
                        <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                            <img src="{{ Storage::url($image->image) }}" class="d-block w-100" alt="{{ $product->name }}" style="height: 500px; object-fit: cover;">
                        </div>
                        @endforeach
                    </div>

                    <!-- Controles -->
                    <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>
                </div>
                @else
                <img src="{{ asset('images/placeholder.png') }}" class="img-fluid rounded" alt="{{ $product->name }}" style="width: 100%; height: 500px; object-fit: cover;">
                @endif
            </div>
        </div>


        <!-- Información del producto -->
        <div class="col-md-6">
            <div class="product-info">
                <h1 class="mb-3" style="font-family: 'Georgia', serif;">{{ $product->name }}</h1>

                <div class="price-section mb-4">
                    @php
                    $totalPrice = ($product->price ?? 0) + ($product->interest ?? 0);
                    @endphp
                    <h3 class="fw-bold">${{ number_format($totalPrice, 0, ',', '.') }}</h3>
                    <small class="text-white">/ {{ $product->avg_weight ?: 'per lb' }}</small>
                </div>

                <div class="stock-info mb-4">
                    @if($product->stock <= 0) <span class="badge bg-danger fs-6">Out of Stock</span>
                        @elseif($product->stock <= 5) <span class="badge bg-warning text-dark fs-6">Limited Stock ({{ $product->stock }} left)</span>
                            @else
                            <span class="badge bg-success fs-6">In Stock ({{ $product->stock }} available)</span>
                            @endif
                </div>

                <div class="description mb-4">
                    <h5>Description</h5>
                    <div class="text-white">{!! $product->description !!}</div>
                </div>


                <!-- Formulario para agregar al carrito -->
                @if($product->stock > 0)
                    <form action="{{ route('cart.add') }}" method="POST" class="mb-4">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">

                        <div class="row g-3">
                            <div class="col-4">
                                <label for="quantity" class="form-label">Quantity</label>
                                <input type="number" class="form-control" id="quantity" name="quantity" value="1" min="1" max="{{ $product->stock }}">
                            </div>
                            <div class="col-8 d-flex align-items-end">
                                <button type="submit" class="btn btn-success btn-lg w-100">
                                    <i class="fas fa-shopping-cart"></i> Add to Cart
                                </button>
                            </div>
                        </div>
                    </form>
                @endif



                <!-- Botones adicionales -->
                <div class="d-flex gap-2 mb-4">
                    <a href="{{ route('shop.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Shop
                    </a>

                    @auth
                        @php
                            // En detalle no pasa nada si consultamos 1 vez a DB
                            $isFav = auth()->user()
                                ->favoriteProducts()
                                ->where('products.id', $product->id)
                                ->exists();
                        @endphp

                        <form id="favForm" action="{{ route('favorites.toggle', $product) }}" method="POST" class="d-inline">
                            @csrf
                            <button id="favBtn" type="submit"
                                    class="btn {{ $isFav ? 'btn-danger' : 'btn-outline-danger' }}"
                                    aria-pressed="{{ $isFav ? 'true' : 'false' }}"
                                    aria-label="{{ $isFav ? 'Quitar de favoritos' : 'Agregar a favoritos' }}">
                                <i class="{{ $isFav ? 'fas' : 'far' }} fa-heart"></i>
                                <span class="fav-label">{{ $isFav ? 'Remove to Wishlist' : 'Add to Wishlist' }}</span>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-danger"
                        title="Inicia sesión para guardar favoritos">
                            <i class="far fa-heart"></i> Add to Wishlist
                        </a>
                    @endauth
                </div>

                <!-- Información adicional -->
                <div class="product-details">
                    <h6>Product Details</h6>
                    <ul class="list-unstyled">
                        <li><strong>SKU:</strong> #{{ $product->id }}</li>
                        <li><strong>Category:</strong> {{ $product->category->name ?? 'N/A' }}</li>
                        {{-- <li><strong>Weight:</strong> Sold per lb</li> --}}
                        <li><strong>Estado:</strong> {{ $product->estado ?? 'N/A' }}</li>
                        <li><strong>Listado:</strong> {{ $product->tipo_listado ?? 'N/A' }}</li>
                    </ul>
                </div>

                @if($product->extra)
                    <div class="product-extra mt-3">
                        <h6>Extra Details</h6>
                        <ul class="list-unstyled">
                            <li><strong>Ubicación:</strong> {{ $product->extra->ubicacion ?? 'N/A' }}</li>
                            <li><strong>Raza:</strong> {{ $product->extra->raza ?? 'N/A' }}</li>
                            <li><strong>Edad:</strong> {{ $product->extra->edad . " años" ?? 'N/A' }}</li>
                            <li><strong>Género:</strong> {{ $product->extra->genero ?? 'N/A' }}</li>
                            <li><strong>Pedigrí:</strong> {{ $product->extra->pedigri ?? 'N/A' }}</li>
                            <li><strong>Entrenamiento:</strong> {{ $product->extra->entrenamiento ?? 'N/A' }}</li>
                            <li><strong>Historial de salud:</strong> {{ $product->extra->historial_salud ?? 'N/A' }}</li>
                        </ul>
                    </div>
                @endif
                <div class="mt-5">
                    <h4>Preguntas y Respuestas</h4>

                    {{-- Formulario de nueva pregunta --}}
                    <form action="{{ route('products.questions.store', $product) }}" method="POST" class="mb-4">
                        @csrf
                        <div class="mb-2">
                            <label for="user_name" class="form-label">Tu nombre</label>
                            <input type="text" name="user_name" class="form-control" required>
                        </div>
                        <div class="mb-2">
                            <label for="user_email" class="form-label">Tu correo (opcional)</label>
                            <input type="email" name="user_email" class="form-control">
                        </div>
                        <div class="mb-2">
                            <label for="question" class="form-label">Pregunta</label>
                            <textarea name="question" rows="3" class="form-control" required></textarea>
                        </div>
                        <button class="btn btn-primary">Enviar Pregunta</button>
                    </form>

                    {{-- Listado de preguntas --}}
                    @forelse($product->questions as $q)
                        <div class="border rounded p-3 mb-3">
                            <strong>{{ $q->user_name }}</strong> preguntó:
                            <p>{{ $q->question }}</p>

                            @if($q->answer)
                                <div class="bg-light p-2 rounded">
                                    <strong>Respuesta del administrador:</strong>
                                    <p>{{ $q->answer }}</p>
                                </div>
                            @else
                                <span class="text-muted">Aún sin respuesta</span>
                            @endif
                        </div>
                    @empty
                        <p>No hay preguntas aún. ¡Sé el primero en preguntar!</p>
                    @endforelse
                </div>

            </div>
        </div>
    </div>

    <!-- Productos relacionados -->


    <!-- Productos destacados/populares -->
    @if($featuredProducts->count() > 0)
    <div class="row mt-5">
        <div class="col-12">
            <h3 class="mb-4">
                <i class="fas fa-star text-warning"></i> You Might Also Like
                <small class="text-white fs-6">Popular products</small>
            </h3>
            <div class="row g-4">
                @foreach($featuredProducts as $featuredProduct)
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="position-relative" style="overflow: hidden;">
                            <img src="{{ $featuredProduct->images->first() ? Storage::url($featuredProduct->images->first()->image) : asset('images/placeholder.png') }}" class="card-img-top" alt="{{ $featuredProduct->name }}" style="height: 220px; object-fit: cover; transition: transform 0.3s ease;">

                            <!-- Badge de categoría -->
                            @if($featuredProduct->category)
                            <span class="position-absolute top-0 start-0 badge bg-dark m-2">
                                {{ $featuredProduct->category->name }}
                            </span>
                            @endif
                        </div>
                        <div class="card-body p-3">
                            <h6 class="card-title mb-2">{{ Str::limit($featuredProduct->name, 30) }}</h6>
                            <p class="card-text text-white small mb-2  ">
                                {{ Str::limit($featuredProduct->description, 60) }}
                            </p>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-white fw-bold">
                                    ${{ number_format(($featuredProduct->price ?? 0) + ($featuredProduct->interest ?? 0), 0, ',', '.') }}
                                </span>
                                <small class="text-white">/ per lb</small>
                            </div>
                            <div class="d-flex gap-1">
                                <a href="{{ route('product.show', $featuredProduct) }}" class="btn btn-outline-primary btn-sm flex-fill">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <form action="{{ route('cart.add') }}" method="POST" class="flex-fill">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $featuredProduct->id }}">
                                    <button type="submit" class="btn btn-success btn-sm w-100" {{ $featuredProduct->stock <= 0 ? 'disabled' : '' }}>
                                        <i class="fas fa-cart-plus"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Sección adicional: Últimos productos agregados -->
    <div class="row mt-5 mb-5">
        <div class="col-12">
            <div class="text-center">
                <h4 class="mb-3">Discover More Products</h4>
                <p class="text-white mb-4">Explore our complete collection of premium cuts</p>
                <a href="{{ route('shop.index') }}" class="btn btn-primary btn-lg px-5">
                    <i class="fas fa-store"></i> Browse All Products
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    .thumbnail-img:hover {
        opacity: 0.8;
        transition: opacity 0.3s ease;
    }

    .card-img-top:hover {
        transform: scale(1.05);
    }

    .carousel-indicators [data-bs-target] {
        background-color: white;
        width: 10px;
        height: 10px;
        border-radius: 50%;
    }

    .carousel-indicators .active {
        background-color: #CD853F;
        /* verde Bootstrap */
    }

    .btn-success {
        background-color: #DEB887 !important;
        border-color: #DEB887 !important;
    }

    .btn-success:hover {
        background-color: #f7a831 !important;
        border-color: #f7a831 !important;
        color: #101820 !important;
    }

</style>

<script>
    function toggleWishlist() {
        // Aquí puedes agregar la lógica para wishlist
        alert('Wishlist functionality - implement as needed');
    }

    // Cambiar imagen principal al hacer clic en thumbnails
    document.querySelectorAll('.thumbnail-img').forEach(thumb => {
        thumb.addEventListener('click', function() {
            const mainImg = document.querySelector('.main-image img');
            mainImg.src = this.src;
        });
    });

</script>
@endsection
