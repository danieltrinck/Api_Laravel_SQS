<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use DB;
use App\Models\MercadoLivre;

class ProcessSqsMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $message;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($message)
    {
        $this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Processar a mensagem da fila
        // Nesse ponto seria feito a atualização via api para os endpoints no array domains atualizando os dados via post
        // Como é um teste estou apenas salvando no banco de dados ou atualizando caso já exista.

        try {
          
            $message = $this->message['Body'];
            $message = json_decode($message);
            if(isset($message->Mercadolivre))
            {
                DB::beginTransaction();
                $ml      = MercadoLivre::where('store_id', $message->Mercadolivre->store_id)->first();

                if($ml){

                    $ml->dados = $this->message['Body'];
                    $ml->save();

                }else{

                    MercadoLivre::create([
                        'store_id' => $message->Mercadolivre->store_id,
                        'dados'    => $this->message['Body']
                    ]);
                }
                DB::commit();
            }

        } catch (\Throwable $th) {
            DB::rollback();
            \Log::channel('database')->error('ProcessSqsMessage', ['exception' => $th]);
        }

    }
}
