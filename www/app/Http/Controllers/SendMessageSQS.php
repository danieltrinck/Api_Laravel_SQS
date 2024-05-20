<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RequestSendMessageSQS;
use Aws\Exception\AwsException;
use Aws\Sqs\SqsClient;
use App\Services\SqsService;

class SendMessageSQS extends Controller
{
    public function sendSQS(RequestSendMessageSQS $request)
    {
        
        try {
    
            $sqs = new SqsService();
            $sqs->sendSQS($request);
            return response()->json([
                "success" => true,
                "message" => "Mensagem enviada para fila SQS"
            ]);
    
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
}
