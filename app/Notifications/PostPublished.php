<?php

namespace App\Notifications;

use App\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Messages\SlackAttachment;

class PostPublished extends Notification
{
    use Queueable;
    /**
     * @var Post
     */
    private $post;

    /**
     * Create a new notification instance.
     *
     * @param Post $post
     */
    public function __construct(Post $post)
    {
        //
        $this->post = $post;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['slack'];
    }

    public function toSlack()
    {
        return (new SlackMessage())
            ->success()
            ->from('GabrielAttila')
            ->content('Un nuevo post fue publicado')
            // recibe coo argumento un closer;
            ->attachment(function (SlackAttachment $attachment){
                $attachment
                    ->title($this->post->title, $this->post->url)
                    ->fields([
                    'points' => $this->post->points,
                    'author' => 'GabrielAttila',
                    'category' => 'Laravel'
                ]);
            });
    }
}
