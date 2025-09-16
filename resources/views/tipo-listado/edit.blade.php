@extends('layouts.app_admin')

@section('content')
<div class="container py-5">
  <h2 class="text-warning mb-4">Editar tipo de listado</h2>
  @include('tipo-listado._form', [
    'action' => route('admin.tipo-listado.update', $tipoListado),
    'method' => 'PUT',
    'tipoListado' => $tipoListado
  ])
</div>
@endsection