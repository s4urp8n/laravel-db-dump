<?php

namespace s4urp8n\DatabaseDump\Command\Dump\Driver;
class MySql extends Base
{
    public function detectMysqlBinary($file)
    {
//        $dirErrorMessage = 'Cant detect mysql binary ' . $file;
//
//        $basedir = Sql::select('show variables where variable_name=\'basedir\'');
//        if (!is_array($basedir) || !$basedir) {
//            throw new \Exception($dirErrorMessage);
//        }
//
//        $basedir = $basedir[0] ?? false;
//        if (!is_array($basedir) || !$basedir) {
//            throw new \Exception($dirErrorMessage);
//        }
//
//        $basedir = array_values($basedir);
//        $basedir = $basedir[1] ?? false;
//        if (!$basedir || !is_dir($basedir)) {
//            throw new \Exception($dirErrorMessage);
//        }
//
//        $binary = $basedir . DIRECTORY_SEPARATOR . 'bin' . DIRECTORY_SEPARATOR . $file;
//        if (DIRECTORY_SEPARATOR == '\\') {
//            $binary .= '.exe';
//        }
//
//        if (!file_exists($binary)) {
//            throw new \Exception($dirErrorMessage);
//        }
//
//        return $binary;
    }

    public function dump(string $connection, string $database, string $table, string $savepath)
    {
//        $database = env('DB_DATABASE');
//        $username = env('DB_USERNAME');
//        $password = env('DB_PASSWORD');
//        $mysqldump = $this->detectMysqlBinary('mysqldump');
//        $date = date('Y-m-d H_i_s');
//        $dir = base_path('dumps' . DIRECTORY_SEPARATOR . $date);
//        $tables = Sql::select('show tables', 0);
//        mkdir($dir, 0777, true);
//        putenv('MYSQL_PWD=' . $password);
//
//        foreach ($tables as $table) {
//            if (preg_match('/^memory_/i', $table)) {
//                continue;
//            }
//
//            echo "\n\n", $table, "...\n";
//
//            $output = $dir . DIRECTORY_SEPARATOR . $table . '.sql';
//            $cmd = sprintf('%s --add-drop-table --add-locks --disable-keys --extended-insert --insert-ignore --quote-names --routines --verbose --triggers --events --force -u%s %s %s > %s',
//                escapeshellarg($mysqldump),
//                escapeshellarg($username),
//                escapeshellarg($database),
//                escapeshellarg($table),
//                escapeshellarg($output)
//            );
//
//            passthru($cmd);
    }
}