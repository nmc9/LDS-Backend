<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $casts = [
        'start_datetime' => 'datetime',
        'end_datetime' => 'datetime',
    ];

    protected $guarded = [];


    public function user(){
        $this->belongsTo(User::class);
    }
}
