### Instalando o Laravel
Clonando o sistema:
```php
git clone https://github.com/danieltrinck/Api_Laravel_SQS.git . 
```

Renomear o arquivo .env.example para .env
Criar um banco de dados chamado irroba. Alterar os usuários de acesso DB_USERNAME, DB_PASSWORD concedendo acesso ao sistema.

Entrar na pasta raiz do projeto ./www e rodar os comandos abaixo:
```php
composer install
php artisan migrate
php artisan db:seed
```

### Configurar a Amazon SQS
Para enviar e receber as mensagens, configurar as variáveis de ambiente dentro do .env
```php
AWS_ACCESS_KEY_ID=your-access-key-id
AWS_SECRET_ACCESS_KEY=your-secret-access-key
AWS_DEFAULT_REGION=your-region
AWS_SQS_QUEUE=your-queue-url
```

### Rodando o sistema criando as filas e agendando os recebimentos
Agendando e iniciando o Scheduler do Laravel. Cadastrar no cron do sistema a linha abaixo:
```php
* * * * * php /path-to-your-project/artisan schedule:run >> /dev/null 2>&1
```
Se não quiser agendar, para testar, basta executar o comando todas as vezes que quiser baixar as mensagens;
```php
php /path-to-your-project/artisan schedule:run
```

### Executar a fila do laravel
Deixar o comando abaixo rodando em background para processar as filas. Nesse momento poderia ser feito um post para qualquer api atualizando os dados. Como é um teste será registrado em um banco de dados na tabela mercadolivre.
Todo o sistema conta com log de erros em tempo de execução. Os erros das filas serão registrados na tabela failed_jobs e os demais na tabela logs.
```php
php artisan queue:work
```
### Testes automatizados
Os testes serão realizados cadastrando um usuário e gerando um token de acesso que será utilizado para autenticar as requisições via API para envio de mensagens.
Também será testado o envio e recebimento de mensagens pela AWS SQS
Para realizar os testes rodar o comando abaixo:
```php
php artisan test
```

### Documentação da API
Para testar o sistema, rodar o comando abaixo. Se tudo estiver correto será possível acessar a página da API com os dados de envio para gerar as mensagens e processar as filas.
```php
php artisan serve
```
Acessando a página com instruções sobre a autenticação e envio dos dados para AWS SQS.
http://localhost:8000

Para o funcionamento das filas, o envio de mensagens e o acesso pela API certifique-se de que os comandos abaixo estão sendo executados ao mesmo tempo e em terminais diferentes.
```php
php artisan serve
php artisan queue:work
php /path-to-your-project/artisan schedule:run
```

Com esses passos, terá um job agendado no Laravel que busca mensagens na Amazon SQS periodicamente e as processa conforme necessário. Nessa caso salvando no banco ou atualizando um registro existente.