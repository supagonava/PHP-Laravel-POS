<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

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
        if (env('APP_DEBUG')) {
            DB::listen(function ($query) {
                Log::info(
                    $query->sql,
                    ['bindings' => $query->bindings, 'time' => $query->time]
                );
            });
        }
        // Schema::defaultStringLength(191);
        // dd([
        //     env("DB_CONNECTION"),
        //     env("DB_HOST"),
        //     env("DB_PORT"),
        //     env("DB_DATABASE"),
        //     env("DB_USERNAME"),
        //     env("DB_PASSWORD"),
        //     env("DB_SSLMODE"),
        //     env('MYSQL_ATTR_SSL_CA')
        // ]);
        // Paginator::useBootstrap();
    }
}
