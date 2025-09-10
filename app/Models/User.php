<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles; 

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_verified',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function buyerChats()
    {
        return $this->hasMany(Chat::class, 'user_id');
    }

    /**
     * Chats donde el usuario es el vendedor
     */
    public function sellerChats()
    {
        return $this->hasMany(Chat::class, 'vendedor_id');
    }

    /**
     * Todos los chats relacionados con el usuario (como comprador o vendedor)
     */
    public function chats()
    {
        return Chat::where('user_id', $this->id)
                    ->orWhere('vendedor_id', $this->id);
    }

    /**
     * Mensajes enviados por este usuario
     */
    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    /**
     * Contar mensajes no leídos de todos los chats
     */
    public function unreadMessagesCount()
    {
        return Message::whereHas('chat', function($q) {
                $q->where('user_id', $this->id)
                  ->orWhere('vendedor_id', $this->id);
            })
            ->where('is_read', false)
            ->where('sender_id', '<>', $this->id)
            ->count();
    }

    public function favoriteProducts()
    {
        return $this->belongsToMany(Product::class, 'favorite_products')->withTimestamps();
    }
}
