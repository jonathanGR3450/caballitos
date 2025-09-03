<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_name',
        'user_email',
        'question',
        'answer',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
