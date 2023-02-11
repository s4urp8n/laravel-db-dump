<?php

namespace s4urp8n\DatabaseDump;

use Illuminate\Support\ServiceProvider;
use s4urp8n\MysqlDump\Command\Dump\Mysql;

class DatabaseDumpServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/db_dump.php' => config_path('db_dump.php'),
        ]);

        if ($this->app->runningInConsole()) {
            $this->commands([
                Mysql::class
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
