<?php

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class TokenAuthenticator extends AbstractGuardAuthenticator
{
    private const COOKIE_AUTH_TOKEN = 'authToken';
    private const HEADER_AUTH_TOKEN = 'X-AUTH-TOKEN';

    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function start(Request $request, AuthenticationException $authException = null): JsonResponse
    {
        return new JsonResponse(
            [
                'message' => 'Authentication Required',
            ], Response::HTTP_UNAUTHORIZED
        );
    }

    public function supports(Request $request): bool
    {
        // sso_login not supported by TokenAuthenticator, but by SsoAuthenticator
        if ('sso_login' === $request->attributes->get('_route')) {
            return false;
        }

        if ($request->headers->has(self::HEADER_AUTH_TOKEN)) {
            return true;
        }

        if ($request->cookies->has(self::COOKIE_AUTH_TOKEN)) {
            return true;
        }

        return false;
    }

    /**
     * @return string[]
     */
    public function getCredentials(Request $request): array
    {
        $token = null;
        if ($request->headers->has(self::HEADER_AUTH_TOKEN)) {
            $token = $request->headers->get(self::HEADER_AUTH_TOKEN);
        } elseif ($request->cookies->has(self::COOKIE_AUTH_TOKEN)) {
            $token = $request->cookies->get(self::COOKIE_AUTH_TOKEN);
        }

        return [
            'token' => $token,
        ];
    }

    public function getUser($credentials, UserProviderInterface $userProvider): UserInterface
    {
        $apiToken = $credentials['token'];

        if (null === $apiToken) {
            throw new AuthenticationException('Token empty!');
        }

        $user = $this->userRepository->findOneBy(['token' => $apiToken]);
        if (null === $user) {
            throw new AuthenticationException('User not found by token!');
        }

        return $user;
    }

    public function checkCredentials($credentials, UserInterface $user): bool
    {
        return true;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        $user = $token->getUser();
        if (is_string($user)) {
            $user = $this->userRepository->findOneBy(['email' => $user]);
        }
        
        if (!$user instanceof User) {
            throw new AuthenticationException('User not found!');
        }
        
        // on success, let the request continue
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
    {
        if (!in_array('application/json', $request->getAcceptableContentTypes(), true)) {
            $redirectResponse = new RedirectResponse('/');
            $redirectResponse->headers->clearCookie(self::COOKIE_AUTH_TOKEN);

            return $redirectResponse;
        }

        $jsonResponse = new JsonResponse(null, Response::HTTP_UNAUTHORIZED);
        $jsonResponse->headers->clearCookie(self::COOKIE_AUTH_TOKEN);

        return $jsonResponse;
    }

    public function supportsRememberMe(): bool
    {
        return false;
    }
}
