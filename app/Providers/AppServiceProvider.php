<?php

namespace App\Providers;

use App\Services\BaseProfiler;
use App\Services\ProfilerContract;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ProfilerContract::class, BaseProfiler::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $profiler = app(ProfilerContract::class);

        \DB::listen(function (QueryExecuted $query) use ($profiler) {
            $profiler->saveQuery($query);
        });
    }
}
