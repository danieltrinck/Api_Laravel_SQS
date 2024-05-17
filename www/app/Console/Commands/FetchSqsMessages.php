<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Aws\Exception\AwsException;
use Aws\Sqs\SqsClient;
use App\Jobs\ProcessSqsMessage;

class FetchSqsMessages extends Command
{
    protected $signature = 'sqs:fetch-messages';
    protected $description = 'Pega as mensagens da Amazon SQS';

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
                'key'     => config('AWS_ACCESS_KEY_ID'),
                'secret'  => config('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);

        try {
       
            $queue_url = config('SQS_PREFIX');
            $result = $client->receiveMessage([
                'QueueUrl'            => $queue_url,
                'MaxNumberOfMessages' => 10,
                'WaitTimeSeconds'     => 0,
            ]);

            if (!empty($result->get('Messages'))) {
                foreach ($result->get('Messages') as $message) {
                    ProcessSqsMessage::dispatch($message);
                    $client->deleteMessage([
                        'QueueUrl'      => $queue_url,
                        'ReceiptHandle' => $message['ReceiptHandle'],
                    ]);
                }
            }else {
                echo "Sem mensagens na fila para processar.\n";
            }

        } catch (AwsException $e) {
            // output error message if fails
            echo $e->getMessage();
            error_log($e->getMessage());
        }
    }
}
