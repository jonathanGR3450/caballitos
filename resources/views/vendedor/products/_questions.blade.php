<div class="form-container mt-5">
    <h4 class="mb-3">❓ Preguntas de clientes</h4>

    @if($product->questions->count() > 0)
        @foreach($product->questions as $q)
            <div class="border rounded p-3 mb-3 bg-white shadow-sm">
                <div class="mb-2">
                    <strong>{{ $q->user_name }}</strong> 
                    <small class="text-muted">({{ $q->created_at->format('d/m/Y H:i') }})</small>
                </div>
                <p class="mb-2">{{ $q->question }}</p>

                {{-- Formulario para responder --}}
                <form action="{{ route('admin.questions.update', $q) }}" method="POST" class="mt-2">
                    @csrf
                    @method('PUT')
                    <div class="mb-2">
                        <textarea name="answer" rows="2" class="form-control border-secondary"
                                  placeholder="Escribe tu respuesta...">{{ old('answer', $q->answer) }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-success btn-sm">
                        💬 Guardar respuesta
                    </button>
                </form>

                @if($q->answer)
                    <div class="mt-2 p-2 bg-light rounded">
                        <strong>Respuesta actual:</strong>
                        <p class="mb-0">{{ $q->answer }}</p>
                    </div>
                @endif
            </div>
        @endforeach
    @else
        <p class="text-muted">No hay preguntas para este producto todavía.</p>
    @endif
</div>
