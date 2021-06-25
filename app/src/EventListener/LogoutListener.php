<?php

declare(strict_types = 1);

namespace App\EventListener;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Security\Authenticator\TokenAuthenticator;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Event\LogoutEvent;

final class LogoutListener
{
    private UserRepository $userRepository;
    private UrlGeneratorInterface $generator;

    public function __construct(UrlGeneratorInterface $generator, UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
        $this->generator = $generator;
    }

    public function onLogoutEvent(LogoutEvent $event): Response
    {
        $user = $event->getToken()->getUser();
        if ($user instanceof User) {
            $user->logout();
            $this->userRepository->save($user);
        }

        $response = $event->getResponse();
        $response->headers->clearCookie(TokenAuthenticator::COOKIE_AUTH_TOKEN);

        return new RedirectResponse($this->generator->generate('app_home'));
    }
}


