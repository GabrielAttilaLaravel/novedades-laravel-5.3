<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function profile()
    {
        // un usuario puede tener un perfil de usuario
        return $this->hasOne(UserProfile::class);
    }

    public function getProfileAttribute()
    {
        return $this->profile()->firstOrNew([]);
    }

    // optenemos el administrador del sistema
    public static function getAdmin()
    {
        return static::firstOrCreate([
            'email' => 'gabrieljmorenot@gmail.com'
        ], [
            'name' => 'GabrieilAttila',
            'password' => bcrypt('123123')
        ]);

        // obtenemos el primer registro y si da un error envia un error 404
        /**$admin = static::where('email', 'gabrieljmorenot@gmail.com')->first();

        if ($admin == null){
            $admin = User::create([
                'name' => 'GabrieilAttila',
                'email' => 'gabrieljmorenot@gmail.com',
                'password' => bcrypt('123123')
            ]);
        }
        return $admin;**/
    }
}
