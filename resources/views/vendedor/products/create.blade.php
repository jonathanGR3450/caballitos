@extends('vendedor.products.layout-vendedor-producto')

@section('title', "Cargar Producto - {{ env('APP_NAME', 'CaballosApp') }}")

@section('sub-content')
    <h2 class="mb-4">Nuevo producto</h2>

    @include('vendedor.products._flash')

    @php
    $u = auth()->user();
    $remaining = $u?->remainingQuota();
    $ends = $u?->membershipEndsAt()?->isoFormat('YYYY-MM-DD');
    @endphp

    @if($u && $u->tipoListado)
    <div class="alert alert-info">
        Plan: <strong>{{ $u->tipoListado->nombre }}</strong> —
        @if(is_null($remaining))
        Publicaciones: <strong>Ilimitadas</strong>
        @else
        Te quedan <strong>{{ $remaining }}</strong> publicaciones
        @endif
        @if($ends)
        &nbsp;|&nbsp; Vigente hasta: <strong>{{ $ends }}</strong>
        @endif
    </div>
    @endif

    <form action="{{ route('vendedor.products.store') }}" method="POST" enctype="multipart/form-data">
        @include('vendedor.products._form', ['product' => null])
    </form>
@endsection

