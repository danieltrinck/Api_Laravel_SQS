<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{env('APP_NAME')}}</title>

        <!-- Fonts -->
        <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <!-- Styles -->
        <link href="{{ asset('css/style.css') }}" rel="stylesheet">
        
    </head>
    <body>
        <section id="token-section" class="doc-section">
            <h2 class="section-title">Token</h2>
            <div class="section-block">
                <h3 class="block-title">/getToken</h3>
                <p>Método responsável por retornar o <i>Token</i>.</p>
                <p>Deverá ser realizado uma requisição <b>POST</b> informando no corpo da requisição, seu <b>e-mail</b> e <b>senha</b>.</p>
                <li class="label label-orange"><b class="label label-orange-800 no-padding">POST</b> http://localhost:8000/api/getToken</li>
                <br><br>
                <h5>Requisição</h5>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Utilizar para testes</th>
                            <th>Formato</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td><b>e-mail</b></td>
                            <td>api@irroba.com</td>
                            <td><i>String</i></td>
                        </tr>
                        <tr>
                            <td><b>password</b></td>
                            <td>irroba</td>
                            <td><i>String</i></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <h5>Resposta de Sucesso</h5>

                <div class="code">
                {<br>
                &nbsp;&nbsp;<span class="green">"access_token"</span>: <span class="green">"TOKEN GERADO"</span>,
                <br>
                &nbsp;&nbsp;<span class="green">"token_type"</span>: <span class="green">"Bearer"</span>
                <br>}
                </div>

                <h5>Resposta de Falha</h5>
                <div class="code">
                {
                    <br>&nbsp;&nbsp;<span class="green">"message"</span>: <span class="green">"O e-mail é obrigatório. (and 1 more error)"</span>,
                    <br>&nbsp;&nbsp;<span class="green">"errors"</span>: {
                    <br>&nbsp;&nbsp;&nbsp;&nbsp;<span class="green">"email"</span>: [
                    <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="green">"O e-mail é obrigatório."</span>
                    <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;],
                    <br>&nbsp;&nbsp;&nbsp;&nbsp;<span class="green">"password"</span>: [
                    <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="green">"A senha é obrigatória."</span>
                    <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;]
                    <br>&nbsp;&nbsp;}
                <br>}
                </div>

            </div>
        </section>
        
        <section id="token-section" class="doc-section">
            <h2 class="section-title">Enviando Mensagem para AWS SQS</h2>
            <div class="section-block">
                <h3 class="block-title">/sendMessageSQS</h3>
                <p>Método responsável por simular outro sistema gerando um JSON de atualização armazenando os dados na AWS SQS.</p>
                <p>Após a mensagem ser registrada no SQS o serviço será executado pegando as mensagens e colocando em filas para serem processadas posteriormente.<br>
                Para pegar as mensagens manualmente sem configurar um CRON ou aguardar um agendamento executar o comando abaixo:<br>
                <b>php {(caminho em que se encontra o projeto ex.: /var/www/...)}/artisan schedule:run</b>
                <br>Lembrando que o método <b>php artisan queue:work</b> deverá estar em execução para processar a fila.
                <p>Para criar a requisição deverá ser realizado um <b>POST</b> informando no corpo da requisição, o <b>JSON Modelo</b> abaixo.</p>
                <li class="label label-orange"><b class="label label-orange-800 no-padding">POST</b> http://localhost:8000/api/sendMessageSQS</li>
                <br>
                <h5>Modelo de Teste (JSON modelo já disponivel na collection do Postman)</h5>
                <div class="code">
                {
                    <br><span class="green">"Mercadolivre"</span>: {
                    <br>&nbsp;&nbsp;<span class="green">"auth"</span>: {
                    <br>&nbsp;&nbsp;&nbsp;&nbsp;<span class="green">"token_irroba"</span>:<span class="green"> "000000000000000000000000"</span>
                    <br>&nbsp;&nbsp;},
                    <br>&nbsp;<span class="green">"type"</span>:<span class="green"> "stock_db"</span>,
                    <br>&nbsp;<span class="green">"mass"</span>:<span class="green"> false</span>,
                    <br>&nbsp;<span class="green">"store_id"</span>:<span class="green"> "123"</span>,
                    <br>&nbsp;<span class="green">"domain"</span>:<span class="green"> "http://www.exemplo.com.br"</span>,
                    <br>&nbsp;<span class="green">"is_api"</span>:<span class="green"> false</span>,
                    <br>&nbsp;<span class="green">"products"</span>: [
                    <br>&nbsp;&nbsp;{
                    <br>&nbsp;&nbsp;&nbsp;&nbsp;<span class="green">"product_id"</span>:<span class="green"> "3422"</span>,
                    <br>&nbsp;&nbsp;&nbsp;&nbsp;<span class="green">"merca_id"</span>:<span class="green"> "MLBxxxxxxx"</span>,
                    <br>&nbsp;&nbsp;&nbsp;&nbsp;<span class="green">"variations"</span>: [
                    <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{
                    <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="green">"id"</span>:<span class="green"> "180469698170"</span>,
                    <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="green">"available_quantity"</span>:<span class="green"> 999</span>,
                    <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="green">"unic"</span>:<span class="green"> "0"</span>,
                    <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="green">"refresh_token"</span>:<span class="green"> "TG-xxxxxxxxxxxx5655555-70357035"</span>,
                    <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="green">"account_id"</span>:<span class="green"> "7"</span>,
                    <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="green">"sku_id"</span>:<span class="green"> "3422-18838"</span>
                    <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;},
                    <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{
                    <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="green">"id"</span>:<span class="green"> "180469698172"</span>,
                    <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="green">"available_quantity"</span>:<span class="green"> "100"</span>,
                    <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="green">"unic"</span>:<span class="green"> "0"</span>,
                    <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="green">"refresh_token"</span>:<span class="green"> "TG-xxxxxxxxxxxx5655555-70357035"</span>,
                    <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="green">"account_id"</span>:<span class="green"> "7"</span>,
                    <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="green">"sku_id"</span>:<span class="green"> "3422-18839"</span>,
                    <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}
                    <br>&nbsp;&nbsp;&nbsp;&nbsp;]}
                    <br>&nbsp;]}<br>
                }
                </div>

                <h5>Resposta de Sucesso</h5>

                <div class="code">
                {
                <br>&nbsp;&nbsp;<span class="green">"success"</span>: <span class="green">true</span>,
                <br>&nbsp;&nbsp;<span class="green">"message"</span>: <span class="green">"Mensagem enviada para fila SQS"</span>
                <br>}
                </div>

                <h5>Resposta de Falha</h5>
                <div class="code">
                    {
                    <br>&nbsp;&nbsp;<span class="green">"error"</span>: <span class="green">true</span>,
                    <br>&nbsp;&nbsp;<span class="green">"message"</span>: <span class="green">"Falha na execução"</span>
                    <br>}
                </div>

            </div>
        </section>
    </body>
</html>
