<?php

use App\User;
use Illuminate\Foundation\Inspiring;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');

Artisan::command('attila:welcome {name=Invitado} {--back}', function ($name) {
    if ($this->option('back')){
        $this->info("Welcome back GabrielAttila!, {$name}");
    }else{
        $this->info("Welcome to GabrielAttila!, {$name}");
    }
})->describe('Welcome a user to our project');

