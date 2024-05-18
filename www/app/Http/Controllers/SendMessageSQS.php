<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RequestSendMessageSQS;
use Aws\Exception\AwsException;
use Aws\Sqs\SqsClient;

class SendMessageSQS extends Controller
{
    public function sendSQS(RequestSendMessageSQS $request)
    {
        
        try {
    
            $client = new SqsClient([
                'version'     => 'latest',
                'region'      => 'sa-east-1',
                'credentials' => [
                    'key'     => env('xAWS_ACCESS_KEY_ID'),
                    'secret'  => env('AWS_SECRET_ACCESS_KEY'),
                ],
            ]);

            $params = [
                'MessageBody' => json_encode($request->all()),
                'QueueUrl'    => env('SQS_PREFIX')
            ];
       
            $result = $client->sendMessage($params);
            return "Mensagem enviada para fila SQS.\n";
    
        } catch (AwsException $e) {
            // grava a mensagem de erro se houver falhas no processo
            error_log($e->getMessage());
            Log::channel('database')->error('SendMessageSQS.sendSQS', ['exception' => $e]);
            return response()->json(['error' => 'Falha na execução']);
        }

    }
}
