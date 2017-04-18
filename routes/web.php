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


use App\User;

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

Route::get('/home', 'HomeController@index');
