<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;
    public function user_groups()
    {
        return $this->hasMany(User_group::class,'group_id');
    }

    public function broads(){
        return $this->hasMany(Board::class,'group_id');
    }
}
