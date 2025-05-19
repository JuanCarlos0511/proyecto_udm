<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\ProfileController;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Aquí puedes registrar servicios personalizados si los necesitas
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 🔐 Forzar HTTPS en producción
        if (env('APP_ENV') === 'production') {
            URL::forceScheme('https');
        }

        // 📡 Compartir variables de perfil con todas las vistas
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