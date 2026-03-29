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
        \Illuminate\Support\Carbon::setLocale('es');
        \Illuminate\Pagination\Paginator::useBootstrapFive();

        \Illuminate\Support\Facades\View::composer('*', function ($view) {
            $user = auth()->user();
            
            $latestComunicados = \App\Models\Release::with(['author', 'readByUsers' => function($q) use ($user) {
                if ($user) $q->where('user_id', $user->id);
            }])
                ->where('type', 'comunicado')
                ->published()
                ->where('published_at', '<=', now())
                ->latest('published_at')
                ->take(5)
                ->get();

            $latestActualizaciones = \App\Models\Release::with(['author', 'readByUsers' => function($q) use ($user) {
                if ($user) $q->where('user_id', $user->id);
            }])
                ->where('type', 'actualizacion')
                ->published()
                ->where('published_at', '<=', now())
                ->latest('published_at')
                ->take(5)
                ->get();

            // Transformar para añadir un booleano is_read
            $latestComunicados->each(function($rel) use ($user) {
                $rel->is_read = $user ? $rel->readByUsers->contains($user->id) : false;
            });
            $latestActualizaciones->each(function($act) use ($user) {
                $act->is_read = $user ? $act->readByUsers->contains($user->id) : false;
            });

            // Contar no leídos para el badge rojo
            $unreadCount = 0;
            if ($user) {
                $unreadCount = \App\Models\Release::published()
                    ->where('published_at', '<=', now())
                    ->whereDoesntHave('readByUsers', function ($query) use ($user) {
                        $query->where('user_id', $user->id);
                    })
                    ->count();
            }

            $view->with('latestComunicados', $latestComunicados);
            $view->with('latestActualizaciones', $latestActualizaciones);
            $view->with('unreadCount', $unreadCount);
        });
    }
}
