<?php

namespace App\Notifications;

use App\UserProfile;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ProfileUpdated extends Notification
{
    use Queueable;
    /**
     * @var UserProfile
     */
    private $userProfile;

    /**
     * Create a new notification instance.
     * @param UserProfile $userProfile
     */
    public function __construct(UserProfile $userProfile)
    {
        $this->userProfile = $userProfile;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    // recibimos el modelo que va hacer notificado
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    // metodo que se va a implementar para enviar la notificacion
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->success()
                    // sino colocas este metodo se colocara el nombre de la clase por defecto
                    ->subject('You profile was update!')
                    ->line('Your profile was updated')
                    ->action('View your profile', url('profile'))
                    ->line("If you didn't update your prifile, you are un trouble!")
                    ->line("Please chance your password o contact us");
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
