@extends('vendedor.products.layout-vendedor-producto')

@section('title', "Cargar Producto - {{ env('APP_NAME', 'CaballosApp') }}")

@section('sub-content')
    <h2 class="mb-4">Nuevo producto</h2>

    @include('vendedor.products._flash')

    <form action="{{ route('vendedor.products.store') }}" method="POST" enctype="multipart/form-data">
        @include('vendedor.products._form', ['product' => null])
    </form>
@endsection

