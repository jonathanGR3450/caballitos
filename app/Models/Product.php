<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    const ESTADO_DISPONIBLE = 'Disponible';
    const ESTADO_PENDIENTE  = 'Pendiente';
    const ESTADO_VENDIDO    = 'Vendido';

    const TIPO_LISTADO_NORMAL = 'normal';
    const TIPO_LISTADO_DESTACADO = 'destacado';
    const TIPO_LISTADO_PREMIUM = 'premium';

    /* 1️⃣  habilita asignación masiva  */
    protected $fillable = [
        'name',
        'description',
        'price',
        'avg_weight',
        'stock',        // ← AGREGAR ESTE CAMPO
        'image',
        'category_id',
        'interest',
        'estado',
        'vence',
        'fecha_vencimiento',
        'tipo_listado',
    ];

    // Scope para obtener productos vencidos
    public function scopeVencidos($query)
    {
        return $query->where('vence', true)
                     ->whereDate('fecha_vencimiento', '<', now());
    }

    // Scope para productos vigentes
    public function scopeVigentes($query)
    {
        return $query->where(function ($q) {
            $q->where('vence', false)
              ->orWhereDate('fecha_vencimiento', '>=', now());
        });
    }

    // Accesor para saber si está vencido
    public function getEstaVencidoAttribute()
    {
        return $this->vence && $this->fecha_vencimiento && $this->fecha_vencimiento < now();
    }


    public function category()
    {
        return $this->belongsTo(\App\Models\Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }
    public function prices()
    {
        return $this->hasMany(Price::class);
    }
    public function extra()
    {
        return $this->hasOne(ProductExtra::class);
    }

    public static function getEstados()
    {
        return [
            self::ESTADO_DISPONIBLE,
            self::ESTADO_PENDIENTE,
            self::ESTADO_VENDIDO,
        ];
    }

    public static function getTiposListado()
    {
        return [
            self::TIPO_LISTADO_NORMAL,
            self::TIPO_LISTADO_DESTACADO,
            self::TIPO_LISTADO_PREMIUM,
        ];
    }


}
