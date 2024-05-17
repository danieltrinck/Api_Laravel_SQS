<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Aws\Exception\AwsException;
use Aws\Sqs\SqsClient;


class SendSqsJob implements ShouldQueue
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
                'key'     => $this->key_id,
                'secret'  => $this->secret_id,
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

//            var_dump($result);
            echo "Mensagem enviada para fila.\n";

        } catch (AwsException $e) {
            // output error message if fails
            echo $e->getMessage();
            error_log($e->getMessage());
        }
    }
}
