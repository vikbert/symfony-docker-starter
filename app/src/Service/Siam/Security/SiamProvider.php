<?php

declare(strict_types = 1);

namespace App\Service\Siam\Security;

use App\Service\Siam\SiamConstant;
use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class SiamProvider extends AbstractProvider
{
    use BearerAuthorizationTrait;

    protected string $baseAuthorizationUrl;
    protected string $baseAccessTokenUrl;
    protected string $resourceOwnerDetailsUrl;

    public function getBaseAuthorizationUrl(): string
    {
        return $this->baseAuthorizationUrl;
    }

    /**
     * @param array<string, string> $params
     */
    public function getBaseAccessTokenUrl(array $params): string
    {
        return $this->baseAccessTokenUrl;
    }

    public function getResourceOwnerDetailsUrl(AccessToken $token): string
    {
        return $this->resourceOwnerDetailsUrl;
    }

    /**
     * @return array<int, string>
     */
    protected function getDefaultScopes(): array
    {
        return [SiamConstant::SSO_SCOPE];
    }

    /**
     * @param array<string, string> $options
     *
     * @return array<string, string>
     */
    protected function getAuthorizationParameters(array $options): array
    {
        $params = parent::getAuthorizationParameters($options);

        return $this->addMissingParameters($params);
    }

    /**
     * @param array<string, string> $params
     */
    protected function getAccessTokenRequest(array $params): RequestInterface
    {
        $params = $this->addMissingParameters($params);

        return parent::getAccessTokenRequest($params);
    }

    /**
     * @param array<string, string> $params
     *
     * @return array<string, string>
     */
    private function addMissingParameters(array $params): array
    {
        $params = array_merge(
            $params,
            [
                'resourceServer' => SiamConstant::SSO_RESOURCE_SERVER,
                'scope' => implode($this->getScopeSeparator(), $this->getDefaultScopes()),
            ]
        );

        return $params;
    }

    /**
     * @param array<string, int|string> $data
     *
     * @throws IdentityProviderException
     */
    protected function checkResponse(ResponseInterface $response, $data): void
    {
        if ($response->getStatusCode() >= 400) {
            throw new IdentityProviderException($data['error'] ?? $response->getReasonPhrase(), $response->getStatusCode(), $response->getBody()->getContents());
        }
    }

    protected function createResourceOwner(array $response, AccessToken $token): SiamResourceOwner
    {
        return new SiamResourceOwner($response);
    }

    protected function buildQueryString(array $params): string
    {
        return implode(
            '&',
            array_map(
                static function (string $key, string $value): string {
                    return sprintf('%s=%s', $key, $value);
                },
                array_keys($params),
                $params
            )
        );
    }
}
