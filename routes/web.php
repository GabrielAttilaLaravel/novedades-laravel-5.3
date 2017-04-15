<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Mail\Welcome as WelcomeEmail;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;

Route::get('/', function () {
    return view('welcome');
});

Route::get('welcome', function (){
    $user = new \App\User([
       'name' => 'GabrielAttila',
        'email' => 'gabrieljmorenot@gmail.com'
    ]);

    // 1 - to: pasamos el destinatario del mensaje
    // 2 - send: pasamos la instancia del objeto Email anteriormente creada
    Mail::to($user->email, $user->name)
        ->send(new WelcomeEmail($user));
});


/**Route::get('welcome', function (){
    // 1 - vista del email
    // 2 - data de la vista
    // 3 - callbak el cual recibira el mensaje
    Mail::send('emails.welcome', ['name' => 'GabrielAttila'], function(Message $message){
        $message->to('gabrieljmorenot@gmail.com', 'GabrielAttila')
                ->from('gabrieljmorenot@gmail.com', 'Laravel')
                ->subject('Bienvenido a Laravel');
    });
});**/
