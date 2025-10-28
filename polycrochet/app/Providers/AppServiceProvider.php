<?php

namespace App\Providers;

use App\Support\SettingsStore;
use Carbon\Carbon;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Filesystem\FlysystemAdapter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
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

        if (Schema::hasTable('settings')) {
            /** @var SettingsStore $settings */
            $settings = app(SettingsStore::class);

            if ($storeName = $settings->get('store.name')) {
                config(['app.name' => $storeName]);
            }

            if ($supportEmail = $settings->get('store.support_email')) {
                config(['services.mail.support' => $supportEmail]);
            }

            if ($paypalClientId = $settings->get('paypal.client_id')) {
                config(['services.paypal.client_id' => $paypalClientId]);
            }

            if ($paypalSecret = $settings->get('paypal.secret')) {
                config(['services.paypal.secret' => $paypalSecret]);
            }

            if ($paypalBaseUri = $settings->get('paypal.base_uri')) {
                config(['services.paypal.base_uri' => $paypalBaseUri]);
            }

            if ($paypalCurrency = $settings->get('paypal.currency')) {
                config(['services.paypal.currency' => strtoupper($paypalCurrency)]);
            }

            if (($conversionRate = $settings->get('paypal.conversion_rate')) && (float) $conversionRate > 0) {
                config(['services.paypal.conversion_rate' => (float) $conversionRate]);
            }
        }

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
