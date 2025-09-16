<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'country_code',
        'category_id',
        'address',
        'message',
        'whatsapp_contact',
        'tipo_listado_id',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tipoListado()
    {
        return $this->belongsTo(TipoListado::class, 'tipo_listado_id');
    }
}
