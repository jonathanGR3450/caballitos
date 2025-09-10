@extends('layouts.app')

@section('title','Comparar productos')

@section('content')
<div class="container py-4">
  <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
    <h1 class="h4 m-0">Comparar productos</h1>

    @php $cmpCount = $products->count(); @endphp
    @if($cmpCount)
      <div class="d-flex align-items-center gap-3">
        <div class="form-check form-switch">
          <input class="form-check-input" type="checkbox" id="onlyDiffsSwitch">
          <label class="form-check-label" for="onlyDiffsSwitch">Solo diferencias</label>
        </div>
        <form action="{{ route('compare.clear') }}" method="POST" onsubmit="return confirm('¿Vaciar la comparación?')">
          @csrf @method('DELETE')
          <button class="btn btn-outline-secondary">Vaciar</button>
        </form>
      </div>
    @endif
  </div>

  @if($products->isEmpty())
    <div class="alert alert-info">No has seleccionado productos para comparar.</div>
  @else
  <div class="table-responsive">
    <table class="table align-middle table-compare">
      <thead>
        <tr>
          <th style="width:240px;">Característica</th>
          @foreach($products as $p)
            <th>
              <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
                <div class="d-flex align-items-center gap-2">
                  <img src="{{ $p->images->first()?->image ? Storage::url($p->images->first()->image) : asset('images/placeholder.png') }}"
                       alt="{{ $p->name }}" style="width:60px;height:60px;object-fit:cover;border-radius:8px;">
                  <div>
                    <div class="fw-semibold">
                      <a href="{{ route('product.show', $p) }}">{{ $p->name }}</a>
                    </div>
                    <div class="small text-muted">{{ $p->category->name ?? 'Sin categoría' }}</div>
                  </div>
                </div>
                {{-- Quitar de comparar --}}
                <form action="{{ route('compare.toggle', $p) }}" method="POST" title="Quitar">
                  @csrf
                  <button type="submit" class="btn btn-sm btn-outline-secondary">&times;</button>
                </form>
              </div>
            </th>
          @endforeach
        </tr>
      </thead>

      <tbody>
        {{-- Precio total --}}
        <tr data-row="precio_total">
          <th>Precio</th>
          @foreach($products as $p)
            @php $total = (float)($p->price ?? 0) + (float)($p->interest ?? 0); @endphp
            <td data-value="{{ number_format($total, 0) }}">
              ${{ number_format($total, 0, ',', '.') }} <span class="text-muted small">c/u</span>
            </td>
          @endforeach
        </tr>

        {{-- Interés --}}
        <tr data-row="interes">
          <th>Interés</th>
          @foreach($products as $p)
            <td data-value="{{ number_format((float)($p->interest ?? 0), 0) }}">
              ${{ number_format((float)($p->interest ?? 0), 0, ',', '.') }}
            </td>
          @endforeach
        </tr>

        {{-- Stock --}}
        <tr data-row="stock">
          <th>Stock</th>
          @foreach($products as $p)
            @php $stock = (int)($p->stock ?? 0); @endphp
            <td data-value="{{ $stock }}">
              @if($stock > 0)
                <span class="badge bg-success">En stock ({{ $stock }})</span>
              @else
                <span class="badge bg-secondary">Sin stock</span>
              @endif
            </td>
          @endforeach
        </tr>

        {{-- Estado --}}
        <tr data-row="estado">
          <th>Estado</th>
          @foreach($products as $p)
            @php
              $estado = strtolower($p->estado ?? '');
              $cls = match($estado){
                'disponible' => 'bg-success',
                'vendido'    => 'bg-danger',
                'pendiente'  => 'bg-warning text-dark',
                default      => 'bg-secondary'
              };
            @endphp
            <td data-value="{{ $estado }}">
              <span class="badge {{ $cls }}">{{ ucfirst($p->estado ?? '—') }}</span>
            </td>
          @endforeach
        </tr>

        {{-- Tipo de listado --}}
        <tr data-row="tipo_listado">
          <th>Tipo de listado</th>
          @foreach($products as $p)
            @php $tipo = strtolower($p->tipo_listado ?? ''); @endphp
            <td data-value="{{ $tipo }}">{{ ucfirst($p->tipo_listado ?? '—') }}</td>
          @endforeach
        </tr>

        {{-- Vigencia --}}
        <tr data-row="vigencia">
          <th>Vigencia</th>
          @foreach($products as $p)
            @php $vig = $p->esta_vencido ? 'vencido' : 'vigente'; @endphp
            <td data-value="{{ $vig }}">
              @if($p->esta_vencido)
                <span class="badge bg-danger">Vencido</span>
              @else
                <span class="badge bg-info">Vigente</span>
              @endif
            </td>
          @endforeach
        </tr>

        {{-- SKU --}}
        <tr data-row="sku">
          <th>SKU</th>
          @foreach($products as $p)
            <td data-value="{{ $p->id }}">#{{ $p->id }}</td>
          @endforeach
        </tr>

        {{-- Categoría --}}
        <tr data-row="category">
          <th>Categoría</th>
          @foreach($products as $p)
            @php $cat = $p->category->name ?? 'Sin categoría'; @endphp
            <td data-value="{{ strtolower($cat) }}">{{ $cat }}</td>
          @endforeach
        </tr>

        {{-- Peso prom. --}}
        <tr data-row="avg_weight">
          <th>Peso promedio</th>
          @foreach($products as $p)
            @php $weight = $p->avg_weight ?? null; @endphp
            <td data-value="{{ $weight ?? '—' }}">{{ $weight ? $weight : '—' }}</td>
          @endforeach
        </tr>

        {{-- Vendedor --}}
        <tr data-row="vendedor">
          <th>Vendedor</th>
          @foreach($products as $p)
            @php $vname = $p->user->name ?? '—'; @endphp
            <td data-value="{{ strtolower($vname) }}">
              @if(isset($p->user))
                <a href="{{ route('vendedor.perfil', $p->user) }}">{{ $vname }}</a>
              @else
                —
              @endif
            </td>
          @endforeach
        </tr>

        {{-- EXTRA: Ubicación --}}
        <tr data-row="ubicacion">
          <th>Ubicación</th>
          @foreach($products as $p)
            @php $val = optional($p->extra)->ubicacion; @endphp
            <td data-value="{{ strtolower($val ?? '—') }}">{{ $val ?? '—' }}</td>
          @endforeach
        </tr>

        {{-- EXTRA: Raza --}}
        <tr data-row="raza">
          <th>Raza</th>
          @foreach($products as $p)
            @php $val = optional($p->extra)->raza; @endphp
            <td data-value="{{ strtolower($val ?? '—') }}">{{ $val ?? '—' }}</td>
          @endforeach
        </tr>

        {{-- EXTRA: Edad --}}
        <tr data-row="edad">
          <th>Edad</th>
          @foreach($products as $p)
            @php
              $edad = optional($p->extra)->edad;
              $muestra = $edad !== null && $edad !== '' ? ($edad.' años') : '—';
            @endphp
            <td data-value="{{ $edad ?? '—' }}">{{ $muestra }}</td>
          @endforeach
        </tr>

        {{-- EXTRA: Género --}}
        <tr data-row="genero">
          <th>Género</th>
          @foreach($products as $p)
            @php $val = optional($p->extra)->genero; @endphp
            <td data-value="{{ strtolower($val ?? '—') }}">{{ $val ?? '—' }}</td>
          @endforeach
        </tr>

        {{-- EXTRA: Pedigrí --}}
        <tr data-row="pedigri">
          <th>Pedigrí</th>
          @foreach($products as $p)
            @php $val = optional($p->extra)->pedigri; @endphp
            <td data-value="{{ strtolower($val ?? '—') }}">{{ $val ?? '—' }}</td>
          @endforeach
        </tr>

        {{-- EXTRA: Entrenamiento --}}
        <tr data-row="entrenamiento">
          <th>Entrenamiento</th>
          @foreach($products as $p)
            @php $val = optional($p->extra)->entrenamiento; @endphp
            <td data-value="{{ strtolower($val ?? '—') }}">{{ $val ?? '—' }}</td>
          @endforeach
        </tr>

        {{-- EXTRA: Historial de salud --}}
        <tr data-row="historial_salud">
          <th>Historial de salud</th>
          @foreach($products as $p)
            @php $val = optional($p->extra)->historial_salud; @endphp
            <td data-value="{{ strtolower($val ?? '—') }}">{{ $val ?? '—' }}</td>
          @endforeach
        </tr>

      </tbody>
    </table>
  </div>
  @endif
</div>

{{-- Estilos opcionales para diferenciar filas distintas --}}
<style>
.table-compare tbody tr.diff-row > th{
  border-left: 4px solid #CD853F;
}
.table-compare tbody tr.diff-row{
  background: rgba(205,133,63,.06);
}
</style>

{{-- Solo diferencias (JS simple en cliente) --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
  const switchEl = document.getElementById('onlyDiffsSwitch');
  if(!switchEl) return;

  const rows = [...document.querySelectorAll('.table-compare tbody tr')];

  function normalize(val){
    return String(val ?? '')
      .replace(/\s+/g,' ')
      .trim()
      .toLowerCase();
  }

  function isRowEqual(row){
    const tds = [...row.querySelectorAll('td')];
    if (tds.length === 0) return true;
    const values = tds.map(td => td.getAttribute('data-value') ?? td.textContent);
    const normalized = values.map(v => normalize(v));
    // Si todos son exactamente iguales, la fila NO es diferencia
    return normalized.every(v => v === normalized[0]);
  }

  function markRows(){
    rows.forEach(r => {
      const equal = isRowEqual(r);
      r.classList.toggle('diff-row', !equal);
      // si está activado el switch, ocultar las iguales
      r.style.display = (switchEl.checked && equal) ? 'none' : '';
    });
  }

  switchEl.addEventListener('change', markRows);
  markRows();
});
</script>
@endsection
