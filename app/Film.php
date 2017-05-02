<?php

namespace App;

use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;

class Film extends Model
{

    // incluimos el trait de scout para su funcionamiento
    use Searchable;

    protected $table = 'film';

    protected $primaryKey = 'film_id';

    public $timestamps = false;

    public function actors()
    {
        return $this->belongsToMany(Actor::class, 'film_actor');
    }
}
