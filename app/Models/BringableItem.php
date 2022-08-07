<?php

namespace App\Models;

use App\Models\Bringable;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BringableItem extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function bringable(){
        return $this->belongsTo(Bringable::class);
    }

    public function assigned(){
        return $this->belongsTo(User::class,"assigned_id","id");
    }
}
