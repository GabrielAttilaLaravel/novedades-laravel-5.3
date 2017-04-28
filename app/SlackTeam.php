<?php
/**
 * Created by PhpStorm.
 * User: gabriel
 * Date: 27/04/17
 * Time: 11:28 AM
 */

namespace App;


use Illuminate\Notifications\RoutesNotifications;

class SlackTeam // extends Model
{
    // implementamos el trait
    use RoutesNotifications;

    public function routeNotificationForSlack()
    {
        //return env('SLACK_WEBHOOK');
        return config('services.slack.webhook');
        // almacenamos en la db
        //return $this->slack_webhook;
    }
}