<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Availability extends Model
{
    use HasFactory;

    protected $casts = [
        'start_time' => 'datetime:H:m:s',
        'end_time' => 'datetime:H:m:s',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
