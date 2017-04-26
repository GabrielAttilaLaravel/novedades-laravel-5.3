<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // simulamos que el post tiene 5 suscriptores
    public function getSubscribersAttribute()
    {
        return User::take(5)->get();
    }
}
