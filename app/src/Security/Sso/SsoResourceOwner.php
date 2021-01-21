<?php

declare(strict_types = 1);

namespace App\Security\Sso;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;

final class SsoResourceOwner implements ResourceOwnerInterface
{
    private $responseData;

    public function __construct(array $responseData = [])
    {
        $this->responseData = $responseData;
    }

    public function getId(): ?string
    {
        return $this->responseData['workforceId'] ?? null;
    }

    public function toArray(): array
    {
        return $this->responseData;
    }

    /**
     * @return string[]
     */
    public function getRoles(): array
    {
        return $this->responseData['groups'] ?? [];
    }
}
