<?php

declare(strict_types=1);

namespace Vlsv\SberApiRegistryOauthClient;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
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
     * @throws Exception|GuzzleException
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
        } catch (RequestException $exception) {
            $this->exceptionGuard($exception);
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
     * @throws Exception|GuzzleException
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
        } catch (RequestException $exception) {
            $this->exceptionGuard($exception);
        }

        if ($response->getStatusCode() === 200) {
            return $this->serializer->deserialize(
                data: $response->getBody()->getContents(),
                type: OidcTokenResponse::class,
                format: 'json'
            );
        }

        throw new ApiException(
            message: '[' . $response->getStatusCode() . '] ' . 'Unknown response',
            code: $response->getStatusCode(),
        );
    }

    /**
     * @throws ApiException
     */
    private function exceptionGuard(RequestException $exception): void
    {
        $responseCode = $exception->getCode();
        $apiResponseCodes = [
            400 => BadRequest::class,
            401 => Unauthorized::class,
            405 => MethodNotAllowed::class,
            500 => InternalServerError::class,
        ];

        if (key_exists($responseCode, $apiResponseCodes)) {
            $responseObject = $this->serializer->deserialize(
                data: (string)$exception->getResponse()->getBody(),
                type: $apiResponseCodes[$responseCode],
                format: 'json'
            );

            throw (new ApiException(
                message: '[' . $exception->getCode() . '] ' . $exception->getResponse()->getBody(),
                code: $exception->getCode(),
                previous: $exception,
            ))->setResponseObject($responseObject);
        }

        throw new ApiException(
            message: '[' . $exception->getCode() . '] ' . $exception->getMessage(),
            code: $exception->getCode(),
            previous: $exception,
        );
    }
}
