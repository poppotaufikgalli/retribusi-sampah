<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use View;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

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

        Str::macro('currency', function ($price)
        {
            return "Rp.".number_format($price, 0, '.', '.');
        });

        Str::macro('rupiah', function ($price)
        {
            return "Rp.".number_format($price, 0, '.', '.');
        });

        Str::macro('idDate', function ($date)
        {
            return Carbon::parse($date)->locale('id')->translatedFormat('d F Y');
        });

        View::composer('*', function($view)
        {
            $ctl = new Controller;
            $konfig = $ctl->GetKonfigurasi();
            $view->with('konfig', $konfig);
        });
    }
}
