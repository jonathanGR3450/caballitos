<div class="product-card" style="background: #fff; border: 1px solid #ddd; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
    <div class="pc-media">
        @php $imgs = $product->images; @endphp
        <a href="{{ route('product.show', $product) }}">
            <img src="{{ $imgs->first()?->image ? Storage::url($imgs->first()->image) : asset('images/placeholder.png') }}"
                 class="pc-img" alt="{{ $product->name }}" loading="lazy">
        </a>
    </div>

    <div class="pc-body">
        <div class="pc-category">{{ $product->category->name ?? 'Sin categoría' }}</div>
        <h3 class="pc-title">
            <a href="{{ route('product.show', $product) }}">{{ $product->name }}</a>
        </h3>
        <div class="pc-price-row">
            <div class="pc-price">${{ number_format($product->price + ($product->interest ?? 0), 0, ',', '.') }}</div>
            <div class="pc-weight">c/u</div>
        </div>
        <span class="badge bg-secondary">{{ ucfirst($product->tipo_listado) }}</span>
    </div>

    <div class="pc-actions">
        <a href="{{ route('product.show', $product) }}" class="btn-ghost">
            <i class="fas fa-eye"></i> Ver
        </a>
        <form action="{{ route('cart.add') }}" method="POST" class="d-inline-block">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <button type="submit" class="btn-solid" {{ $product->stock <= 0 ? 'disabled' : '' }}>
                <i class="fas fa-shopping-cart"></i> {{ $product->stock <= 0 ? 'Agotado' : 'Agregar' }}
            </button>
        </form>
    </div>
</div>
