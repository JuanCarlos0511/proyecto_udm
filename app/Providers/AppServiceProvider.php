<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;

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
        // Compartir variables de perfil con todas las vistas
        View::composer('*', function ($view) {
            if (Auth::check()) {
                $profileController = new ProfileController();
                $view->with([
                    'isProfileComplete' => $profileController->isProfileComplete(),
                    'hasOptionalFieldsMissing' => $profileController->hasOptionalFieldsMissing(),
                    'isProfileFullyComplete' => $profileController->isProfileFullyComplete()
                ]);
            }
        });
    }
}
