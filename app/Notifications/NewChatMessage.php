<?php

namespace App\Notifications;

use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class NewChatMessage extends Notification
{
    public function __construct(public Message $message) {}

    public function via(object $notifiable): array
    {
        return ['mail']; // solo email, sin colas
    }

    public function toMail(object $notifiable): MailMessage
    {
        $chat   = $this->message->chat()->with(['user:id,name','vendedor:id,name'])->first();
        $sender = $this->message->sender()->first();
        $url    = route('chats.show', $chat);

        return (new MailMessage)
            ->subject('Nuevo mensaje de ' . $sender->name)
            ->greeting('¡Hola ' . $notifiable->name . '!')
            ->line('Tienes un nuevo mensaje en tu conversación:')
            ->line('— ' . Str::limit($this->message->content, 140))
            ->action('Abrir chat', $url)
            ->line('Enviado desde ' . config('app.name') . '.');
    }
}
