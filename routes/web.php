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
use App\Post;
use App\Notifications\PostPublished;
use App\SlackTeam;
use App\User;
use App\Notifications\Follower;
use App\DatabaseNotification;
use Nexmo\Laravel\Facade\Nexmo;

// usamos php artisan vendor:publish --tag=laravel-pagination
// para poder extraer el codigo que personalizamos del vendor
Route::get('posts', function (){
    // usamos Eager Loading (se utiliza para resolver problemas de N+1) "with"
    // Post::with('user') = traeme todos los post con sus autores unicamente con los campos id y name
    $posts = Post::with('user:id,name')
                ->select('id', 'title', 'user_id')
                ->orderBy('title', 'ASC')
                ->Paginate(15);

    return view('posts', compact('posts'));
});

//
Route::get('subscribe/{post}', function (Post $post){
    // conectamos al uruaio con el id 1
    auth()->loginUsingId(1);

    $user = auth()->user();

    $user->subscriptions()->toggle($post);


    // imprimimos las subscriptiones del usuario
    dd($user->subscriptions);
});

//creamos una ruta para simular la notificacion del usuario que va a seguir y el seguido
Route::get('follow/{follower}/{followed}', function (User $follower, User $followed){
    // metodo send():
    // 1- enviamos el usuario al cual quiero enviarle la notificacion
    // 2- pasamos una instancia del objeto de la notificacion
    // Clase Follower:
    // le pasamos el seguidor
    Notification::send($followed, new Follower($follower));
});

Route::get('comment/{post}', function (Post $post){
    // Write comment...

    // enviamos la notificacion ej: si tenemos los subscriptores del post le enviamos que hay un nuevo
    // comentario
    Notification::send($post->subscribers, new \App\Notifications\PostComented($post));
});

Route::group(['middleware' => 'auth'], function (){
    Route::get('profile', 'ProfileController@edit');
    Route::put('profile', 'ProfileController@update');

    Route::get('profile/avatar', 'ProfileController@avatar');

    Route::get('notifications', function (){
        // notifications: mostramos todas las notificaciones
        // unreadNotifications: mostramos las notificaciones no leidas
        $notifications = auth()->user()->notifications;

        return view('notifications', compact('notifications'));
    });

    Route::get('notifications/read-all', function (){
        // obtenemos las notificaciones al usuario conectado
        auth()->user()->notifications->markAsRead();
        // redirigimos a la pantalla anterior
        return back();
    });

    Route::get('notifications/{notification}', function (DatabaseNotification $notification){
        // abortmos a menos que el usuario conectado sea igual al usuario de la notificacion
        // y para estar 100% seguro verificamos el tipo de notificacion
        abort_unless($notification->associatedTo(auth()->user()), 404);
        // markAsRead: marcmos una notificacion como leida
        $notification->markAsRead();

        // redirigimos al usuario
        return redirect($notification->redirec_url);
    });


    Route::get('profile/{user}', function (User $user){
        dd($user);
    });

    Route::get('posts/publish/{post}', function (Post $post){
      // Publish post here...

        //
        (new SlackTeam())->notify(new PostPublished($post));

        return 'Done!';
    });
    Route::get('posts/{post}', function (Post $post){
        dd($post);
    });
});

Route::get('/', function () {
    Nexmo::message()->send([
        'to' => '584128502368',
        'from' => '584128502368',
        'text' => 'Mensaje enviado con Nexmo'
    ]);
});

Route::get('welcome', function (){
    $user = auth()->user();
    // 1 - to: pasamos el destinatario del mensaje
    // 2 - send: pasamos la instancia del objeto Email anteriormente creada
    Mail::to($user->email, $user->name)
        ->cc('cc@example.com')
        ->bcc('cc@example.com')
        ->send(new WelcomeEmail($user));
});
/**
Route::get('welcome', function (){
    $user = auth()->user();

    Mail::to($user->email, $user->name)
        ->cc('cc@example.com')
        ->bcc('cc@example.com')
        ->send(new WelcomeEmail($user));
});**/

Route::get('admin', function(){
    return User::getAdmin();
});

Route::get('points', function (){
    $user = User::find(1);

    return $user->posts->avg(function ($post){
        return $post->points;
    });
});
// Route::auth(); laravel 5.2
Auth::routes();
Route::get('/home', 'HomeController@index');
