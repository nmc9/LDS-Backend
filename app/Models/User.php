<?php

namespace App\Models;

use App\Models\Availability;
use App\Models\BringableItem;
use App\Models\Event;
use App\Models\Friend;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function availabilities(){
        return $this->hasMany(Availability::class);
    }

    public function ownedEvents(){
        return $this->hasMany(Event::class,"owner_id","id");
    }

    public function events(){
        return $this->belongsToMany(Event::class, 'invitations');
    }


    public function sentFriends(){
        return $this->belongsToMany(User::class, "friends", "from_user_id", "to_user_id")
        ->withPivot(["accepted","token"])
        ->wherePivot('accepted', 1);
    }

    public function receievedFriends(){
        return $this->belongsToMany(User::class, "friends", "to_user_id", "from_user_id")
        ->withPivot(["accepted","token"])
        ->wherePivot('accepted', 1);
    }

    public function allSentFriends(){
        return $this->belongsToMany(User::class, "friends", "from_user_id", "to_user_id")
        ->withPivot(["accepted","token"]);
    }

    public function allReceievedFriends(){
        return $this->belongsToMany(User::class, "friends", "to_user_id", "from_user_id")
        ->withPivot(["accepted","token"]);
    }

    public function invitations(){
        return $this->hasMany(Invitation::class);
    }

    public function bringableItems(){
        return $this->hasMany(BringableItem::class,'assigned_id');
    }

}
