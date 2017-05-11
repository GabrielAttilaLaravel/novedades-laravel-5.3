<?php

use App\{User, Post};
use App\Notifications\PostComented;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PostCommentTest extends TestCase
{
    // usamos el trait
    // usamos el trait ara que una prueba no afecte la otra
    use DatabaseTransactions;

    public function test_a_user_recives_a_notification_when_a_post_is_commented()
    {
        // llamamos al metodo fake del facades Notification para realizar las pruebas
        Notification::fake();
        // creamos un usuario con model factory
        $subscriber = factory(User::class)->create();
        $nonSubscriber = factory(User::class)->create();
        // creamos un post
        $post = factory(Post::class)->create();
        // suscribimos el usuario al post
        $post->subscribers()->attach($subscriber);

        // visitamos la url en donde comentamos un post
        $this->visit('comment/'.$post->id);
        // cuando visitemos esta url le enviaos una notificacion a los subcriptores del post
        Notification::assertSentTo($subscriber, PostComented::class,
            function ($notification, $channnels) use ($post){
                // verificamos si la notificacion es la misma del post y el canal por la cual la estamos enviando
                return $notification->post->id == $post->id
                    && $channnels == ['mail', 'database'];
            });
        // no le enviamos notificacion al usuario 2
        Notification::assertNotSentTo($nonSubscriber, PostComented::class);
    }
}
