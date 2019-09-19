<?php

namespace App\Providers;

use App\Events\ProjectAltered;
use App\Events\ResetProjects;
use App\Listeners\AddProjectsToHosts;
use App\Listeners\PopulateAllVhosts;
use App\Listeners\UpdateVhosts;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        ProjectAltered::class => [
            UpdateVhosts::class,
            AddProjectsToHosts::class,
        ],
        ResetProjects::class => [
            PopulateAllVhosts::class,
            AddProjectsToHosts::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
