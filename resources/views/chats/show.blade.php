@extends('layouts.app')

@section('title', 'Chat con ' . $other->name)

@section('content')
<div class="container py-4" style="max-width: 900px;">
    <div class="card">
        <div class="card-header d-flex align-items-center gap-2">
            <a href="{{ route('chats.index') }}" class="btn btn-sm btn-outline-secondary me-2">&larr;</a>
            <div class="fw-semibold">Chat con {{ $other->name }}</div>
        </div>

        <div id="chatBody" class="card-body" style="height: 60vh; overflow-y: auto;">
            @foreach($messages as $msg)
                @php
                    $mine = $msg->sender_id === auth()->id();
                @endphp
                <div class="d-flex mb-2 {{ $mine ? 'justify-content-end' : 'justify-content-start' }}">
                    <div class="p-2 rounded-3" style="
                        max-width: 70%;
                        background: {{ $mine ? '#DCF8C6' : '#F1F0F0' }};
                        ">
                        <div class="small">{{ $msg->content }}</div>
                        <div class="text-muted text-end" style="font-size: .75rem;">
                            {{ $msg->created_at->format('d/m H:i') }}
                        </div>
                    </div>
                </div>
            @endforeach

            {{-- Paginación (si usas paginate) --}}
            <div class="mt-2">
                {{ $messages->links() }}
            </div>
        </div>

        <div class="card-footer">
            <form action="{{ route('chats.send', $chat) }}" method="POST" class="d-flex gap-2">
                @csrf
                <input type="text" name="content" class="form-control" placeholder="Escribe un mensaje..." required maxlength="5000">
                <button class="btn btn-primary">Enviar</button>
            </form>
        </div>
    </div>
</div>

<script>
// Scroll al final del contenedor al cargar
const chatBody = document.getElementById('chatBody');
if (chatBody) chatBody.scrollTop = chatBody.scrollHeight;
</script>
@endsection