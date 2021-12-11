<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Broad extends Model
{
    use HasFactory;
    public function user_boards()
    {
        return $this->hasMany(User_board::class, 'board_id');

    }public function lists()
    {
        return $this->hasMany(List_card::class, 'board_id');
    }
}
