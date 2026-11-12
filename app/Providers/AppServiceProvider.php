<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use App\Models\Survey;

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
        Paginator::defaultView('vendor.pagination.custom');
        Paginator::defaultSimpleView('vendor.pagination.custom');

        // Share currently active surveys with sidebar in app layout
        View::composer('layouts.app', function ($view) {
            $activeSurveys = Survey::unarchived()
                ->where('is_active', true)
                ->orderBy('id', 'asc')
                ->get()
                ->filter(function ($survey) {
                    return $survey->isWithinActiveDate();
                });

            $view->with('sidebarActiveSurveys', $activeSurveys);
        });
    }
}
