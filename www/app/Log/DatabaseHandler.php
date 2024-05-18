<?php
namespace App\Log;

use Monolog\Handler\AbstractProcessingHandler;
use Illuminate\Support\Facades\DB;

class DatabaseHandler extends AbstractProcessingHandler
{
    protected function write(array $record): void
    {
        DB::table('logs')->insert([
            'level'      => $record['level_name'],
            'message'    => $record['message'],
            'context'    => json_encode($record['context']),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
