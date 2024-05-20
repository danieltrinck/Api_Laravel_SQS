<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Tests\TestCase;
use Mockery;
use Aws\Sqs\SqsClient;
use App\Services\SqsService;

class SQSTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_sends_a_message_to_sqs()
    {

        $sqsService = new SqsService();
        $request    = new Request();
        $request['message'] = 'Test message';
        $result = $sqsService->sendSQS($request,true); //true retorno para ambiente de teste

        $this->assertNotNull($result->get('MessageId'));
    }

    /** @test */
    public function it_receives_a_message_from_sqs()
    {

        $sqsService = new SqsService();
        $result     = $sqsService->receiveSQS(true); //true retorno para ambiente de teste

        $this->assertNotNull($result['MessageId']);
        $this->assertNotNull($result['Body']);
    }
}
