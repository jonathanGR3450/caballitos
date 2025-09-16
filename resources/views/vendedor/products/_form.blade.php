@csrf


<div class="form-container">

<div class="mb-4">
    <label class="form-label fw-bold ">Product Name *</label>
    <input type="text" name="name" class="form-control   border-secondary"
           value="{{ old('name', $product->name ?? '') }}" required>
</div>

<div class="mb-4">
  <label class="form-label fw-bold ">Description</label>
  <textarea id="description" name="description" rows="6" class="form-control   border-secondary">{{ old('description', $product->description ?? '') }}</textarea>
</div>


<div class="row">
    <div class="col-md-4 mb-4">
        <label class="form-label fw-bold ">Stock *</label>
        <input type="number" name="stock" class="form-control   border-secondary"
               value="{{ old('stock', $product->stock ?? 0) }}" required>
    </div>

    <div class="col-md-4 mb-4">
        <label class="form-label fw-bold ">Info adicional tarjeta</label>
        <input type="text" name="avg_weight" class="form-control   border-secondary"
               value="{{ old('avg_weight', $product->avg_weight ?? '') }}" placeholder="e.g. 7 Lbs or 3.2 Kg">
    </div>

    {{-- input estado, default disponible --}}
    <div class="col-md-4 mb-4">
        <label class="form-label fw-bold">Estado</label>
        <select name="estado" class="form-control border-secondary">
            @foreach(\App\Models\Product::getEstados() as $estado)
                <option value="{{ $estado }}"
                    {{ old('estado', $product->estado ?? 'Disponible') == $estado ? 'selected' : '' }}>
                    {{ $estado }}
                </option>
            @endforeach
        </select>
    </div>

</div>
<div class="form-check mb-3">
    <input type="hidden" name="vence" value="0">
    <input type="checkbox" class="form-check-input" id="vence" name="vence" 
           value="1" {{ old('vence', $product->vence ?? false) ? 'checked' : '' }}>
    <label for="vence" class="form-check-label">¿Tiene vencimiento?</label>
</div>

<div id="fecha-vencimiento-container" 
     style="display: {{ old('vence', $product->vence ?? false) ? 'block' : 'none' }}">
    <label for="fecha_vencimiento">Fecha de vencimiento</label>
    <input type="date" id="fecha_vencimiento" name="fecha_vencimiento"
           class="form-control @error('fecha_vencimiento') is-invalid @enderror"
           value="{{ old('fecha_vencimiento', $product->fecha_vencimiento ?? '') }}">
    @error('fecha_vencimiento')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
<div class="row mb-4 mt-4">
    <div class="col-md-12">
        <div class="form-check">
            <input type="hidden" name="has_extras" value="0">
            <input type="checkbox"
                   class="form-check-input"
                   id="hasExtras"
                   name="has_extras"
                   value="1"
                   {{ old('has_extras', isset($product) && $product->extra ? 1 : 0) ? 'checked' : '' }}>
            <label class="form-check-label fw-bold" for="hasExtras">
                ¿Tiene campos extras?
            </label>
        </div>
    </div>
</div>
<div id="extrasFields" style="display: none;">
    <div class="row">
        <div class="col-md-4 mb-4">
            <label class="form-label fw-bold">Ubicación</label>
            <input type="text" name="ubicacion" class="form-control border-secondary"
                   value="{{ old('ubicacion', $product->extra->ubicacion ?? '') }}">
        </div>

        <div class="col-md-4 mb-4">
            <label class="form-label fw-bold">Raza</label>
            <input type="text" name="raza" class="form-control border-secondary"
                   value="{{ old('raza', $product->extra->raza ?? '') }}">
        </div>

        <div class="col-md-4 mb-4">
            <label class="form-label fw-bold">Edad</label>
            <input type="number" name="edad" class="form-control border-secondary"
                   value="{{ old('edad', $product->extra->edad ?? '') }}">
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <label class="form-label fw-bold">Género</label>
            <select name="genero" class="form-control border-secondary">
                <option value="">-- Seleccionar --</option>
                <option value="Macho" {{ old('genero', $product->extra->genero ?? '') == 'Macho' ? 'selected' : '' }}>Macho</option>
                <option value="Hembra" {{ old('genero', $product->extra->genero ?? '') == 'Hembra' ? 'selected' : '' }}>Hembra</option>
            </select>
        </div>

        <div class="col-md-4 mb-4">
            <label class="form-label fw-bold">Pedigrí</label>
            <select name="pedigri" class="form-control border-secondary">
                <option value="">-- Seleccionar --</option>
                <option value="1" {{ old('pedigri', $product->extra->pedigri ?? '') == '1' ? 'selected' : '' }}>Sí</option>
                <option value="0" {{ old('pedigri', $product->extra->pedigri ?? '') == '0' ? 'selected' : '' }}>No</option>
            </select>
        </div>

        <div class="col-md-4 mb-4">
            <label class="form-label fw-bold">Entrenamiento</label>
            <input type="text" name="entrenamiento" class="form-control border-secondary"
                   value="{{ old('entrenamiento', $product->extra->entrenamiento ?? '') }}">
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 mb-4">
            <label class="form-label fw-bold">Historial de Salud</label>
            <textarea name="historial_salud" class="form-control border-secondary" rows="3">{{ old('historial_salud', $product->extra->historial_salud ?? '') }}</textarea>
        </div>
    </div>
</div>


<div class="mb-4">
    <label class="form-label fw-bold ">Product Images</label>
    <input type="file" name="images[]" class="form-control   border-secondary" multiple>
    
    @if(isset($product) && $product->images && $product->images->count() > 0)
        <small class="text-muted d-block mt-2 mb-3">
            Currently has {{ $product->images->count() }} image{{ $product->images->count() > 1 ? 's' : '' }}
        </small>
        
        <!-- Grid de imágenes existentes -->
        <div class="row mt-3" id="images-container">
            @foreach($product->images as $image)
                <div class="col-md-3 col-sm-4 col-6 mb-3" id="image-{{ $image->id }}">
                    <div class="position-relative border border-secondary rounded p-2" >
                        <!-- Imagen -->
                        <img src="{{ Storage::url($image->image) }}" 
                             class="img-fluid rounded mb-2" 
                             style="height: 120px; width: 100%; object-fit: cover;" 
                             alt="Product image">
                        
                        <!-- Botón de eliminar -->
                        <button type="button" 
                                class="btn btn-danger btn-sm w-100 d-flex align-items-center justify-content-center gap-1"
                                onclick="deleteImageAjax({{ $image->id }})">
                            <span>🗑️</span> Delete
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
<div class="mb-4">
    <label class="form-label fw-bold ">Category *</label>
  <select name="category_id" class="form-select   border-secondary" required>
    <option value="">-- Select a category --</option>
    @foreach($categories as $cat)
        <option value="{{ $cat->id }}"
            {{ old('category_id', $product->category_id ?? '') == $cat->id ? 'selected' : '' }}>
            {{ $cat->name }}
        </option>
    @endforeach
</select>
</div>
<div class="mb-4">
    <label class="form-label fw-bold ">Impuestos y Envío por País/Ciudad</label>

    <div id="price-location-container">
        @if(isset($product) && $product->prices->count() > 0)
            {{-- Si hay configuraciones existentes, mostrarlas --}}
            @foreach($product->prices as $i => $price)
                <div class="row border rounded p-3 mb-3 ">
                    <div class="col-md-3 mb-2">
                        <label class="form-label ">Countrys</label>
                        <select name="prices[{{ $i }}][country_id]" class="form-select  " onchange="loadCities(this)">
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}" {{ $price->country_id == $country->id ? 'selected' : '' }}>
                                    {{ $country->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 mb-2">
                        <label class="form-label ">Cities</label>
                        <select name="prices[{{ $i }}][city_id]" class="form-select  ">
                            @php
                                $countryWithCities = $countries->firstWhere('id', $price->country_id);
                            @endphp

                            @if($countryWithCities && $countryWithCities->cities)
                                @foreach($countryWithCities->cities as $city)
                                    <option value="{{ $city->id }}" {{ $price->city_id == $city->id ? 'selected' : '' }}>
                                        {{ $city->name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="col-md-2 mb-2">
                        <label class="form-label ">Impuesto</label>
                        <input type="number" 
                               name="prices[{{ $i }}][interest]" 
                               class="form-control  " 
                               value="{{ $price->interest }}"
                               step="0.01"
                               placeholder="15.5">
                    </div>

                    <div class="col-md-2 mb-2">
                        <label class="form-label ">Costo Envío</label>
                        <input type="number" 
                               name="prices[{{ $i }}][shipping]" 
                               class="form-control  " 
                               value="{{ $price->shipping }}"
                               step="0.01">
                    </div>

                    <div class="col-md-2 mb-2 d-flex align-items-end">
                        <button type="button" class="btn btn-danger btn-sm w-100" onclick="removePriceBlock(this)">
                            🗑️ Eliminar
                        </button>
                    </div>
                </div>
            @endforeach
        @else
            {{-- Si no hay configuraciones, mostrar un bloque vacío --}}
            <div class="row border rounded p-3 mb-3 ">
                <div class="col-md-3 mb-2">
                    <label class="form-label ">País</label>
                    <select name="prices[0][country_id]" class="form-select  " onchange="loadCities(this)">
                        <option value="">-- Selecciona país --</option>
                        @foreach($countries as $country)
                            <option value="{{ $country->id }}">{{ $country->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3 mb-2">
                    <label class="form-label ">Ciudad</label>
                    <select name="prices[0][city_id]" class="form-select  ">
                        <option value="">-- Selecciona país primero --</option>
                    </select>
                </div>

                <div class="col-md-2 mb-2">
                    <label class="form-label ">Impuesto (%)</label>
                    <input type="number" 
                           name="prices[0][interest]" 
                           class="form-control  " 
                           value="0"
                           step="0.01"
                           placeholder="15.5">
                </div>

                <div class="col-md-2 mb-2">
                    <label class="form-label ">Costo Envío</label>
                    <input type="number" 
                           name="prices[0][shipping]" 
                           class="form-control  " 
                           value="0"
                           step="0.01">
                </div>

                <div class="col-md-2 mb-2 d-flex align-items-end">
                    <button type="button" class="btn btn-danger btn-sm w-100" onclick="removePriceBlock(this)">
                        🗑️ Eliminar
                    </button>
                </div>
            </div>
        @endif
    </div>

    <button type="button" class="btn btn-outline-light btn-sm" onclick="addPriceBlock()">
        ➕ Añadir configuración por ubicación
    </button>
</div>

{{-- El precio base del producto se mantiene separado --}}
<div class="col-md-4 mb-4">
    <label class="form-label fw-bold ">Precio Base *</label>
    <input type="number" name="price" step="0.01" class="form-control   border-secondary"
           value="{{ old('price', $product->price ?? 0) }}" required>
    <small class="text-muted">Este es el precio base del producto</small>
</div>

<div class="d-flex justify-content-between mt-4">
    <button class="btn btn-success px-4">💾 Save Product</button>
    <a href="{{ route('vendedor.products.index') }}" class="btn btn-secondary">Cancel</a>
</div>

</div>

<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>


<script>
    document.getElementById('vence').addEventListener('change', function() {
        document.getElementById('fecha-vencimiento-container').style.display = this.checked ? 'block' : 'none';
    });

    document.addEventListener("DOMContentLoaded", function () {
        const check = document.getElementById("hasExtras");
        const extras = document.getElementById("extrasFields");

        function toggleExtras() {
            extras.style.display = check.checked ? "block" : "none";
        }

        toggleExtras(); // Inicializa al cargar
        check.addEventListener("change", toggleExtras);
    });

    ClassicEditor
  .create(document.querySelector('#description'), {
    toolbar: [
      'heading', '|',
      'bold','italic','underline','link','bulletedList','numberedList','blockQuote','undo','redo'
    ],
    link: { addTargetToExternalLinks: true },
    removePlugins: [
      'CKBox','CKFinder','EasyImage','ImageUpload','MediaEmbed','Table','TableToolbar'
    ]
  })
  .then(editor => {
    // Ajuste visual para dark mode
    const editable = editor.ui.getEditableElement();
  })
  .catch(console.error);
let priceIndex = {{ isset($product) && $product->prices->count() > 0 ? $product->prices->count() : 1 }};
const countries = @json($countries);

function addPriceBlock() {
    let block = `
        <div class="row border rounded p-3 mb-3 ">
            <div class="col-md-3 mb-2">
                <label class="form-label ">País</label>
                <select name="prices[\${priceIndex}][country_id]" class="form-select  " onchange="loadCities(this)">
                    <option value="">-- Selecciona país --</option>
                    ${countries.map(c => `<option value="${c.id}">${c.name}</option>`).join('')}
                </select>
            </div>

            <div class="col-md-3 mb-2">
                <label class="form-label ">Ciudad</label>
                <select name="prices[\${priceIndex}][city_id]" class="form-select  ">
                    <option value="">-- Selecciona país primero --</option>
                </select>
            </div>

            <div class="col-md-2 mb-2">
                <label class="form-label ">Impuesto (%)</label>
                <input type="number" 
                       name="prices[\${priceIndex}][interest]" 
                       class="form-control  " 
                       value="0"
                       step="0.01"
                       placeholder="15.5">
            </div>

            <div class="col-md-2 mb-2">
                <label class="form-label ">Costo Envío</label>
                <input type="number" 
                       name="prices[\${priceIndex}][shipping]" 
                       class="form-control  " 
                       value="0"
                       step="0.01">
            </div>

            <div class="col-md-2 mb-2 d-flex align-items-end">
                <button type="button" class="btn btn-danger btn-sm w-100" onclick="removePriceBlock(this)">
                    🗑️ Eliminar
                </button>
            </div>
        </div>
    `;
    document.getElementById('price-location-container').insertAdjacentHTML('beforeend', block);
    priceIndex++;
}

function loadCities(select) {
    const countryId = parseInt(select.value);
    const cities = countries.find(c => c.id === countryId)?.cities || [];
    const citySelect = select.parentElement.nextElementSibling.querySelector('select');

    citySelect.innerHTML = `<option value="">-- Selecciona ciudad --</option>`;
    citySelect.innerHTML += cities.map(c => `<option value="${c.id}">${c.name}</option>`).join('');
}

function removePriceBlock(button) {
    button.closest('.row').remove();
}
</script>