<?php

namespace s4urp8n\DatabaseDump\Command\Dump\Driver;

abstract class Base
{
    public abstract function dump(string &$connection, string &$database, string &$table, string &$savepath);
}