<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $fillable = ['user_id', 'vendedor_id'];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function vendedor() {
        return $this->belongsTo(User::class, 'vendedor_id');
    }

    public function messages() {
        return $this->hasMany(Message::class);
    }

    public function unreadMessages() {
        return $this->messages()->where('is_read', false);
    }
}
