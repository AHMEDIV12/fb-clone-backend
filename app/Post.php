<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Post extends Model
{
    
    protected $fillable = [
        'title', 'desc', 'likes','user_id','img'
    ];


    protected $casts = [
        'likes' => 'array',
    ];

        public function user()
        {
            return $this->belongsTo(User::class, 'user_id');
        }
    }

