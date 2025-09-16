@if ($errors->any())
  <div class="alert alert-danger">
    <ul class="mb-0">
      @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
    </ul>
  </div>
@endif

<form action="{{ $action }}" method="POST" class="p-4 rounded bg-white shadow-sm">
  @csrf
  @if($method !== 'POST') @method($method) @endif

  <div class="row g-3">
    <div class="col-md-6">
      <label class="form-label">Nombre</label>
      <input type="text" name="nombre" class="form-control" required
             value="{{ old('nombre', $tipoListado->nombre) }}" placeholder="Regular, Destacado, Premium...">
    </div>

    <div class="col-md-6">
      <label class="form-label">Slug</label>
      <input type="text" name="slug" class="form-control"
             value="{{ old('slug', $tipoListado->slug) }}" placeholder="regular, destacado... (opcional)">
      <div class="form-text">Si lo dejas vacío se generará automáticamente.</div>
    </div>

    <div class="col-12">
      <label class="form-label">Descripción</label>
      <textarea name="descripcion" class="form-control" rows="2"
        placeholder="Descripción corta">{{ old('descripcion', $tipoListado->descripcion) }}</textarea>
    </div>

    <div class="col-md-4">
      <label class="form-label">Periodo</label>
      <select name="periodo" class="form-select" required>
        @php $periodos = ['day'=>'Día','week'=>'Semana','month'=>'Mes','year'=>'Año']; @endphp
        @foreach($periodos as $val => $label)
          <option value="{{ $val }}" {{ old('periodo', $tipoListado->periodo)===$val?'selected':'' }}>{{ $label }}</option>
        @endforeach
      </select>
    </div>

    <div class="col-md-4">
      <label class="form-label">Cada</label>
      <input type="number" name="periodo_cantidad" min="1" class="form-control" required
             value="{{ old('periodo_cantidad', $tipoListado->periodo_cantidad ?? 1) }}">
      <div class="form-text">p. ej. 1 mes</div>
    </div>

    <div class="col-md-4">
      <label class="form-label">Precio (USD)</label>
      <input type="number" step="0.01" min="0" name="precio" class="form-control" required
             value="{{ old('precio', number_format((float)($tipoListado->precio ?? 0), 2, '.', '')) }}">
    </div>

    <div class="col-md-4">
      <label class="form-label d-block">¿Ilimitado?</label>
      <div class="form-check form-switch">
        <input type="hidden" id="is_ilimitado" name="is_ilimitado" value="0">

        <input class="form-check-input" type="checkbox" id="is_ilimitado" name="is_ilimitado" value="1"
               {{ old('is_ilimitado', $tipoListado->is_ilimitado) ? 'checked' : '' }}>
        <label class="form-check-label" for="is_ilimitado">Activar publicaciones ilimitadas</label>
      </div>
    </div>

    <div class="col-md-4">
      <label class="form-label">Máx. productos</label>
      <input type="number" min="1" name="max_productos" id="max_productos" class="form-control"
             value="{{ old('max_productos', $tipoListado->max_productos) }}"
             {{ old('is_ilimitado', $tipoListado->is_ilimitado) ? 'disabled' : '' }}>
      <div class="form-text">Se desactiva si es ilimitado.</div>
    </div>

    <div class="col-md-4">
      <label class="form-label d-block">Estado</label>
      <div class="form-check form-switch">
        <input class="form-check-input" type="checkbox" id="is_activo" name="is_activo" value="1"
               {{ old('is_activo', $tipoListado->is_activo ?? true) ? 'checked' : '' }}>
        <label class="form-check-label" for="is_activo">Activo</label>
      </div>
    </div>
  </div>

  <div class="mt-4 d-flex gap-2">
    <input type="hidden" id="id" name="id" value="{{ $tipoListado->id ?? '' }}">
    <button class="btn btn-success"><i class="fas fa-save me-2"></i>Guardar</button>
    <a href="{{ route('admin.tipo-listado.index') }}" class="btn btn-secondary">Cancelar</a>
  </div>
</form>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    const ilimitado = document.getElementById('is_ilimitado');
    const max = document.getElementById('max_productos');
    const sync = () => { max.disabled = ilimitado.checked; if (ilimitado.checked) max.value = ''; };
    ilimitado.addEventListener('change', sync);
    sync();
  });
</script>