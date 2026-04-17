<?php

namespace App\Providers;

use App\Models\Event;
use App\Models\Reservation;
use Illuminate\Database\Eloquent\Relations\Relation;
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
        Relation::enforceMorphMap([
            'Reservation' => Reservation::class,
            'Event' => Event::class,
        ]);
    }
}
