@extends('layouts.app')

@section('title','Mis favoritos')

@section('content')
<link rel="stylesheet" href="{{ asset('css/productos.css') }}">
<div class="container py-4">
  <h1 class="h4 mb-3">Mis favoritos</h1>

  @if($products->count())
      <div class="products-grid">
          @php
              $favoritesIds = $products->pluck('id')->all(); // todos son favs aquí
          @endphp
          @forelse ($products as $product)
                @php
                    // Estilos visuales según tipo_listado
                    $cardStyle = match($product->tipo_listado) {
                        'premium' => 'background: #fff8e1; box-shadow: 0 4px 15px rgba(255, 193, 7, 0.6); border: 2px solid #ffc107;',
                        'destacado' => 'background: #e3f2fd; box-shadow: 0 4px 15px rgba(13, 110, 253, 0.6); border: 2px solid #0d6efd;',
                        default => 'background: #ffffff; box-shadow: 0 2px 5px rgba(0,0,0,0.1); border: 1px solid #ddd;',
                    };

                    $badgeClass = match($product->tipo_listado) {
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
                    </div>

                    {{-- BODY --}}
                    <div class="pc-body">
                        {{-- Badge tipo listado --}}
                        <span class="{{ $badgeClass }}">
                            {{ ucfirst($product->tipo_listado) }}
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
                            <span >{{ ucfirst($product->tipo_listado) }}</span>
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

      <div class="mt-3">
        {{ $products->links() }}
      </div>
  @else
      <div class="alert alert-info">
        Aún no tienes productos en favoritos.
      </div>
  @endif
</div>
@endsection
