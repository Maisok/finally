<?php

namespace App\Providers;
use App\Rules\CurrentPassword;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use App\Models\Car;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Validator::extend('current_password', function ($attribute, $value, $parameters, $validator) {
            return (new CurrentPassword)->passes($attribute, $value);
        });
       
    }
}
