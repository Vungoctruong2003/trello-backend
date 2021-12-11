<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class List_card extends Model
{
    use HasFactory;
    public function board()
    {
        return $this->belongsTo(Broad::class);
    }
    public function cards()
    {
        return $this->hasMany(Card::class, 'list_id');
    }
}
