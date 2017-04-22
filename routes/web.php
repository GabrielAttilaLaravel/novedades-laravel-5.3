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


use App\Post;
use App\User;

// usamos php artisan vendor:publish --tag=laravel-pagination
// para poder extraer el codigo que personalizamos del vendor
Route::get('posts', function (){
    $posts = Post::Paginate(5);

    return view('posts', compact('posts'));
});

Route::group(['middleware' => 'auth'], function (){
    Route::get('profile', 'ProfileController@edit');
    Route::put('profile', 'ProfileController@update');

    Route::get('profile/avatar', 'ProfileController@avatar');
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
