@extends('vendedor.products.layout-vendedor-producto')

@section('title', "Cargar Producto - {{ env('APP_NAME', 'CaballosApp') }}")

@section('sub-content')
    <h2 class="mb-4">Editar producto</h2>

    @include('vendedor.products._flash')

    <form action="{{ route('vendedor.products.update', $product) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        @include('vendedor.products._form', ['product' => $product])
    </form>

    @include('vendedor.products._questions', ['product' => $product])
@endsection




