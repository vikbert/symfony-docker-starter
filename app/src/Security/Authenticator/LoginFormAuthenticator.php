<?php

namespace App\Security\Authenticator;

use App\Entity\User;
use App\Repository\UserRepository;
use Exception;
use JetBrains\PhpStorm\ArrayShape;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginFormAuthenticator extends AbstractFormLoginAuthenticator
{
    use TargetPathTrait;

    private $logger;
    private $urlGenerator;
    private $csrfTokenManager;
    private $passwordEncoder;
    private UserRepository $userRepository;

    public function __construct(
        LoggerInterface $logger,
        UrlGeneratorInterface $urlGenerator,
        CsrfTokenManagerInterface $csrfTokenManager,
        UserRepository $userRepository,
    ) {
        $this->logger = $logger;
        $this->urlGenerator = $urlGenerator;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->userRepository = $userRepository;
    }

    public function supports(Request $request): bool
    {
        $this->logger->debug('🔥 check if request app_login supported');

        return 'app_login' === $request->attributes->get('_route')
            && $request->isMethod('POST');
    }

    #[ArrayShape(['email' => "mixed", 'password' => "mixed", 'csrf_token' => "mixed"])]
    public function getCredentials(Request $request
    ): array {
        $credentials = [
            'email' => $request->request->get('email'),
            'password' => $request->request->get('password'),
            'csrf_token' => $request->request->get('_csrf_token'),
        ];

        $this->logger->debug(sprintf('🔥read credentials from request: %s', json_encode($credentials)));

        $request->getSession()->set(
            Security::LAST_USERNAME,
            $credentials['email']
        );

        return $credentials;
    }

    public function getUser($credentials, UserProviderInterface $loginFormUserProvider): UserInterface
    {
        $token = new CsrfToken('authenticate', $credentials['csrf_token']);
        if (!$this->csrfTokenManager->isTokenValid($token)) {
            throw new InvalidCsrfTokenException();
        }

        $loginFormUser = $loginFormUserProvider->loadUserByUsername($credentials['email']);

        $grantedUser = new User();
        $grantedUser->setEmail($loginFormUser->getUsername());
        $grantedUser->setPassword($loginFormUser->getPassword());

        $this->logger->debug(sprintf('🔥 get the granted user from memory: %s', $grantedUser->getEmail()));

        return $grantedUser;
    }

    public function checkCredentials($credentials, UserInterface $user): bool
    {
        if ($user->getPassword() === $credentials['password']) {
            return true;
        }

        throw new Exception(sprintf('Invalid password: "%s"', $credentials['password']));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey): Response
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $providerKey)) {
            return new RedirectResponse($targetPath);
        }

        $user = $token->getUser();

        if (!$user instanceof User) {
            throw new AuthenticationException('User not found by token');
        }

        $user->login();
        $this->userRepository->save($user);

        $this->logger->debug(sprintf('✅ login form success: %s', $user->getToken()));

        $response = new RedirectResponse($this->urlGenerator->generate('app_profile'), 302);
        $response->headers->setCookie(new Cookie('authToken', $user->getToken()));

        return $response;
    }

    protected function getLoginUrl(): string
    {
        return $this->urlGenerator->generate('app_login');
    }
}
