<?php

namespace App\Providers;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // podriamos utilizar una plantilla personalizada para el admin solamente si lo deseamos y no la de boostrap
        if (Request::segment(1) != 'admin'){
            LengthAwarePaginator::defaultView('partials/default-pagination');
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
