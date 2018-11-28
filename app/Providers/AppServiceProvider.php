<?php

namespace App\Providers;

use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Queue::before(function (JobProcessing $event) {
            // $connectionName = $event->connectionName;
            // $job = $event->job;
            // $payload = $job->payload();
        });
        Queue::after(function (JobProcessed $event) {
            // $connectionName = $event->connectionName;
            // $job = $event->job;
            // $payload = $job->payload();
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
