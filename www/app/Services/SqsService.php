<?php
namespace App\Services;

use Aws\Sqs\SqsClient;
use Aws\Exception\AwsException;
use App\Jobs\ProcessSqsMessage;

class SqsService
{
    protected $sqs;

    public function __construct()
    {
        $this->sqs = new SqsClient([
            'version'     => 'latest',
            'region'      => 'sa-east-1',
            'credentials' => [
                'key'     => env('AWS_ACCESS_KEY_ID'),
                'secret'  => env('AWS_SECRET_ACCESS_KEY'),
            ]
        ]);
    }

    public function sendSQS($request, $test=false)
    {
        
        try {

            $params = [
                "QueueUrl"    => env('SQS_PREFIX'),
                "MessageBody" => json_encode($request->all())
            ];
       
            $result = $this->sqs->sendMessage($params);

            if($test)
            {   //Retorno para ambiente de test

                return $result;

            }else{

                return response()->json([
                    'success' => true,
                    'message' => 'Mensagem enviada para fila SQS'
                ]);
            }
    
        } catch (AwsException $e) {
            // grava a mensagem de erro se houver falhas no processo
            error_log($e->getMessage());
            Log::channel('database')->error('SendMessageSQS.sendSQS', ['exception' => $e]);
            return response()->json([
                'error'   => true,
                'message' => 'Falha na execução'
            ]);
        }

    }

    public function receiveSQS($test=false)
    {
        try {
       
            $queue_url = env('SQS_PREFIX');
            $result = $this->sqs->receiveMessage([
                'QueueUrl'            => $queue_url,
                'MaxNumberOfMessages' => 10,
                'WaitTimeSeconds'     => 0,
            ]);

            if($test)
            {   //Retorno para ambiente de test

                if (!empty($result->get('Messages'))) {
                    return $result->get('Messages')[0];
                }
    
                return null;

            }else{

                if (!empty($result->get('Messages'))) 
                {
                    foreach ($result->get('Messages') as $message) 
                    {
                        ProcessSqsMessage::dispatch($message);
                        $this->sqs->deleteMessage([
                            'QueueUrl'      => $queue_url,
                            'ReceiptHandle' => $message['ReceiptHandle'],
                        ]);
                    }
                }
            }

        } catch (AwsException $e) {
            // grava a mensagem de erro se houver falhas no processo
            error_log($e->getMessage());
            Log::channel('database')->error('FetchSqsMessages', ['exception' => $e]);
        }
    }
}