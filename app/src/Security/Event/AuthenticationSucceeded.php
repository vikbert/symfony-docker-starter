<?php

declare(strict_types = 1);

namespace App\Security\Event;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Contracts\EventDispatcher\Event;

final class AuthenticationSucceeded extends Event
{
    private $id;
    private $data;

    public function __construct(string $id, TokenInterface $token, string $uri, string $method, string $body, ?string $clientIp)
    {
        $this->id = $id;
        $this->data = [
            'username' => $token->getUsername(),
            'clientIp' => $clientIp,
            'ressource' => 'Login',
            'action' => 'Authentication',
            'status' => 'success',
            'request' => [
                'url' => $uri,
                'method' => $method,
                'body' => $body,
            ],
        ];
    }

    public static function fromHttpRequest(string $id, TokenInterface $token, Request $request): self
    {
        return new self(
            $id,
            $token,
            $request->getUri(),
            $request->getMethod(),
            (string) $request->getContent(),
            $request->getClientIp()
        );
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getId(): string
    {
        return $this->id;
    }
}
