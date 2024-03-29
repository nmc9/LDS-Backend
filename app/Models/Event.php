<?php

namespace App\Models;

use App\Library\Constants;
use App\Models\Invitation;
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


    public function owner(){
        $this->belongsTo(User::class,"id","owner_id");
    }

    public function users(){
        return $this->belongsToMany(User::class, 'invitations');
    }

    public function invitations(){
        return $this->hasMany(Invitation::class);
    }

    public function acceptedUsers(){
        return $this->belongsToMany(User::class, 'invitations')
        ->withPivot(["status","token"])
        ->wherePivot('status',Constants::INVITATION_ACCEPTED);
    }

    public function pendingUsers(){
        return $this->belongsToMany(User::class, 'invitations')
        ->withPivot(["status","token"])
        ->wherePivot('status',Constants::INVITATION_PENDING);
    }

    public function bringables(){
        return $this->hasMany(Bringable::class);
    }
}
