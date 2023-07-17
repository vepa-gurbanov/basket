<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Sanctum::ignoreMigrations();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();
        Model::preventLazyLoading(!app()->isProduction());

        View::composer('admin.app.sidebar', function ($view) {
            $sidebarLinks = [
                ['admin.dashboard', 'fas fa-fw fa-tachometer-alt', 'Dashboard'],
                /** ['admin.users', 'fa-fw bi-people-fill', 'Users'] ,**/
            ];


            $data = [
                'sidebarLinks' => $sidebarLinks,
            ];
            $view->with($data);
        });
    }
}
