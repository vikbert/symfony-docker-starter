<?php

declare(strict_types = 1);

namespace App\EventListener;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Security\Authenticator\TokenAuthenticator;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Http\Event\LogoutEvent;

final class LogoutListener
{
    private LoggerInterface $logger;
    private UserRepository $userRepository;

    public function __construct(LoggerInterface $logger, UserRepository $userRepository)
    {
        $this->logger = $logger;
        $this->userRepository = $userRepository;
    }

    public function onLogoutEvent(LogoutEvent $event)
    {
        $user = $event->getToken()->getUser();
        if (!$user instanceof User) {
            throw new Exception('User is not logged in.');
        }

        $user->logout();
        $this->userRepository->save($user);

        $response = $event->getResponse();
        $response->headers->clearCookie(TokenAuthenticator::COOKIE_AUTH_TOKEN);
    }
}
