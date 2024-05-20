<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Aws\Exception\AwsException;
use Aws\Sqs\SqsClient;
use App\Jobs\ProcessSqsMessage;
use App\Services\SqsService;


class FetchSqsMessages extends Command
{
    protected $signature   = 'sqs:fetch-messages';
    protected $description = 'Pega as mensagens na Amazon SQS';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
       
            $sqs = new SqsService();
            $sqs->receiveSQS();

        } catch (AwsException $e) {
            // grava a mensagem de erro se houver falhas no processo
            error_log($e->getMessage());
            Log::channel('database')->error('FetchSqsMessages', ['exception' => $e]);
        }
    }
}
