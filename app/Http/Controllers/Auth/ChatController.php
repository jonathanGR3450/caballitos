<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use App\Notifications\NewChatMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    // Lista todos los chats del usuario (sea comprador o vendedor)
    public function index(Request $request)
    {
        $userId = Auth::id();

        // Trae los chats donde participo, con:
        // - el otro participante
        // - último mensaje
        // - conteo de no leídos por chat (solo de mensajes del otro)
        $chats = Chat::query()
            ->where(function ($q) use ($userId) {
                $q->where('user_id', $userId)->orWhere('vendedor_id', $userId);
            })
            ->with([
                'user:id,name',
                'vendedor:id,name',
                'messages' => function ($q) {
                    $q->latest()->limit(1);
                },
            ])
            ->withCount([
                'messages as unread_count' => function ($q) use ($userId) {
                    $q->where('is_read', false)->where('sender_id', '!=', $userId);
                }
            ])
            ->latest('updated_at')
            ->paginate(20);

        return view('chats.index', compact('chats'));
    }

    // Muestra un chat y marca mensajes del otro como leídos
    public function show(Chat $chat)
    {
        $this->authorizeParticipant($chat);

        $userId = Auth::id();

        // Marcar como leídos los mensajes que son del otro usuario
        Message::where('chat_id', $chat->id)
            ->where('sender_id', '!=', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        // Cargar mensajes (paginados si quieres)
        $messages = $chat->messages()
            ->with('sender:id,name')
            ->orderBy('created_at', 'asc')
            ->paginate(50);

        // Identificar el "otro" usuario para mostrar en cabecera
        $other = $chat->user_id === $userId ? $chat->vendedor : $chat->user;

        return view('chats.show', compact('chat', 'messages', 'other'));
    }

    // Enviar mensaje
    public function send(Request $request, Chat $chat)
    {
        $this->authorizeParticipant($chat);

        $data = $request->validate([
            'content' => ['required', 'string', 'max:5000'],
        ]);

        $message = new Message([
            'chat_id'   => $chat->id,
            'sender_id' => Auth::id(),
            'content'   => $data['content'],
            'is_read'   => false,
        ]);

        $message->save();

        // Actualiza updated_at del chat para ordenar por actividad
        $chat->touch();

        // Notificar al otro participante
        $senderId  = Auth::user()->id;
        $recipient = $senderId === $chat->user_id ? $chat->vendedor : $chat->user;

        if ($recipient && $recipient->id !== $senderId && filter_var($recipient->email, FILTER_VALIDATE_EMAIL)) {

            // Anti-spam: máximo 1 email cada 2 min por (chat, destinatario)
            $key = "notify_chat_{$chat->id}_to_{$recipient->id}";
            $shouldNotify = Cache::add($key, true, now()->addMinutes(2));

            if ($shouldNotify) {
                try {
                    $recipient->notify(new NewChatMessage($message)); // síncrono
                } catch (\Throwable $e) {
                    // No rompas el flujo del chat por un error de correo
                    Log::warning('Fallo envío email de chat: '.$e->getMessage(), [
                        'chat_id' => $chat->id,
                        'to' => $recipient->email,
                    ]);
                }
            }
        }


        // (Opcional) emitir evento/broadcast

        return redirect()->route('chats.show', $chat)->with('sent', true);
    }

    // Crear o reutilizar chat comprador ↔ vendedor y redirigir al show
    public function start(User $vendedor)
    {
        $compradorId = Auth::id();
        abort_if($compradorId === $vendedor->id, 403, 'No puedes chatear contigo mismo.');

        // Buscar o crear el chat único
        $chat = Chat::firstOrCreate(
            ['user_id' => $compradorId, 'vendedor_id' => $vendedor->id],
            []
        );

        return redirect()->route('chats.show', $chat);
    }

    // Contador global de no leídos (para badge del navbar o AJAX)
    public function unreadCount()
    {
        $user = Auth::user();
        $count = $user->unreadMessagesCount();
        return response()->json(['count' => $count]);
    }

    // Seguridad: solo comprador o vendedor pueden ver/enviar
    private function authorizeParticipant(Chat $chat): void
    {
        $userId = Auth::id();
        if ($chat->user_id !== $userId && $chat->vendedor_id !== $userId) {
            abort(403);
        }
    }
}
