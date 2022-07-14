<?php

namespace App\Models;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    use HasFactory;

    public $guarded = [];
    
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function event(){
        return $this->belongsTo(Event::class);
    }

    public function inviter(){
        return $this->belongsTo(User::class,"inviter_id");
    }
}
