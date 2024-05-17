<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Jobs\SendSqsJob;
use App\Jobs\ReceiveSqsJob;
use Aws\Exception\AwsException;
use Aws\Sqs\SqsClient;

Route::post('/sendMessageSQS', function(){
    
    $client = new SqsClient([
        'version'     => 'latest',
        'region'      => 'sa-east-1',
        'credentials' => [
            'key'     => env('AWS_ACCESS_KEY_ID'),
            'secret'  => env('AWS_SECRET_ACCESS_KEY'),
        ],
    ]);

    try {

        $fields = [
            "Mercadolivre" => [
                "auth" => [
                    "token_irroba" => "xxxxxxxxxxxxxxx"
                ],
                "type" => "stock_db",
                "mass" => false,
                "store_id" => "3243",
                "domain" => "http://www.exemplo.com.br",
                "is_api" => false,
                "products" => [
                    [
                        "product_id" => "3422",
                        "merca_id" =>  "MLBxxxxxxx",
                        "variations" => [
                            [
                                "id" => "180469698170",
                                "available_quantity" => 999,
                                "unic" => "0",
                                "refresh_token" => "TG-xxxxxxxxxxxx5655555-70357035",
                                "account_id" => "7",
                                "sku_id" => "3422-18838"
                            ],
                            [
                                "id" => "180469698172",
                                "available_quantity" => "100",
                                "unic" => "0",
                                "refresh_token" => "TG-xxxxxxxxxxxx5655555-70357035",
                                "account_id" => "7",
                                "sku_id" => "3422-18839"
                            ]
                        ]
                    ]
                ]
            ]
        ];
        $params = [
            'MessageBody' => json_encode($fields),
            'QueueUrl'    => env('SQS_PREFIX')
        ];
   
        $result = $client->sendMessage($params);

        var_dump($result);
        echo "Mensagem enviada para fila.\n";

    } catch (AwsException $e) {
        // output error message if fails
        echo $e->getMessage();
        error_log($e->getMessage());
    }

});