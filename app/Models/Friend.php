<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Friend extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function toUser(){
        return $this->belongsTo(User::class,'to_user_id');
    }


    public function fromUser(){
        return $this->belongsTo(User::class,'from_user_id');
    }
}
