<?php

namespace s4urp8n\DatabaseDump;

use Illuminate\Support\ServiceProvider;
use s4urp8n\DatabaseDump\Command\Dump;

class DatabaseDumpServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/db_dump.php' => config_path('db_dump.php'),
        ]);

        if ($this->app->runningInConsole()) {
            $this->commands([
                Dump::class
            ]);
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/db_dump.php', 'db_dump'
        );
    }

}
