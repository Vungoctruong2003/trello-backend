<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;
    public function board()
    {
        return $this->belongsTo(List_card::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'card_id');
    }
    public function tags()
    {
        return $this->hasMany(Tag::class, 'card_id');
    }
}
