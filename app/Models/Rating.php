<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendedor_id', // el usuario que recibe la calificación
        'user_id',     // el usuario que califica
        'score',       // puntuación (1-5)
        'comment',     // comentario opcional
    ];

    /**
     * El vendedor al que pertenece la calificación
     */
    public function vendedor()
    {
        return $this->belongsTo(User::class, 'vendedor_id');
    }

    /**
     * El usuario que hizo la calificación
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
