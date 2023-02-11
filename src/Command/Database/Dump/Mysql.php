<?php

namespace s4urp8n\MysqlDump\Command\Dump;

use Illuminate\Console\Command;

class Mysql extends Command
{
    protected $signature = 'db:dump:mysql';

    protected $description = 'Create dump of MySQL database';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        echo __METHOD__, "\n";
    }
}