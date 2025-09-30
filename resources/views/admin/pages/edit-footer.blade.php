@extends('layouts.app_admin')

@section('content')
<style>
    .section-card{background:#FAF9F6;border:1px solid #DEB887;border-radius:8px;margin-bottom:24px}
    .section-header{padding:14px 16px;border-bottom:1px solid #DEB887}
    .section-body{padding:16px}
    .form-control{background:#FAF9F6;border:1px solid #DEB887;color:#101820}
    .btn-success{background:#DEB887;border-color:#DEB887}
    h2,h4{color:#DEB887}
</style>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Editar Footer</h2>
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">Volver</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @foreach($page->sections()->ordered()->get() as $section)

        @if($section->name === 'about')
        <div class="section-card">
          <div class="section-header"><h4>Acerca de</h4></div>
          <div class="section-body">
            <form action="{{ route('admin.pages.sections.update', [$page->id,$section->id]) }}" method="POST" enctype="multipart/form-data">
              @csrf @method('PUT')
              <div class="mb-3">
                <label class="form-label">Título</label>
                <input name="title" class="form-control" value="{{ $section->title }}">
              </div>
              <div class="mb-3">
                <label class="form-label">Descripción</label>
                <textarea name="content" rows="3" class="form-control">{{ $section->content }}</textarea>
              </div>
              <div class="mb-3">
                <label class="form-label">Logo (opcional)</label>
                <input type="file" name="images[]" accept="image/*" class="form-control">
                @if($section->getImagesArray())
                <div class="mt-2 d-flex align-items-center">
                  <img src="{{ Storage::url($section->getImagesArray()[0]) }}" style="height:50px" alt="logo">
                  <button type="button" class="btn btn-danger btn-sm ms-2" onclick="deleteImage({{ $section->id }},0)">Eliminar</button>
                </div>
                @endif
              </div>
              <input type="hidden" name="is_active" value="1">
              <button class="btn btn-success">Guardar</button>
            </form>
          </div>
        </div>
        @endif

        @if($section->name === 'social')
        <div class="section-card">
          <div class="section-header"><h4>Redes sociales</h4></div>
          <div class="section-body">
            <form action="{{ route('admin.pages.sections.update', [$page->id,$section->id]) }}" method="POST">
              @csrf @method('PUT')
              <div class="row g-3">
                <div class="col-md-6"><label class="form-label">Facebook</label><input name="facebook" class="form-control" value="{{ $section->getCustomData('facebook','') }}"></div>
                <div class="col-md-6"><label class="form-label">Instagram</label><input name="instagram" class="form-control" value="{{ $section->getCustomData('instagram','') }}"></div>
                <div class="col-md-6"><label class="form-label">WhatsApp</label><input name="whatsapp" class="form-control" value="{{ $section->getCustomData('whatsapp','') }}"></div>
                <div class="col-md-6"><label class="form-label">Email</label><input name="email" class="form-control" value="{{ $section->getCustomData('email','') }}"></div>
              </div>
              <input type="hidden" name="is_active" value="1">
              <button class="btn btn-success mt-3">Guardar</button>
            </form>
          </div>
        </div>
        @endif

        @if($section->name === 'nav')
        <div class="section-card">
          <div class="section-header"><h4>Navegación</h4></div>
          <div class="section-body">
            <form action="{{ route('admin.pages.sections.update', [$page->id,$section->id]) }}" method="POST">
              @csrf @method('PUT')
              @php $links = $section->getCustomData('links',[]) ?? []; @endphp
              <div id="nav-links">
                @foreach(($links ?: [['label'=>'','route'=>'']]) as $i=>$l)
                <div class="row g-2 align-items-end mb-2">
                  <div class="col-md-5"><label class="form-label">Etiqueta</label><input name="links[{{ $i }}][label]" class="form-control" value="{{ $l['label'] ?? '' }}"></div>
                  <div class="col-md-5"><label class="form-label">Ruta (name)</label><input name="links[{{ $i }}][route]" class="form-control" value="{{ $l['route'] ?? '' }}"></div>
                </div>
                @endforeach
              </div>
              <small class="text-muted">Usa nombres de ruta: home, shop.index, about, contact.index, recipes…</small>
              <input type="hidden" name="is_active" value="1">
              <button class="btn btn-success mt-3">Guardar</button>
            </form>
          </div>
        </div>
        @endif

        @if($section->name === 'contact')
        <div class="section-card">
          <div class="section-header"><h4>Contacto</h4></div>
          <div class="section-body">
            <form action="{{ route('admin.pages.sections.update', [$page->id,$section->id]) }}" method="POST">
              @csrf @method('PUT')
              <div class="row g-3">
                <div class="col-md-6"><label class="form-label">Email</label><input name="email" class="form-control" value="{{ $section->getCustomData('email','') }}"></div>
                <div class="col-md-6"><label class="form-label">Teléfono</label><input name="phone" class="form-control" value="{{ $section->getCustomData('phone','') }}"></div>
                <div class="col-md-6"><label class="form-label">Enlace Tel (tel:)</label><input name="phone_link" class="form-control" value="{{ $section->getCustomData('phone_link','') }}"></div>
                <div class="col-md-6"><label class="form-label">Ubicación</label><input name="location" class="form-control" value="{{ $section->getCustomData('location','') }}"></div>
              </div>
              <input type="hidden" name="is_active" value="1">
              <button class="btn btn-success mt-3">Guardar</button>
            </form>
          </div>
        </div>
        @endif

        @if($section->name === 'hours')
        <div class="section-card">
          <div class="section-header"><h4>Horarios</h4></div>
          <div class="section-body">
            <form action="{{ route('admin.pages.sections.update', [$page->id,$section->id]) }}" method="POST">
              @csrf @method('PUT')
              <div class="mb-2"><label class="form-label">Lunes a Viernes</label><input name="weekdays" class="form-control" value="{{ $section->getCustomData('weekdays','') }}"></div>
              <div class="mb-2"><label class="form-label">Sábados</label><input name="saturday" class="form-control" value="{{ $section->getCustomData('saturday','') }}"></div>
              <div class="mb-2"><label class="form-label">Domingos</label><input name="sunday" class="form-control" value="{{ $section->getCustomData('sunday','') }}"></div>
              <input type="hidden" name="is_active" value="1">
              <button class="btn btn-success">Guardar</button>
            </form>
          </div>
        </div>
        @endif

        @if($section->name === 'badges')
        <div class="section-card">
          <div class="section-header"><h4>Sellos</h4></div>
          <div class="section-body">
            <form action="{{ route('admin.pages.sections.update', [$page->id,$section->id]) }}" method="POST">
              @csrf @method('PUT')
              @php $items = $section->getCustomData('items',[]) ?? []; @endphp
              @foreach(($items ?: [['icon'=>'fas fa-user-check','text'=>'Vendedores verificados']]) as $i=>$it)
              <div class="row g-2 align-items-end mb-2">
                <div class="col-md-5"><label class="form-label">Icono (FA)</label><input name="items[{{ $i }}][icon]" class="form-control" value="{{ $it['icon'] ?? '' }}"></div>
                <div class="col-md-5"><label class="form-label">Texto</label><input name="items[{{ $i }}][text]" class="form-control" value="{{ $it['text'] ?? '' }}"></div>
              </div>
              @endforeach
              <input type="hidden" name="is_active" value="1">
              <button class="btn btn-success mt-2">Guardar</button>
            </form>
          </div>
        </div>
        @endif

        @if($section->name === 'bottom')
        <div class="section-card">
          <div class="section-header"><h4>Legal / Línea inferior</h4></div>
          <div class="section-body">
            <form action="{{ route('admin.pages.sections.update', [$page->id,$section->id]) }}" method="POST">
              @csrf @method('PUT')
              <div class="mb-2"><label class="form-label">Copyright</label>
                <input name="copyright" class="form-control" value="{{ $section->getCustomData('copyright','') }}">
              </div>
              <input type="hidden" name="is_active" value="1">
              <button class="btn btn-success">Guardar</button>
            </form>
          </div>
        </div>
        @endif

    @endforeach
</div>

<script>
function deleteImage(sectionId, imageIndex){
  if(!confirm('¿Eliminar imagen?')) return;
  fetch(`/admin/pages/{{ $page->id }}/sections/${sectionId}/images`, {
    method: 'DELETE',
    headers: {
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({ image_index: imageIndex })
  }).then(r=>r.json()).then(()=>location.reload());
}
</script>
@endsection
