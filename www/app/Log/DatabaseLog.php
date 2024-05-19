<?php

namespace App\Log;

use Monolog\Logger;
use App\Log\DatabaseHandler;

class DatabaseLog
{
    public function __invoke(array $config)
    {
        $logger = new Logger('database');
        return $logger->pushHandler(new DatabaseHandler());
    }
}