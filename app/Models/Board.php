<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    use HasFactory;
    public function user_boards()
    {
        return $this->hasMany(User_board::class, 'board_id');

    }public function lists()
    {
        return $this->hasMany(List_card::class, 'board_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}
