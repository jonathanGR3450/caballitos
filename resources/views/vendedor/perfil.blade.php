@extends('layouts.app')

@section('title', 'Perfil del Vendedor - ' . env('APP_NAME'))

@section('content')
<style>
    body {
        background-color: #FAF9F6;
        color: #8B4513;
        font-family: 'Inter', Arial, sans-serif;
    }

    .catalog-section {
        min-height: 100vh;
        padding: 40px 0;
    }

    .catalog-title {
        color: #8B4513;
        font-size: 2.2rem;
        font-weight: 700;
        text-align: center;
        margin-bottom: 40px;
    }

    /* Filtro Simple */
    .filter-bar {
        background: linear-gradient(135deg, #FAF9F6 0%, #F8F6ED 100%);
        border: 2px solid #DEB887;
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 40px;
        display: flex;
        gap: 20px;
        align-items: center;
        justify-content: center;
        flex-wrap: wrap;
        box-shadow: 0 5px 20px #DEB887;
    }

    .filter-group {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .filter-label {
        color: #8B4513;
        font-size: 0.9rem;
        font-weight: 600;
    }

    .filter-select {
        background: #FAF9F6;
        border: 2px solid #DEB887;
        border-radius: 8px;
        color: #8B4513;
        padding: 10px 16px;
        font-size: 0.9rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        min-width: 140px;
    }

    .filter-select:focus {
        outline: none;
        border-color: #CD853F;
        box-shadow: 0 0 0 3px rgba(0, 207, 180, 0.2);
    }

    /* Grid de Productos */
    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 25px;
    }

    .product-card {
        display: flex;
        flex-direction: column;
        background: linear-gradient(145deg, #FAF9F6 0%, #F8F6ED 100%);
        border: 2px solid #DEB887;
        border-radius: 15px;
        overflow: hidden;
        min-height: 100%;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .product-card:hover {
        transform: translateY(-5px);
        border-color: #CD853F;
        box-shadow: 0 10px 30px #DEB887;
    }

    .pc-media {
        position: relative;
        overflow: hidden;
        background: #F8F6ED;
    }

    .pc-img {
        width: 100%;
        height: 250px;
        object-fit: cover;
        display: block;
        transition: transform 0.3s ease;
    }

    .product-card:hover .pc-img {
        transform: scale(1.05);
    }

    .product-carousel .carousel-item {
        transition: transform .6s ease;
    }

    /* BODY */
    .pc-body {
        padding: 20px;
        color: #8B4513;
        flex: 1 1 auto;
    }

    .pc-category {
        font-size: 0.8rem;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        color: #DEB887;
        margin-bottom: 8px;
        font-weight: 600;
    }

    .pc-title {
        margin: 0 0 12px;
        font-size: 1.1rem;
        font-weight: 700;
        line-height: 1.3;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .pc-title a {
        color: #8B4513;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .pc-title a:hover {
        color: #8B4513;
    }

    .pc-price-row {
        display: flex;
        align-items: baseline;
        gap: 8px;
        margin-top: auto;
    }

    .pc-price {
        color: #CD853F;
        font-weight: 800;
        font-size: 1.2rem;
    }

    .pc-weight {
        color: #666;
        font-size: 0.85rem;
    }

    /* ACTIONS */
    .pc-actions {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 12px;
        padding: 15px 20px 20px;
        border-top: 2px solid #DEB887;
        flex-wrap: wrap;
    }

    .btn-ghost, .btn-solid {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        justify-content: center;
        min-width: 120px;
        padding: 10px 16px;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        cursor: pointer;
        font-size: 0.9rem;
        border: 2px solid;
    }

    /* Outline */
    .btn-ghost {
        color: #DEB887;
        background: transparent;
        border-color: #DEB887;
    }

    .btn-ghost:hover {
        color: #FAF9F6;
        background: #DEB887;
        border-color: #DEB887;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px #DEB887;
    }

    /* Solid */
    .btn-solid {
        color: #FAF9F6;
        background: linear-gradient(135deg, #CD853F, #DEB887);
        border-color: #CD853F;
    }

    .btn-solid:hover {
        background: linear-gradient(135deg, #DEB887, #CD853F);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px #DEB887;
    }

    @media (max-width: 576px) {
        .pc-img {
            height: 220px;
        }
        .btn-ghost, .btn-solid {
            min-width: 100%;
        }
    }

    /* No products message */
    .no-products {
        text-align: center;
        color: #666;
        font-size: 1.2rem;
        margin-top: 60px;
        padding: 40px;
        background: #F8F6ED;
        border-radius: 15px;
        border: 2px solid #DEB887;
    }

    /* Pagination */
    .pagination-wrapper {
        margin-top: 50px;
        display: flex;
        justify-content: center;
    }

    /* Carrusel Styles */
    .electrahome-carousel-section {
        background: linear-gradient(135deg, #8B4513 0%, #1a252f 100%);
        margin: 0;
        padding: 0;
        width: 100vw;
        position: relative;
        left: 50%;
        right: 50%;
        margin-left: -50vw;
        margin-right: -50vw;
        margin-bottom: 0;
    }

    .category-hero-slide {
        position: relative;
        height: 100%;
        overflow: hidden;
    }

    .slide-background {
        position: absolute;
        top: 0;
        right: 0;
        width: 50%;
        height: 100%;
        z-index: 1;
    }

    .slide-bg-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        opacity: 0.3;
    }

    .slide-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: #8B4513;
    }

    .slide-content {
        position: relative;
        height: 100%;
        display: flex;
        align-items: center;
        z-index: 2;
        padding: 50px 0;
    }

    .slide-text {
        color: white;
        padding-right: 40px;
    }

    .category-label {
        background: #DEB887;
        border: 2px solid #DEB887;
        border-radius: 25px;
        padding: 10px 22px;
        margin-bottom: 25px;
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        display: inline-block;
        color: #FAF9F6;
    }

    .slide-title {
        font-size: 3rem;
        font-weight: 900;
        margin-bottom: 20px;
        text-transform: uppercase;
        letter-spacing: 2px;
        text-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
        line-height: 1.1;
        color: #FAF9F6;
    }

    .slide-description {
        font-size: 1.2rem;
        margin-bottom: 30px;
        opacity: 0.9;
        line-height: 1.6;
        max-width: 500px;
        color: #FAF9F6;
    }

    .shop-category-btn {
        display: inline-block;
        background: linear-gradient(135deg, #DEB887, #CD853F);
        color: white;
        padding: 16px 40px;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 700;
        font-size: 0.95rem;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        transition: all 0.3s ease;
        box-shadow: 0 8px 25px #DEB887;
    }

    .shop-category-btn:hover {
        background: linear-gradient(135deg, #CD853F, #DEB887);
        transform: translateY(-3px);
        box-shadow: 0 12px 35px #DEB887;
        color: white;
        text-decoration: none;
    }

    .slide-image {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100%;
    }

    .category-product-image {
        max-width: 100%;
        max-height: 320px;
        object-fit: contain;
        border-radius: 15px;
        box-shadow: 0 15px 40px #DEB887;
        transition: transform 0.3s ease;
    }

    .category-product-image:hover {
        transform: scale(1.05);
    }

    /* Controles del carrusel */
    .carousel-control-prev,
    .carousel-control-next {
        width: 55px;
        height: 55px;
        background: #DEB887;
        backdrop-filter: blur(10px);
        border: 2px solid #DEB887;
        border-radius: 50%;
        top: 50%;
        transform: translateY(-50%);
        opacity: 0.8;
        transition: all 0.3s ease;
    }

    .carousel-control-prev {
        left: 30px;
    }

    .carousel-control-next {
        right: 30px;
    }

    .carousel-control-prev:hover,
    .carousel-control-next:hover {
        opacity: 1;
        background: #DEB887;
        transform: translateY(-50%) scale(1.1);
    }

    .carousel-control-prev-icon,
    .carousel-control-next-icon {
        width: 22px;
        height: 22px;
    }

    /* Indicadores */
    .carousel-indicators {
        bottom: 30px;
        margin-bottom: 0;
    }

    .carousel-indicators button {
        width: 14px;
        height: 14px;
        border-radius: 50%;
        background: #DEB887;
        border: 2px solid #DEB887;
        transition: all 0.3s ease;
        margin: 0 6px;
    }

    .carousel-indicators button.active {
        background: #DEB887;
        border-color: #FAF9F6;
        transform: scale(1.3);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .catalog-title {
            font-size: 1.8rem;
            margin-bottom: 30px;
        }

        .filter-bar {
            flex-direction: column;
            gap: 15px;
            padding: 20px;
        }

        .filter-group {
            width: 100%;
            justify-content: space-between;
        }

        .filter-select {
            min-width: 160px;
        }

        .products-grid {
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 20px;
        }

        .slide-title {
            font-size: 2.2rem;
        }

        .slide-description {
            font-size: 1rem;
            padding: 0 20px;
        }

        .carousel-control-prev,
        .carousel-control-next {
            width: 45px;
            height: 45px;
        }
    }

    @media (max-width: 480px) {
        .catalog-section {
            padding: 20px 0;
        }

        .products-grid {
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .slide-title {
            font-size: 1.8rem;
        }

        .category-label {
            padding: 8px 16px;
            font-size: 0.75rem;
            margin-bottom: 15px;
        }

        .slide-description {
            font-size: 0.9rem;
            padding: 0 15px;
        }

        .shop-category-btn {
            padding: 14px 28px;
            font-size: 0.85rem;
            letter-spacing: 1px;
        }

        .carousel-indicators {
            bottom: 20px;
        }

        .category-product-image {
            max-height: 220px;
        }
    }
</style>

<div class="vendedor-perfil container py-4">

    <div class="perfil-header mb-4">
        <h1>{{ $user->name }}</h1>
        <p>Productos publicados: {{ $products->total() }}</p>
        <p>
            Calificación promedio: 
            @for ($i = 1; $i <= 5; $i++)
                <i class="fas fa-star" style="color: {{ $i <= round($avgRating) ? '#ffc107' : '#ddd' }}"></i>
            @endfor
            ({{ number_format($avgRating, 1) }}/5)
        </p>
    </div>


    {{-- Reutilizamos el grid de productos --}}
    <div class="products-grid">
        @forelse ($products as $product)
            @include('vendedor.partials.product-card', ['product' => $product])
        @empty
            <div class="no-products">
                <i class="fas fa-box-open mb-3" style="font-size: 3rem; color: #DEB887;"></i>
                <h3>No tienes productos publicados</h3>
            </div>
        @endforelse
    </div>

    @if($products->hasPages())
        <div class="pagination-wrapper">
            {{ $products->links() }}
        </div>
    @endif

    <div class="perfil-header mb-4">
        <h2 class="mt-5">Opiniones de compradores</h2>
        @forelse($ratings as $rating)
            <div class="rating-item mb-3">
                <strong>{{ $rating->user->name }}</strong> 
                @for ($i = 1; $i <= 5; $i++)
                    <i class="fas fa-star" style="color: {{ $i <= $rating->score ? '#ffc107' : '#ddd' }}"></i>
                @endfor
                <p>{{ $rating->comment }}</p>
            </div>
        @empty
            <p>No hay calificaciones aún.</p>
        @endforelse
    </div>

    {{-- Formulario para calificar --}}
    @auth
        <div class="mt-4">
            <h4>Deja tu calificación:</h4>
            <form action="{{ route('vendedor.rate', $user) }}" method="POST">
                @csrf
                <div class="mb-2">
                    <label>Puntuación (1 a 5):</label>
                    <select name="score" class="form-select" required>
                        @for($i=1; $i<=5; $i++)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                </div>

                <div class="mb-2">
                    <label>Comentario (opcional):</label>
                    <textarea name="comment" class="form-control" rows="3"></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Enviar calificación</button>
            </form>
        </div>
    @endauth

</div>
@endsection
