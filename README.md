![phpunit tests](https://github.com/skodnik/sber-api-registry-oauth-client/actions/workflows/php.yml/badge.svg)

# Клиент для работы с токенами Sber API Registry
Получение `access_token` для последующей работы с доступными API.

## Базовые сведения
- [Sber API Registry — доступ к цифровым сервисам экосистемы Сбера](https://api.developer.sber.ru/).
- [О системе Sber API Registry](https://api.developer.sber.ru/how-to-use/about).

Описание доработок, которые необходимо выполнить на стороне сервиса вызова 
API - [Настройки сервиса вызова API](https://api.developer.sber.ru/how-to-use/api_settings). 
Вызов API осуществляется согласно спецификации Oauth 2.0, который предполагает первичное получение токена, разрешающего
выполнить непосредственный запрос API (access_token).

ВАЖНО! **Новый токен нужно получать для каждого нового запроса к API**. Срок
жизни токена составляет 60 сек.

Токены реализуются двух видов:
1. [OAUTH-токен](https://api.developer.sber.ru/how-to-use/token_oauth) - если не требуется доступ к клиентским данным и не требуется согласие клиента на получение его данных.
2. [OIDC-токен](https://api.developer.sber.ru/how-to-use/token_oidc) - если продукт API предполагает работу с данными клиента, в рамках установленных согласий клиентов.

## Требования к переменным окружения
Для получения токена потребуются:
1. `ClientId` и `ClientSecret` для их получения [зарегистрировать приложение](https://api.developer.sber.ru/how-to-use/create_app).
2. В целях двустороннего TLS соединения [выпустить сертификат](https://api.developer.sber.ru/how-to-use/create_certificate).

## Установка библиотеки
```bash
composer require vlsv/sber-api-registry-oauth-client
```

## Получение `access_token`
Приведенные параметры методов запроса токена - минимально необходимые, фактически их больше (см. исходники).
```php
require_once(__DIR__ . '/vendor/autoload.php');

$config = new \Vlsv\SberApiRegistryOauthClient\ClientConfig(
    clientId: 'client_id',
    clientSecret: 'client_secret',
    certPath: 'cert_path',
    certPassword: 'cert_password',
);

$oAuthClient = new \Vlsv\SberApiRegistryOauthClient\OAuthClient($config);

// OAUTH-токен
try {
    $accessToken = $oAuthClient
        ->getOauthToken(scope: 'order.create')
        ->getAccessToken();
} catch (\Vlsv\SberApiRegistryOauthClient\Exception\ApiException $exception) {
    echo $exception->getMessage();
    
    if ($exception->getResponseObject()) {
        echo $exception->getResponseObject()->getMoreInformation();
    }
} catch (\Throwable $exception) {
    echo $exception->getMessage();
}

// OIDC-токен
try {
    $accessToken = $oAuthClient->getOidcToken(
       scope: 'order.create',
       code: 'authorization_code',
       redirectUri: 'redirect_uri', 
   )->getAccessToken();
} catch (\Vlsv\SberApiRegistryOauthClient\Exception\ApiException $exception) {
    echo $exception->getMessage();
    
    if ($exception->getResponseObject()) {
        echo $exception->getResponseObject()->getMoreInformation();
    }
} catch (\Throwable $exception) {
    echo $exception->getMessage();
}
```

## Тесты
Все группы.
```bash
composer tests
```

Feature и unit.
```bash
composer tests-feature && composer tests-unit 
```