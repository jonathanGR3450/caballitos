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
        'tipo_listado',
        'address',
        'message',
        'whatsapp_contact',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
