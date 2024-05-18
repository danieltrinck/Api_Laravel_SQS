<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Aws\Exception\AwsException;
use Aws\Sqs\SqsClient;
use App\Jobs\ProcessSqsMessage;

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
        $client = new SqsClient([
            'version'     => 'latest',
            'region'      => 'sa-east-1',
            'credentials' => [
                'key'     => env('AWS_ACCESS_KEY_ID'),
                'secret'  => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);

        try {
       
            $queue_url = env('SQS_PREFIX');
            $result = $client->receiveMessage([
                'QueueUrl'            => $queue_url,
                'MaxNumberOfMessages' => 10,
                'WaitTimeSeconds'     => 0,
            ]);

            if (!empty($result->get('Messages'))) 
            {
                foreach ($result->get('Messages') as $message) 
                {
                    ProcessSqsMessage::dispatch($message);
                    $client->deleteMessage([
                        'QueueUrl'      => $queue_url,
                        'ReceiptHandle' => $message['ReceiptHandle'],
                    ]);
                }
            }

        } catch (AwsException $e) {
            // grava a mensagem de erro se houver falhas no processo
            error_log($e->getMessage());
            Log::channel('database')->error('FetchSqsMessages', ['exception' => $e]);
        }
    }
}
