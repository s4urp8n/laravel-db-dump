<?php

namespace s4urp8n\DatabaseDump\Command\Dump\Driver;

use Illuminate\Support\Facades\DB;

class MySql extends Base
{
    public function detectMysqlBinary(&$connection, $file)
    {
        $basedir = (array)DB::connection($connection)->selectOne('show variables where variable_name=\'basedir\'');
        $basedir = array_values($basedir)[1] ?? null;

        if (!$basedir || !is_dir($basedir)) {
            throw new \Exception('Cant detect mysql binary ' . $file);
        }

        $binary = $basedir . DIRECTORY_SEPARATOR . 'bin' . DIRECTORY_SEPARATOR . $file;
        if (DIRECTORY_SEPARATOR == '\\') {
            $binary .= '.exe';
        }

        if (!file_exists($binary)) {
            throw new \Exception('Mysql binary ' . $file . ' is not exists');
        }

        return $binary;
    }

    public function dump(string &$connection, string &$database, string &$table, string &$savepath)
    {
        $mysqldump = $this->detectMysqlBinary($connection, 'mysqldump');

        $username = config('database.connections.' . $connection . '.username');
        $password = config('database.connections.' . $connection . '.password');

        putenv('MYSQL_PWD=' . $password);

        $cmd = sprintf('%s --add-drop-table --add-locks --disable-keys --extended-insert --insert-ignore --quote-names --routines --verbose --triggers --events --force -u%s %s %s > %s',
            escapeshellarg($mysqldump),
            escapeshellarg($username),
            escapeshellarg($database),
            escapeshellarg($table),
            escapeshellarg($savepath)
        );

        passthru($cmd);
    }
}