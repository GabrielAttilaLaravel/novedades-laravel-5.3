<?php
use App\Mail\Welcome as WelcomeEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class WelcomeTest extends TestCase
{
    use DatabaseTransactions;

    public function test_a_user_recives_a_welcome_email()
    {
        // en ves de enviar email se almacena en un array temporal para efectos de prueba solamente
        Mail::fake();

        // creamos un usuario
        $user = factory(\App\User::class)->create();
        //conectamos al usuario creado
        $this->actingAs($user)
            // visitamos la pagina welcome
            ->visit('welcome');
        // hecho esto, esperamos que un email sea enviado al usuario conectado
        Mail::assertSentTo($user, WelcomeEmail::class, function ($mail) use ($user){
            return $mail->user->id == $user->id;
        });
    }
}
