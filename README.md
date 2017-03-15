# google-sheets
API para acessar planilhas do Google Drive


## Download
```shell
php composer.phar require eliasrosa/google-sheets
```


## Como usar
```php

// Exemplo
// https://docs.google.com/spreadsheets/d/1BxiMVs0XRA5nFMdKvBdBZjgmUUqptlbs74OgvE2upms/edit

use ER\GoogleClient;
use ER\GoogleSheet;

$client = new GoogleClient();
$client->setApplicationName('Nome da Aplicação');
$client->setTokenPath(__DIR__ . '/token.json');
$client->setClientSecretPath(__DIR__ . '/client_secret.json');
$client->setToken('TOKEN de Acesso');

//
$client = $client->getClient();
$sheet = new GoogleSheet($client);

$values = $sheet
    ->spreadsheets_values
    ->get('1BxiMVs0XRA5nFMdKvBdBZjgmUUqptlbs74OgvE2upms', 'Class Data!A2:E')
    ->getValues();

if(count($values)) {
    foreach ($values as $row) {
        printf( join($row, '|'). "\n");
    }
}

```
