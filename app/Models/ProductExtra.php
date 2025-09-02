<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductExtra extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'ubicacion',
        'raza',
        'edad',
        'genero',
        'pedigri',
        'entrenamiento',
        'historial_salud',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
