<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Aws\Exception\AwsException;
use Aws\Sqs\SqsClient;


class ReceiveSqsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $sqs_prefix, $key_id, $secret_id;

    /**
     * Create a new job instance.
     */
    public function __construct($sqs_prefix,$key_id,$secret_id)
    {
        $this->sqs_prefix = $sqs_prefix;
        $this->key_id     = $key_id;
        $this->secret_id  = $secret_id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $client = new SqsClient([
            'version'     => 'latest',
            'region'      => 'sa-east-1',
            'credentials' => [
                'key'    => $this->key_id,
                'secret' => $this->secret_id,
            ],
        ]);

        try {
       
            $result = $client->receiveMessage([
                'AttributeNames'        => ['SentTimestamp'],
                'MaxNumberOfMessages'   => 1,
                'MessageAttributeNames' => ['All'],
                'QueueUrl'              => $this->sqs_prefix,
                'WaitTimeSeconds'       => 0,
            ]);

            if (!empty($result->get('Messages'))) 
            {
                var_dump($result->get('Messages')[0]);
                $result = $client->deleteMessage([
                    'QueueUrl'      => $this->sqs_prefix,
                    'ReceiptHandle' => $result->get('Messages')[0]['ReceiptHandle']
                ]);

            } else {
                echo "Sem mensagens na fila para processar.\n";
            }

        } catch (AwsException $e) {
            // output error message if fails
            echo $e->getMessage();
            error_log($e->getMessage());
        }
    }
}
