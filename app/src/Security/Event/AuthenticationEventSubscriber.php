<?php

declare(strict_types = 1);

namespace App\Security\Event;

use JetBrains\PhpStorm\ArrayShape;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class AuthenticationEventSubscriber implements EventSubscriberInterface
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    #[ArrayShape([AuthenticationSucceeded::class => "string"])] 
    public static function getSubscribedEvents(): array
    {
        return [
            AuthenticationSucceeded::class => 'authenticationSucceeded',
        ];
    }

    public function authenticationSucceeded(AuthenticationSucceeded $event): void
    {
        $this->logger->info('Authentication succeeded', $event->getData());
    }
}
