<?php

namespace App\Providers;

use App\Models\Advertisement;
use App\Models\Survey;
use App\Models\User;
use App\Observers\AdvertisementObserver;
use App\Observers\ParrainageObserver;
use App\Observers\SurveyObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        Advertisement::observe(AdvertisementObserver::class);
        Survey::observe(SurveyObserver::class);
//        User::observe(ParrainageObserver::class);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
