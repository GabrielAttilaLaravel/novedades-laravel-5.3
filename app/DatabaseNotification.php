<?php
/**
 * Created by PhpStorm.
 * User: gabriel
 * Date: 26/04/17
 * Time: 05:26 AM
 */

namespace App;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\DatabaseNotificationCollection;

class DatabaseNotification extends Model
{
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'notifications';

    /**
     * The guarded attributes on the model.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
    ];

    // pasamos el objeto al cual vamos a notificar
    public function associatedTo($notifiable)
    {
        return $this->notifiable_id == $notifiable->id() && $this->notifiable_type == get_class($notifiable);
    }

    public function getKeyAttribute()
    {
        // palabras separadas por - y en minuscula
        return snake_case(class_basename($this->type), '-');
    }

    public function getDescriptionAttribute()
    {
        return trans('notifications.'.$this->key, $this->data);
    }

    public function getIsNewAttribute()
    {
        return $this->read_at == null;
    }

    // retornamos la url de la notificacion
    public function getUrlAttribute()
    {
        return url('notifications/'.$this->id);
    }

    public function getDateAttribute()
    {
        return $this->created_at->format('d/m/Y h:ia');
    }

    // retornamos la url de la notificacion a la cual debe redirigir
    public function getRedirecUrlAttribute()
    {
        // dependiendo del tipo de notificacion lo enviamos a una url
        switch ($this->type){
            case 'App\Notifications\Follower':
                return redirect('profile/'.$this->data['follower_id']);
        }
    }

    public function getRedirectUrlAttribute()
    {
        return $this->data['redirect_url'];
    }

    /**
     * Get the notifiable entity that the notification belongs to.
     */
    public function notifiable()
    {
        return $this->morphTo();
    }

    /**
     * Mark the notification as read.
     *
     * @return void
     */
    public function markAsRead()
    {
        if (is_null($this->read_at)) {
            $this->forceFill(['read_at' => $this->freshTimestamp()])->save();
        }
    }

    /**
     * Determine if a notification has been read.
     *
     * @return bool
     */
    public function read()
    {
        return $this->read_at !== null;
    }

    /**
     * Determine if a notification has not been read.
     *
     * @return bool
     */
    public function unread()
    {
        return $this->read_at === null;
    }

    /**
     * Create a new database notification collection instance.
     *
     * @param  array  $models
     * @return \Illuminate\Notifications\DatabaseNotificationCollection
     */
    public function newCollection(array $models = [])
    {
        return new DatabaseNotificationCollection($models);
    }
}