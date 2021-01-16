<?php

declare(strict_types = 1);

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Security\Event\AuthenticationSucceeded;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Client\OAuth2ClientInterface;
use KnpU\OAuth2ClientBundle\Security\Authenticator\SocialAuthenticator;
use League\OAuth2\Client\Token\AccessToken;
use Ramsey\Uuid\Uuid;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;

final class SsoAuthenticator extends SocialAuthenticator
{
    private ClientRegistry $clientRegistry;
    private UserRepository $userRepository;
    private EventDispatcherInterface $dispatcher;

    public function __construct(ClientRegistry $clientRegistry, UserRepository $userRepository, EventDispatcherInterface $dispatcher)
    {
        $this->clientRegistry = $clientRegistry;
        $this->userRepository = $userRepository;
        $this->dispatcher = $dispatcher;
    }

    public function start(Request $request, AuthenticationException $authException = null): JsonResponse
    {
        return new JsonResponse(
            [
                'message' => 'Authentication Required',
            ],
            Response::HTTP_UNAUTHORIZED
        );
    }

    public function supports(Request $request): bool
    {
        return 'sso_login' === $request->attributes->get('_route');
    }

    public function getCredentials(Request $request): AccessToken
    {
        return $this->fetchAccessToken($this->getSsoClient());
    }

    public function getUser($credentials, UserProviderInterface $userProvider): User
    {
        /** @var SsoResourceOwner $resourceOwner */
        $resourceOwner = $this->getSsoClient()->getOAuth2Provider()->getResourceOwner($credentials);
        $ownerData = $resourceOwner->toArray();

        $user = new User($ownerData['mail']);
        $user->setRoles($this->mapOauth2Roles($resourceOwner->getRoles()));

        return $user;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): JsonResponse
    {
        return new JsonResponse(null, Response::HTTP_UNAUTHORIZED);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey): JsonResponse
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            throw new AuthenticationException('User not found by token');
        }

        $user->login();
        $this->userRepository->save($user);

        $this->dispatcher->dispatch(
            AuthenticationSucceeded::fromHttpRequest(
                Uuid::uuid4()->toString(),
                $token,
                $request
            )
        );

        return new JsonResponse(['authToken' => $user->getToken()]);
    }

    public function supportsRememberMe(): bool
    {
        return false;
    }

    private function getSsoClient(): OAuth2ClientInterface
    {
        $ssoClient = $this->clientRegistry->getClient('sso_client');
        $ssoClient->setAsStateless();

        return $ssoClient;
    }

    /**
     * @param string[] $roles
     *
     * @return string[]
     */
    private function mapOauth2Roles(array $roles): array
    {
        // here comes the roles mapping logic
        return $roles;
    }
}
