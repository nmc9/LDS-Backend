<?php

namespace App\Models;

use App\Models\BringableItem;
use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bringable extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function event(){
        return $this->belongsTo(Event::class);
    }

    public function items(){
        return $this->hasMany(BringableItem::class);
    }
}
