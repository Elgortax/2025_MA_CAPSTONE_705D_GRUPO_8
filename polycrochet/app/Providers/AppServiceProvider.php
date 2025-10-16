<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Filesystem\FlysystemAdapter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\InMemory\InMemoryFilesystemAdapter;

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
        Carbon::setLocale(config('app.locale', 'es'));

        Storage::extend('supabase', function ($app, $config) {
            $adapter = new InMemoryFilesystemAdapter();

            return new FilesystemAdapter(
                new FilesystemManager($app),
                new FlysystemAdapter($adapter),
                $adapter,
                $config
            );
        });
    }
}
