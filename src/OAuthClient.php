<?php

declare(strict_types=1);

namespace Vlsv\SberApiRegistryOauthClient;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Throwable;
use Vlsv\SberApiRegistryOauthClient\Exception\ApiException;
use Vlsv\SberApiRegistryOauthClient\Model\BadRequest;
use Vlsv\SberApiRegistryOauthClient\Model\BasicErrorResponse;
use Vlsv\SberApiRegistryOauthClient\Model\InternalServerError;
use Vlsv\SberApiRegistryOauthClient\Model\MethodNotAllowed;
use Vlsv\SberApiRegistryOauthClient\Model\OAuthTokenResponse;
use Vlsv\SberApiRegistryOauthClient\Model\OidcTokenResponse;
use Vlsv\SberApiRegistryOauthClient\Model\Unauthorized;

class OAuthClient
{
    protected Serializer $serializer;

    public function __construct(
        protected ClientConfig $config,
        protected Client $client = new Client(),
    ) {
        $this->serializer = new Serializer(
            normalizers: [new ObjectNormalizer()],
            encoders: [new JsonEncoder()]
        );
    }

    /**
     * Запрос OAUTH-токена.
     * Его использует большинство API.
     * Используйте OAUTH-токен, если не требуется доступ к клиентским данным и не требуется согласие
     * клиента на получение его данных.
     *
     * @see https://api.developer.sber.ru/how-to-use/token_oauth
     * @throws Exception
     */
    public function getOauthToken(
        string $scope,
        string $rqUID = '',
        string $xIbmClientId = '',
        string $grantType = 'client_credentials',
    ): OAuthTokenResponse|BasicErrorResponse {
        $resourcePath = '/oauth';
        $request = new Request('POST', $this->config->getHost() . $resourcePath);

        $requestOptions = [
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Accept' => 'application/json',
                'Authorization' => 'Basic ' . $this->config->getBasicAuthorizationString(),
                'RqUID' => $rqUID ?: bin2hex(random_bytes(16)),
                'X-IBM-Client-ID' => $xIbmClientId,
            ],
            'form_params' => [
                'grant_type' => $grantType,
                'scope' => $scope,
            ],
        ];

        if ($this->config->getCertPath()) {
            $requestOptions['cert'] = [
                $this->config->getCertPath(),
                $this->config->getCertPassword(),
            ];
        }

        try {
            $response = $this->client->send($request, $requestOptions);
        } catch (Throwable $exception) {
            return $this->exceptionGuard($exception);
        }

        if ($response->getStatusCode() === 200) {
            return $this->serializer->deserialize(
                data: $response->getBody()->getContents(),
                type: OAuthTokenResponse::class,
                format: 'json'
            );
        }

        throw new ApiException(
            '[' . $response->getStatusCode() . '] ' . 'Unknown response',
            $response->getStatusCode(),
        );
    }

    /**
     * Запрос OIDC-токена.
     * Используйте OIDC-токен, если продукт API предполагает работу с данными клиента,
     * в рамках установленных согласий клиентов.
     * На текущий момент используется только для продукта Сбер ID.
     *
     * @see https://api.developer.sber.ru/how-to-use/token_oidc
     * @throws Exception
     */
    public function getOidcToken(
        string $scope,
        string $code,
        string $redirectUri,
        string $rqUID = '',
        string $codeVerifier = '',
        string $xIbmClientId = '',
        string $grantType = 'authorization_code',
    ): OAuthTokenResponse|BasicErrorResponse {
        $resourcePath = '/oidc';
        $request = new Request('POST', $this->config->getHost() . $resourcePath);

        $requestOptions = [
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Accept' => 'application/json',
                'RqUID' => $rqUID ?: bin2hex(random_bytes(16)),
                'X-IBM-Client-ID' => $xIbmClientId,
            ],
            'form_params' => [
                'grant_type' => $grantType,
                'scope' => $scope,
                'client_id' => $this->config->getClientId(),
                'client_secret' => $this->config->getClientSecret(),
                'code' => $code,
                'redirect_uri' => $redirectUri,
                'code_verifier' => $codeVerifier,
            ],
        ];

        if ($this->config->getCertPath()) {
            $requestOptions['cert'] = [
                $this->config->getCertPath(),
                $this->config->getCertPassword(),
            ];
        }

        try {
            $response = $this->client->send($request, $requestOptions);
        } catch (Throwable $exception) {
            return $this->exceptionGuard($exception);
        }

        if ($response->getStatusCode() === 200) {
            return $this->serializer->deserialize(
                data: $response->getBody()->getContents(),
                type: OidcTokenResponse::class,
                format: 'json'
            );
        }

        throw new ApiException(
            '[' . $response->getStatusCode() . '] ' . 'Unknown response',
            $response->getStatusCode(),
        );
    }

    /**
     * @throws ApiException
     */
    private function exceptionGuard(Throwable|Exception $exception): BasicErrorResponse
    {
        if ($exception->getCode() === 400 && $exception->hasResponse()) {
            return $this->serializer->deserialize(
                data: (string)$exception->getResponse()->getBody(),
                type: BadRequest::class,
                format: 'json'
            );
        }

        if ($exception->getCode() === 401 && $exception->hasResponse()) {
            return $this->serializer->deserialize(
                data: (string)$exception->getResponse()->getBody(),
                type: Unauthorized::class,
                format: 'json'
            );
        }

        if ($exception->getCode() === 405 && $exception->hasResponse()) {
            return $this->serializer->deserialize(
                data: (string)$exception->getResponse()->getBody(),
                type: MethodNotAllowed::class,
                format: 'json'
            );
        }

        if ($exception->getCode() === 500 && $exception->hasResponse()) {
            return $this->serializer->deserialize(
                data: (string)$exception->getResponse()->getBody(),
                type: InternalServerError::class,
                format: 'json'
            );
        }

        throw new ApiException(
            '[' . $exception->getCode() . '] ' . $exception->getMessage(),
            $exception->getCode(),
        );
    }
}
