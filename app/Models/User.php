<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Carbon\Carbon;
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
        'membresia_comprada_en',
        'tipo_listado_id',
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
            'membresia_comprada_en' => 'date',
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

    public function tipoListado()
    {
        return $this->belongsTo(TipoListado::class, 'tipo_listado_id');
    }

    /** Inicio y fin de la ventana de vigencia, basada en membresia_comprada_en y el plan */
    public function membershipWindow(): ?array
    {
        $plan = $this->tipoListado;
        $start = $this->membresia_comprada_en ? Carbon::parse($this->membresia_comprada_en)->startOfDay() : null;
        if (!$plan || !$start) return null;

        $end = (clone $start);
        $qty = max(1, (int)$plan->periodo_cantidad);
        switch ($plan->periodo) {
            case 'day':   $end->addDays($qty); break;
            case 'week':  $end->addWeeks($qty); break;
            case 'year':  $end->addYears($qty); break;
            default:      $end->addMonths($qty); // month por defecto
        }
        // dd($start->format('Y-m-d'), $end->format('Y-m-d'));
        return [$start, $end]; // [inclusive, exclusive)
    }

    /** Productos creados dentro de la ventana actual */
    public function productsUsedInWindow(): int
    {
        $window = $this->membershipWindow();
        if (!$window) return 0;

        [$start, $end] = $window;
        // dd($start->format('Y-m-d'), $end->format('Y-m-d'));

        return $this->products() // asumiendo relación: hasMany(Product::class)
            ->whereBetween('created_at', [$start, $end])
            ->count();
    }

    /** ¿Puede publicar otro producto? null = ilimitado */
    public function remainingQuota(): ?int
    {
        $plan = $this->tipoListado;
        if (!$plan) return 0;

        if ($plan->is_ilimitado || is_null($plan->max_productos)) return null;

        $used = $this->productsUsedInWindow();
        return max(0, (int)$plan->max_productos - $used);
    }

    public function canPublishProduct(): bool
    {
        $plan = $this->tipoListado;
        if (!$plan) return false;

        // si no hay ventana (no hay fecha de compra), no publicar
        if (!$this->membershipWindow()) return false;

        // ilimitado
        if ($plan->is_ilimitado || is_null($plan->max_productos)) return true;

        return $this->remainingQuota() > 0;
    }

    /** Fecha fin de membresía (para mostrar en UI) */
    public function membershipEndsAt(): ?Carbon
    {
        $window = $this->membershipWindow();
        return $window ? $window[1] : null;
    }
}
