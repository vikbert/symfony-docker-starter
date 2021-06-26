<?php

declare(strict_types = 1);

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

final class LoginFormUserProvider implements UserProviderInterface
{
    public function loadUserByUsername(string $username): UserInterface
    {
        $user = new User();
        $user->setUsername($username);

        return $user;
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        return $user;
    }

    public function supportsClass(string $class): bool
    {
        return $class === User::class;
    }
}
