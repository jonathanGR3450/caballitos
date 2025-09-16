<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoListado extends Model
{
    protected $table = 'tipo_listados';

    protected $fillable = [
        'nombre',
        'slug',
        'descripcion',
        'max_productos',
        'is_ilimitado',
        'periodo',
        'periodo_cantidad',
        'precio',
        'is_activo',
    ];

    protected $casts = [
        'is_ilimitado'     => 'boolean',
        'is_activo'        => 'boolean',
        'max_productos'    => 'integer',
        'periodo_cantidad' => 'integer',
        'precio'           => 'decimal:2',
    ];

    public function products(): HasMany {
        return $this->hasMany(Product::class, 'tipo_listado_id');
    }

    public function users(): HasMany {
        return $this->hasMany(User::class, 'tipo_listado_id');
    }
}
