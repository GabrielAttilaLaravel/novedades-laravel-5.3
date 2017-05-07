<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\RoutesNotifications;

class User extends Authenticatable
{
    use RoutesNotifications, HasDatabaseNotifications;

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

    public function subscriptions()
    {
        // como la relacion no es la combinacion de ambos nombres ordenados alfabeitcamente colocamos el
        // nombre de la tabla
        return $this->belongsToMany(Post::class, 'subscriptions');
    }


    public function profile()
    {
        // un usuario puede tener un perfil de usuario
        // withDefault: si el usuario no tiene perfil laravel lo creara por defecto
        return $this->hasOne(UserProfile::class)->withDefault(function ($profile){
            $profile->nickname = 'guest'.rand(100, 999);
        });
    }

    /**public function getProfileAttribute()
    {
        return $this->profile()->firstOrNew([]);
    }**/

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

    public function getNotificationPreferences()
    {
        return ['mail', 'database'];
    }
}
