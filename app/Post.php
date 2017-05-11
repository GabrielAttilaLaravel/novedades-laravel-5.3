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
    public function subscribers()
    {
        return $this->belongsToMany(User::class, 'subscriptions');
    }

    public function getUrlAttribute()
    {
        return url('posts/'.$this->id);
    }
}
