<?php

namespace App;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Post;

class User extends Authenticatable implements JWTSubject{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password','profilePicture','coverPicture',
        'followers','following','city', 'from','relationship'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'followers' => 'array',
        'following' => 'array',

    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'user_id');
    }


}
