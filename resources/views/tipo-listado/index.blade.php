@extends('layouts.app_admin')

@section('content')
<div class="container py-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="text-warning mb-0">Tipos de listado</h2>
    <a href="{{ route('admin.tipo-listado.create') }}" class="btn btn-success">
      <i class="fas fa-plus me-2"></i>Nuevo
    </a>
  </div>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  <div class="table-responsive">
    <table class="table table-striped align-middle">
      <thead class="table-dark">
        <tr>
          <th>#</th>
          <th>Nombre</th>
          <th>Periodo</th>
          <th>Máx. productos</th>
          <th>Precio</th>
          <th>Activo</th>
          <th class="text-center">Acciones</th>
        </tr>
      </thead>
      <tbody>
      @forelse($listados as $l)
        <tr>
          <td>{{ $l->id }}</td>
          <td>
            <div class="fw-semibold">{{ $l->nombre }}</div>
            <div class="small text-muted">{{ $l->slug }}</div>
          </td>
          <td>{{ $l->periodo_cantidad }} / {{ ucfirst($l->periodo) }}</td>
          <td>{{ $l->is_ilimitado ? 'Ilimitado' : ($l->max_productos ?? '—') }}</td>
          <td>${{ number_format($l->precio, 2) }}</td>
          <td>
            <form action="{{ route('admin.tipo-listado.toggle-active', $l) }}" method="POST">
              @csrf @method('PATCH')
              <button class="btn btn-sm {{ $l->is_activo ? 'btn-success' : 'btn-secondary' }}">
                {{ $l->is_activo ? 'Activo' : 'Inactivo' }}
              </button>
            </form>
          </td>
          <td class="text-center">
            <a href="{{ route('admin.tipo-listado.edit', $l) }}" class="btn btn-warning btn-sm">
              <i class="fas fa-edit"></i>
            </a>
            <form action="{{ route('admin.tipo-listado.destroy', $l) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar este tipo de listado?')">
              @csrf @method('DELETE')
              <button type="submit" class="btn btn-danger btn-sm">
                <i class="fas fa-trash"></i>
              </button>
            </form>
          </td>
        </tr>
      @empty
        <tr><td colspan="7" class="text-center text-muted py-4">Sin registros</td></tr>
      @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-3">
    {{ $listados->links('pagination::bootstrap-5') }}
  </div>
</div>
@endsection