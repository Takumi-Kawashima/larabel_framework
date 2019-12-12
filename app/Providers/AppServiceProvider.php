<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        if(config('app.env') !== 'production'){
          \DB::listen(function($query) { //$queryに自動的にクエリ情報が入ってくる
            \Log::info("Query Time:{$query->time}s] $query->sql");
          });
        }
    }
}
