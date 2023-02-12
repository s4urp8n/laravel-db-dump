<?php

namespace s4urp8n\DatabaseDump\Command;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use s4urp8n\DatabaseDump\Command\Dump\Driver\Base;
use s4urp8n\DatabaseDump\Command\Dump\Driver\MySql;

class Dump extends Command
{
    protected $signature = 'db:dump';

    protected $description = 'Create dump of database';

    private $whitelist;
    private $blacklist;

    private $drivers = [
        'mysql' => MySql::class
    ];

    private $started;

    public function __construct()
    {
        parent::__construct();

        $this->whitelist = $this->getConfigOption('white-list', []);
        $this->blacklist = $this->getConfigOption('black-list', []);
    }

    private function getConfigOption($key, $default = null)
    {
        return config('db_dump.' . $key, $default);
    }

    public function handle()
    {
        $this->started = date('Y_m_d_H_i_s');

        $connections = $this->getConfigOption('connections');
        foreach ($connections as &$connection) {
            $this->processConnection($connection);
        }
        unset($connection);
    }

    private function processConnection(string &$connection)
    {
        $dbs = DB::connection($connection)->select('show databases');
        $dbs = array_map('reset', $dbs);
        foreach ($dbs as &$db) {
            $this->processConnectionDb($connection, $db);
        }
        unset($db);
    }

    private function processConnectionDb(string &$connection, string &$db)
    {
        $tablesAndViews = DB::connection($connection)->select(sprintf("show full tables from `%s`", $db));
        $tablesAndViews = array_map(function ($value) {
            return array_values((array)$value);
        }, $tablesAndViews);

        $tables = $views = [];

        foreach ($tablesAndViews as &$tableOrView) {
            if (stripos($tableOrView[1], 'view') === false) {
                $tables[] = $tableOrView[0];
            } else {
                $views[] = $tableOrView[0];
            }
        }
        unset($tableOrView);

        foreach ($tables as &$table) {
            $this->processConnectionDbTable($connection, $db, $table);
        }
        unset($table);

        foreach ($views as &$view) {
            $this->processConnectionDbTable($connection, $db, $view);
        }
        unset($view);
    }

    private function processConnectionDbTable(string &$connection, string &$db, string &$table)
    {
        if (!$this->isAllowedToDump($connection, $db, $table)) {
            return;
        }

        $this->dumpTable($connection, $db, $table);
    }

    private function dumpTable(string &$connection, string &$db, string &$table)
    {
        $fullPath = $this->getFullSavepath($connection, $db, $table);

        echo sprintf("Dumping %s.%s.%s => %s\n", $connection, $db, $table, $fullPath);

        /** @var Base $driver */
        $driver = $this->getConnectionDumpDriver($connection);
        $driver->dump($connection, $db, $table, $fullPath);
    }

    private function isAllowedToDump(string &$connection, string &$db, string &$table)
    {
        return $this->isWhitelistedTable($connection, $db, $table)
            && !$this->isBlacklistedTable($connection, $db, $table);
    }

    private function isWhitelistedTable(string &$connection, string &$db, string &$table)
    {
        if (!$this->whitelist) {
            return true;
        }

        return $this->isTableInList($connection, $db, $table, $this->whitelist);
    }

    private function isBlacklistedTable(string &$connection, string &$db, string &$table)
    {
        if (!$this->blacklist) {
            return false;
        }

        return $this->isTableInList($connection, $db, $table, $this->blacklist);
    }

    private function isTableInList(string &$connection, string &$db, string &$table, array $list)
    {
        $fullname = implode('.', [$connection, $db, $table]);
        foreach ($list as $item) {
            if ($this->isTableMatchListItem($fullname, $item)) {
                return true;
            }
        }
        return false;
    }

    private function isTableMatchListItem(string &$tableFullName, string &$listItem)
    {
        if ($tableFullName == $listItem) {
            return true;
        }

        if (strpos($listItem, '#') !== false && preg_match($listItem, $tableFullName)) {
            return true;
        }

        return false;
    }

    private function getConnectionDumpDriver(&$connection)
    {
        $connectionDriver = config('database.connections.' . $connection . '.driver');

        if (!$connectionDriver) {
            throw new \Exception('Driver for connection ' . $connection . ' is not set');
        }

        if (!array_key_exists($connectionDriver, $this->drivers)) {
            throw new \Exception('Driver for connection ' . $connection . ' is not supported');
        }

        $driverClass = $this->drivers[$connectionDriver];

        return new $driverClass;
    }

    private function getFullSavepath(string &$connection, string &$db, string &$table)
    {
        $path = implode(DIRECTORY_SEPARATOR, [
            rtrim($this->getConfigOption('directory'), '/\\'),
            $this->started,
            $connection,
            $db
        ]);

        if (!file_exists($path)) {
            if (!mkdir($path, 0777, true)) {
                throw new \Exception("Can't create dumps save dir " . $path);
            }
        }

        if (!is_writable($path) || !is_dir($path)) {
            throw new \Exception("Dumps save dir " . $path . " is not writable");
        }

        return $path . DIRECTORY_SEPARATOR . $table . '.sql';
    }


}