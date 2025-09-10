@extends('layouts.app')

@section('title','Mis chats')

@section('content')
<div class="container py-4">
    <h1 class="h4 mb-4">Mis chats</h1>

    @forelse($chats as $chat)
        @php
            $yo = auth()->id();
            $other = $chat->user_id === $yo ? $chat->vendedor : $chat->user;
            $last = $chat->messages->first(); // porque with(['messages' => latest()->limit(1)])
        @endphp

        <a href="{{ route('chats.show', $chat) }}" class="text-decoration-none text-body">
            <div class="card mb-3">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-secondary bg-opacity-25 d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                            <span class="fw-bold">{{ strtoupper(substr($other->name,0,1)) }}</span>
                        </div>
                        <div>
                            <div class="fw-semibold">{{ $other->name }}</div>
                            <div class="text-muted small">
                                @if($last)
                                    <span>{{ $last->sender_id === $yo ? 'Tú: ' : '' }}{{ Str::limit($last->content, 60) }}</span>
                                    <span class="ms-2">· {{ $last->created_at->diffForHumans() }}</span>
                                @else
                                    <em>Sin mensajes aún</em>
                                @endif
                            </div>
                        </div>
                    </div>
                    @if($chat->unread_count > 0)
                        <span class="badge bg-danger">{{ $chat->unread_count }}</span>
                    @endif
                </div>
            </div>
        </a>
    @empty
        <div class="text-muted">No tienes chats por ahora.</div>
    @endforelse

    <div class="mt-3">
        {{ $chats->links() }}
    </div>
</div>
@endsection
