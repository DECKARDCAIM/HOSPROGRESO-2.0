<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        \Illuminate\Pagination\Paginator::useBootstrapFive();

        \Illuminate\Support\Facades\View::composer('*', function ($view) {
            $latestComunicados = \App\Models\Release::with('author')
                ->where('type', 'comunicado')
                ->published()
                ->where('published_at', '<=', now())
                ->latest('published_at')
                ->take(5)
                ->get();

            $latestActualizaciones = \App\Models\Release::with('author')
                ->where('type', 'actualizacion')
                ->published()
                ->where('published_at', '<=', now())
                ->latest('published_at')
                ->take(5)
                ->get();

            $view->with('latestComunicados', $latestComunicados);
            $view->with('latestActualizaciones', $latestActualizaciones);
        });
    }
}
