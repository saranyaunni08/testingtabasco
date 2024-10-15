<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */

     public function boot(): void
     {
         // Log all SQL queries
         DB::listen(function ($query) {
             logger($query->sql, $query->bindings);
         });
     }
     public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
  
}
