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


use App\Notifications\PostComented;
use App\User;
use App\Post;
use App\Notifications\Follower;
use App\DatabaseNotification;

// usamos php artisan vendor:publish --tag=laravel-pagination
// para poder extraer el codigo que personalizamos del vendor
Route::get('posts', function (){
    $posts = Post::Paginate(5);

    return view('posts', compact('posts'));
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
    Notification::send($post->subscribers, new PostComented($post));
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

    Route::get('posts/{post}', function (Post $post){
        dd($post);
    });
});

Route::get('/', function () {
    return view('welcome');
});

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
