@extends('layouts.app_admin')

@section('content')
<div class="container py-5">
  <h2 class="text-warning mb-4">Nuevo tipo de listado</h2>
  @include('tipo-listado._form', [
    'action' => route('admin.tipo-listado.store'),
    'method' => 'POST',
    'tipoListado' => new \App\Models\TipoListado([
        'is_ilimitado' => false,
        'is_activo' => true,
        'periodo' => 'month',
        'periodo_cantidad' => 1,
        'precio' => 0,
    ])
  ])
</div>
@endsection