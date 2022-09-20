<?php

namespace mohamadmurad\LaravelTelegramReporter;


use Illuminate\Support\ServiceProvider;
use mohamadmurad\LaravelTelegramReporter\Console\TelegramReportInstall;

class TelegramReporterServiceProvider extends ServiceProvider
{
    const CONFIG_PATH = __DIR__ . '/../config/telegram-report.php';

    private function publish()
    {

        if (! function_exists('config_path')) {
            // function not available and 'publish' not relevant in Lumen
            return;
        }

        $this->publishes([
            self::CONFIG_PATH => config_path('telegram-report.php')
        ], 'config');


    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            self::CONFIG_PATH,
            'telegram-report'
        );

        $this->app->alias(ExceptionReporter::class, 'telegram-report');


    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publish();

        $this->commands([TelegramReportInstall::class]);
    }
}
