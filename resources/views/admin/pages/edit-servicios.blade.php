{{-- resources/views/admin/pages/edit-servicios.blade.php --}}
@extends('layouts.app_admin')

@section('content')
<style>
    body, .container { background: #FAF9F6 !important; color: #101820; }
    .main-content { background: #FAF9F6; padding: 20px; border-radius: 8px; }
    .section-card { background: #FAF9F6; border: 1px solid #DEB887; border-radius: 8px; margin-bottom: 25px; }
    .section-header { background: #FAF9F6; padding: 15px; border-bottom: 1px solid #DEB887; }
    .section-body { padding: 20px; }
    .form-control, .form-select, .form-control:focus { background: #FAF9F6; border: 1px solid #DEB887; color: #101820; }
    .form-control:focus { border-color: #f7a831; box-shadow: 0 0 0 0.2rem rgba(247, 168, 49, 0.25); }
    .btn-success { background-color: #DEB887; border-color: #DEB887; }
    .btn-success:hover { background-color: #f7a831; border-color: #f7a831; color: #101820; }
    .btn-danger { background-color: #dc3545; border-color: #dc3545; }
    .btn-secondary { background-color: #6c757d; border-color: #6c757d; }
    h2, h4 { color: #DEB887 !important; }
    .alert-success { background-color: #DEB887; color: #FAF9F6; border: 1px solid #DEB887; }
    .form-check-input:checked { background-color: #DEB887; border-color: #DEB887; }
    .badge-hero { background-color: #f7a831; }
    .badge-intro { background-color: #28a745; }
    .badge-services { background-color: #17a2b8; }
    .badge-process { background-color: #fd7e14; }
    .badge-why { background-color: #6f42c1; }
    .badge-cta { background-color: #dc3545; }
    .image-preview { height: 120px; width: 120px; object-fit: cover; border-radius: 8px; border: 2px solid #DEB887; }
    .field-group { background: rgba(222, 184, 135, 0.1); border: 1px solid #DEB887; border-radius: 8px; padding: 15px; margin-bottom: 20px; }
    .field-group h6 { color: #DEB887; margin-bottom: 15px; }
    .service-preview { background: rgba(222, 184, 135, 0.1); border: 1px solid #DEB887; border-radius: 8px; padding: 15px; margin-bottom: 15px; }
    .process-preview { background: rgba(222, 184, 135, 0.1); border: 1px solid #DEB887; border-radius: 8px; padding: 15px; margin-bottom: 15px; }
    .reason-preview { background: rgba(222, 184, 135, 0.1); border: 1px solid #DEB887; border-radius: 8px; padding: 15px; margin-bottom: 15px; }
</style>

<div class="main-content">
    <div class="container py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1"><i class="fas fa-tools"></i> Editar Página "Servicios"</h2>
                <p class="text-light mb-0">Gestiona toda la información de servicios y publicaciones ecuestres</p>
            </div>
            <a href="{{ route('admin.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @foreach($page->sections()->ordered()->get() as $section)

            {{-- SECCIÓN HERO - Banner de Servicios --}}
            @if($section->name === 'hero')
            <div class="section-card">
                <div class="section-header">
                    <h4><i class="fas fa-flag me-2"></i> Banner Principal <span class="badge badge-hero ms-2">Hero</span></h4>
                </div>
                <div class="section-body">
                    <form action="{{ route('admin.sections.update', [$page->id, $section->id]) }}" method="POST" enctype="multipart/form-data">
                        @csrf @method('PUT')
                        
                        <div class="field-group">
                            <h6><i class="fas fa-heading"></i> Títulos del Banner</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Título Principal</label>
                                    <input type="text" name="title" class="form-control" 
                                           value="{{ $section->title ?: 'Servicios Ecuestres' }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Subtítulo</label>
                                    <input type="text" name="content" class="form-control" 
                                           value="{{ $section->content ?: 'Servicios para compra y venta de caballos, entrenamiento y asesoría' }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="field-group">
                            <h6><i class="fas fa-image"></i> Imagen de Fondo</h6>
                            <div class="row">
                                <div class="col-md-8">
                                    <input type="file" name="images[]" class="form-control" accept="image/*">
                                    <small class="text-muted">Recomendado: 1920x600px. Imagen relacionada con el mundo ecuestre.</small>
                                </div>
                                <div class="col-md-4">
                                    @if($section->getImagesArray())
                                        <img src="{{ Storage::url($section->getImagesArray()[0]) }}" class="image-preview mb-2">
                                        <br>
                                        <button type="button" class="btn btn-danger btn-sm" 
                                                onclick="deleteImage('hero', {{ $section->id }}, 0)">
                                            <i class="fas fa-trash"></i> Cambiar
                                        </button>
                                    @else
                                        <div class="text-center p-3 border rounded" style="border-color: #DEB887;">
                                            <i class="fas fa-image fa-2x text-muted"></i><br>
                                            <small class="text-muted">Sin imagen</small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="is_active" value="1">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-save me-2"></i> Guardar Banner
                        </button>
                    </form>
                </div>
            </div>

            {{-- SECCIÓN INTRO - Introducción --}}
            @elseif($section->name === 'intro')
            <div class="section-card">
                <div class="section-header">
                    <h4><i class="fas fa-info-circle me-2"></i> Introducción <span class="badge badge-intro ms-2">Intro</span></h4>
                </div>
                <div class="section-body">
                    <form action="{{ route('admin.sections.update', [$page->id, $section->id]) }}" method="POST" enctype="multipart/form-data">
                        @csrf @method('PUT')

                        <div class="field-group">
                            <h6><i class="fas fa-heading"></i> Título</h6>
                            <input type="text" name="title" class="form-control mb-3" 
                                   value="{{ $section->title ?: 'Expertos en el Mundo Ecuestre' }}" required>
                        </div>

                        <div class="field-group">
                            <h6><i class="fas fa-align-left"></i> Descripción</h6>
                            <textarea name="content" class="form-control" rows="4" 
                                      placeholder="Descripción introductoria sobre tus servicios ecuestres, experiencia y compromiso...">{{ $section->content }}</textarea>
                        </div>

                        <div class="field-group">
                            <h6><i class="fas fa-image"></i> Imagen Representativa</h6>
                            <div class="row">
                                <div class="col-md-8">
                                    <input type="file" name="images[]" class="form-control" accept="image/*">
                                    <small class="text-muted">Imagen del equipo o de la caballeriza</small>
                                </div>
                                <div class="col-md-4">
                                    @if($section->getImagesArray())
                                        <img src="{{ Storage::url($section->getImagesArray()[0]) }}" class="image-preview">
                                        <button type="button" class="btn btn-danger btn-sm mt-1" 
                                                onclick="deleteImage('intro', {{ $section->id }}, 0)">Cambiar</button>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="is_active" value="1">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-save me-2"></i> Guardar Introducción
                        </button>
                    </form>
                </div>
            </div>

            {{-- SECCIÓN SERVICES LIST - Lista de Servicios --}}
            @elseif($section->name === 'services_list')
            <div class="section-card">
                <div class="section-header">
                    <h4><i class="fas fa-list me-2"></i> Lista de Servicios <span class="badge badge-services ms-2">Services</span></h4>
                </div>
                <div class="section-body">
                    <form action="{{ route('admin.sections.update', [$page->id, $section->id]) }}" method="POST">
                        @csrf @method('PUT')

                        <div class="field-group">
                            <h6><i class="fas fa-heading"></i> Título de Sección</h6>
                            <input type="text" name="title" class="form-control mb-3" 
                                   value="{{ $section->title ?: 'Servicios Disponibles' }}" required>
                            <textarea name="content" class="form-control" rows="2" 
                                      placeholder="Descripción breve de los servicios ecuestres">{{ $section->content }}</textarea>
                        </div>

                        <div class="field-group">
                            <h6><i class="fas fa-cogs"></i> 6 Servicios Principales</h6>
                            
                            <!-- Servicio 1 -->
                            <div class="service-preview">
                                <div class="row">
                                    <div class="col-md-2">
                                        <label class="form-label">Icono 1</label>
                                        <input type="text" name="service_1_icon" class="form-control text-center" 
                                               value="{{ $section->getCustomData('service_1_icon', '🐎') }}" style="font-size: 1.5rem;">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Servicio 1</label>
                                        <input type="text" name="service_1_title" class="form-control" 
                                               value="{{ $section->getCustomData('service_1_title', 'Venta de Caballos') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Descripción 1</label>
                                        <input type="text" name="service_1_desc" class="form-control" 
                                               value="{{ $section->getCustomData('service_1_desc', 'Compra y venta de ejemplares por raza y disciplina') }}">
                                    </div>
                                </div>
                            </div>

                            <!-- Servicio 2 -->
                            <div class="service-preview">
                                <div class="row">
                                    <div class="col-md-2">
                                        <label class="form-label">Icono 2</label>
                                        <input type="text" name="service_2_icon" class="form-control text-center" 
                                               value="{{ $section->getCustomData('service_2_icon', '📜') }}" style="font-size: 1.5rem;">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Servicio 2</label>
                                        <input type="text" name="service_2_title" class="form-control" 
                                               value="{{ $section->getCustomData('service_2_title', 'Pedigrí y Documentación') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Descripción 2</label>
                                        <input type="text" name="service_2_desc" class="form-control" 
                                               value="{{ $section->getCustomData('service_2_desc', 'Verificación de registros y certificados sanitarios') }}">
                                    </div>
                                </div>
                            </div>

                            <!-- Servicio 3 -->
                            <div class="service-preview">
                                <div class="row">
                                    <div class="col-md-2">
                                        <label class="form-label">Icono 3</label>
                                        <input type="text" name="service_3_icon" class="form-control text-center" 
                                               value="{{ $section->getCustomData('service_3_icon', '🎓') }}" style="font-size: 1.5rem;">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Servicio 3</label>
                                        <input type="text" name="service_3_title" class="form-control" 
                                               value="{{ $section->getCustomData('service_3_title', 'Entrenamiento y Doma') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Descripción 3</label>
                                        <input type="text" name="service_3_desc" class="form-control" 
                                               value="{{ $section->getCustomData('service_3_desc', 'Programas para salto, dressage, paso fino y más') }}">
                                    </div>
                                </div>
                            </div>

                            <!-- Servicio 4 -->
                            <div class="service-preview">
                                <div class="row">
                                    <div class="col-md-2">
                                        <label class="form-label">Icono 4</label>
                                        <input type="text" name="service_4_icon" class="form-control text-center" 
                                               value="{{ $section->getCustomData('service_4_icon', '🩺') }}" style="font-size: 1.5rem;">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Servicio 4</label>
                                        <input type="text" name="service_4_title" class="form-control" 
                                               value="{{ $section->getCustomData('service_4_title', 'Salud y Bienestar') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Descripción 4</label>
                                        <input type="text" name="service_4_desc" class="form-control" 
                                               value="{{ $section->getCustomData('service_4_desc', 'Planes veterinarios y cuidados preventivos') }}">
                                    </div>
                                </div>
                            </div>

                            <!-- Servicio 5 -->
                            <div class="service-preview">
                                <div class="row">
                                    <div class="col-md-2">
                                        <label class="form-label">Icono 5</label>
                                        <input type="text" name="service_5_icon" class="form-control text-center" 
                                               value="{{ $section->getCustomData('service_5_icon', '🧰') }}" style="font-size: 1.5rem;">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Servicio 5</label>
                                        <input type="text" name="service_5_title" class="form-control" 
                                               value="{{ $section->getCustomData('service_5_title', 'Accesorios y Equipamiento') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Descripción 5</label>
                                        <input type="text" name="service_5_desc" class="form-control" 
                                               value="{{ $section->getCustomData('service_5_desc', 'Monturas, cabezadas, protectores y más') }}">
                                    </div>
                                </div>
                            </div>

                            <!-- Servicio 6 -->
                            <div class="service-preview">
                                <div class="row">
                                    <div class="col-md-2">
                                        <label class="form-label">Icono 6</label>
                                        <input type="text" name="service_6_icon" class="form-control text-center" 
                                               value="{{ $section->getCustomData('service_6_icon', '🚚') }}" style="font-size: 1.5rem;">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Servicio 6</label>
                                        <input type="text" name="service_6_title" class="form-control" 
                                               value="{{ $section->getCustomData('service_6_title', 'Transporte y Logística') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Descripción 6</label>
                                        <input type="text" name="service_6_desc" class="form-control" 
                                               value="{{ $section->getCustomData('service_6_desc', 'Coordinación de traslados nacionales e internacionales') }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="is_active" value="1">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-save me-2"></i> Guardar Lista de Servicios
                        </button>
                    </form>
                </div>
            </div>

            {{-- SECCIÓN PROCESS - Proceso de Trabajo --}}
            @elseif($section->name === 'process')
            <div class="section-card">
                <div class="section-header">
                    <h4><i class="fas fa-tasks me-2"></i> Proceso de Trabajo <span class="badge badge-process ms-2">Process</span></h4>
                </div>
                <div class="section-body">
                    <form action="{{ route('admin.sections.update', [$page->id, $section->id]) }}" method="POST">
                        @csrf @method('PUT')

                        <div class="field-group">
                            <h6><i class="fas fa-heading"></i> Título</h6>
                            <input type="text" name="title" class="form-control mb-3" 
                                   value="{{ $section->title ?: 'Nuestro Proceso de Compra/Venta' }}" required>
                            <textarea name="content" class="form-control" rows="2" 
                                      placeholder="Descripción del proceso ecuestre">{{ $section->content }}</textarea>
                        </div>

                        <div class="field-group">
                            <h6><i class="fas fa-list-ol"></i> 4 Pasos del Proceso</h6>
                            
                            <!-- Paso 1 -->
                            <div class="process-preview">
                                <div class="row">
                                    <div class="col-md-1">
                                        <label class="form-label">Paso</label>
                                        <input type="text" name="step_1_number" class="form-control text-center" 
                                               value="{{ $section->getCustomData('step_1_number', '1') }}" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Título Paso 1</label>
                                        <input type="text" name="step_1_title" class="form-control" 
                                               value="{{ $section->getCustomData('step_1_title', 'Contacto') }}">
                                    </div>
                                    <div class="col-md-7">
                                        <label class="form-label">Descripción Paso 1</label>
                                        <input type="text" name="step_1_desc" class="form-control" 
                                               value="{{ $section->getCustomData('step_1_desc', 'Hablamos sobre necesidades, disciplina y presupuesto') }}">
                                    </div>
                                </div>
                            </div>

                            <!-- Paso 2 -->
                            <div class="process-preview">
                                <div class="row">
                                    <div class="col-md-1">
                                        <label class="form-label">Paso</label>
                                        <input type="text" name="step_2_number" class="form-control text-center" 
                                               value="{{ $section->getCustomData('step_2_number', '2') }}" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Título Paso 2</label>
                                        <input type="text" name="step_2_title" class="form-control" 
                                               value="{{ $section->getCustomData('step_2_title', 'Selección') }}">
                                    </div>
                                    <div class="col-md-7">
                                        <label class="form-label">Descripción Paso 2</label>
                                        <input type="text" name="step_2_desc" class="form-control" 
                                               value="{{ $section->getCustomData('step_2_desc', 'Proponemos ejemplares con ficha, fotos y videos') }}">
                                    </div>
                                </div>
                            </div>

                            <!-- Paso 3 -->
                            <div class="process-preview">
                                <div class="row">
                                    <div class="col-md-1">
                                        <label class="form-label">Paso</label>
                                        <input type="text" name="step_3_number" class="form-control text-center" 
                                               value="{{ $section->getCustomData('step_3_number', '3') }}" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Título Paso 3</label>
                                        <input type="text" name="step_3_title" class="form-control" 
                                               value="{{ $section->getCustomData('step_3_title', 'Inspección') }}">
                                    </div>
                                    <div class="col-md-7">
                                        <label class="form-label">Descripción Paso 3</label>
                                        <input type="text" name="step_3_desc" class="form-control" 
                                               value="{{ $section->getCustomData('step_3_desc', 'Visita, prueba montada y revisión veterinaria') }}">
                                    </div>
                                </div>
                            </div>

                            <!-- Paso 4 -->
                            <div class="process-preview">
                                <div class="row">
                                    <div class="col-md-1">
                                        <label class="form-label">Paso</label>
                                        <input type="text" name="step_4_number" class="form-control text-center" 
                                               value="{{ $section->getCustomData('step_4_number', '4') }}" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Título Paso 4</label>
                                        <input type="text" name="step_4_title" class="form-control" 
                                               value="{{ $section->getCustomData('step_4_title', 'Cierre y Entrega') }}">
                                    </div>
                                    <div class="col-md-7">
                                        <label class="form-label">Descripción Paso 4</label>
                                        <input type="text" name="step_4_desc" class="form-control" 
                                               value="{{ $section->getCustomData('step_4_desc', 'Contrato, traslado y acompañamiento postventa') }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="is_active" value="1">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-save me-2"></i> Guardar Proceso
                        </button>
                    </form>
                </div>
            </div>

            {{-- SECCIÓN WHY CHOOSE - Por Qué Elegir --}}
            @elseif($section->name === 'why_choose')
            <div class="section-card">
                <div class="section-header">
                    <h4><i class="fas fa-star me-2"></i> Por Qué Elegirnos <span class="badge badge-why ms-2">Why</span></h4>
                </div>
                <div class="section-body">
                    <form action="{{ route('admin.sections.update', [$page->id, $section->id]) }}" method="POST">
                        @csrf @method('PUT')

                        <div class="field-group">
                            <h6><i class="fas fa-heading"></i> Título</h6>
                            <input type="text" name="title" class="form-control mb-3" 
                                   value="{{ $section->title ?: 'Por Qué Elegir ' . env('APP_NAME', 'CaballosApp') }}" required>
                            <textarea name="content" class="form-control" rows="2" 
                                      placeholder="Descripción de las ventajas del marketplace ecuestre">{{ $section->content }}</textarea>
                        </div>

                        <div class="field-group">
                            <h6><i class="fas fa-thumbs-up"></i> 4 Razones Principales</h6>
                            
                            <!-- Razón 1 -->
                            <div class="reason-preview">
                                <div class="row">
                                    <div class="col-md-2">
                                        <label class="form-label">Icono 1</label>
                                        <input type="text" name="reason_1_icon" class="form-control text-center" 
                                               value="{{ $section->getCustomData('reason_1_icon', '⭐') }}" style="font-size: 1.5rem;">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Razón 1</label>
                                        <input type="text" name="reason_1_title" class="form-control" 
                                               value="{{ $section->getCustomData('reason_1_title', 'Experiencia Ecuestre') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Descripción 1</label>
                                        <input type="text" name="reason_1_desc" class="form-control" 
                                               value="{{ $section->getCustomData('reason_1_desc', 'Años acompañando compras y cría responsable') }}">
                                    </div>
                                </div>
                            </div>

                            <!-- Razón 2 -->
                            <div class="reason-preview">
                                <div class="row">
                                    <div class="col-md-2">
                                        <label class="form-label">Icono 2</label>
                                        <input type="text" name="reason_2_icon" class="form-control text-center" 
                                               value="{{ $section->getCustomData('reason_2_icon', '🛡️') }}" style="font-size: 1.5rem;">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Razón 2</label>
                                        <input type="text" name="reason_2_title" class="form-control" 
                                               value="{{ $section->getCustomData('reason_2_title', 'Transparencia Total') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Descripción 2</label>
                                        <input type="text" name="reason_2_desc" class="form-control" 
                                               value="{{ $section->getCustomData('reason_2_desc', 'Información verificada y contratos claros') }}">
                                    </div>
                                </div>
                            </div>

                            <!-- Razón 3 -->
                            <div class="reason-preview">
                                <div class="row">
                                    <div class="col-md-2">
                                        <label class="form-label">Icono 3</label>
                                        <input type="text" name="reason_3_icon" class="form-control text-center" 
                                               value="{{ $section->getCustomData('reason_3_icon', '⚡') }}" style="font-size: 1.5rem;">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Razón 3</label>
                                        <input type="text" name="reason_3_title" class="form-control" 
                                               value="{{ $section->getCustomData('reason_3_title', 'Respuesta Rápida') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Descripción 3</label>
                                        <input type="text" name="reason_3_desc" class="form-control" 
                                               value="{{ $section->getCustomData('reason_3_desc', 'Consultas respondidas en 24h') }}">
                                    </div>
                                </div>
                            </div>

                            <!-- Razón 4 -->
                            <div class="reason-preview">
                                <div class="row">
                                    <div class="col-md-2">
                                        <label class="form-label">Icono 4</label>
                                        <input type="text" name="reason_4_icon" class="form-control text-center" 
                                               value="{{ $section->getCustomData('reason_4_icon', '✅') }}" style="font-size: 1.5rem;">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Razón 4</label>
                                        <input type="text" name="reason_4_title" class="form-control" 
                                               value="{{ $section->getCustomData('reason_4_title', 'Vendedores Validados') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Descripción 4</label>
                                        <input type="text" name="reason_4_desc" class="form-control" 
                                               value="{{ $section->getCustomData('reason_4_desc', 'Perfiles revisados y reputación visible') }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="is_active" value="1">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-save me-2"></i> Guardar Razones
                        </button>
                    </form>
                </div>
            </div>

            {{-- SECCIÓN CTA - Llamada a la Acción --}}
            @elseif($section->name === 'cta')
            <div class="section-card">
                <div class="section-header">
                    <h4><i class="fas fa-rocket me-2"></i> Llamada a la Acción <span class="badge badge-cta ms-2">CTA</span></h4>
                </div>
                <div class="section-body">
                    <form action="{{ route('admin.sections.update', [$page->id, $section->id]) }}" method="POST">
                        @csrf @method('PUT')

                        <div class="field-group">
                            <h6><i class="fas fa-bullhorn"></i> CTA Final</h6>
                            <div class="mb-3">
                                <label class="form-label">Título de CTA</label>
                                <input type="text" name="title" class="form-control" 
                                       value="{{ $section->title ?: 'Explora Servicios Ecuestres Hoy' }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Descripción</label>
                                <textarea name="content" class="form-control" rows="3" 
                                          placeholder="Texto motivacional para que contacten y exploren servicios">{{ $section->content }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Texto del Botón Principal</label>
                                <input type="text" name="button_primary_text" class="form-control" 
                                       value="{{ $section->getCustomData('button_primary_text', 'Contactar Ahora') }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Texto del Botón Secundario</label>
                                <input type="text" name="button_secondary_text" class="form-control" 
                                       value="{{ $section->getCustomData('button_secondary_text', 'Ver Más Servicios') }}">
                            </div>
                        </div>

                        <input type="hidden" name="is_active" value="1">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-save me-2"></i> Guardar CTA
                        </button>
                    </form>
                </div>
            </div>
            @endif

        @endforeach

        @if($page->sections->count() == 0)
        <div class="text-center py-5">
            <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
            <h4 class="text-warning">No hay secciones configuradas</h4>
            <p class="text-light">Las secciones se crearán automáticamente al acceder por primera vez.</p>
        </div>
        @endif

    </div>
</div>

<script>
// Función para eliminar imagen
function deleteImage(sectionName, sectionId, imageIndex) {
    if (confirm(`¿Estás seguro de eliminar esta imagen?`)) {
        fetch(`/admin/pages/{{ $page->id }}/sections/${sectionId}/images`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ image_index: imageIndex })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error al eliminar la imagen');
            }
        });
    }
}

// Prevenir submit múltiple
document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', function() {
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Guardando...';
    });
});
</script>
@endsection