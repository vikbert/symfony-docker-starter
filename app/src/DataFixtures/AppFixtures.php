<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public const TEST_EMAIL = 'xun.zhou@mail.com';
    public const TEST_TOKEN = 'b6716e3a-24e8-4e11-b1c7-26d961b356c6';

    public function load(ObjectManager $manager)
    {
        $user = User::create(self::TEST_EMAIL);
        $user->setToken(self::TEST_TOKEN);

        $manager->persist($user);
        $manager->flush();
    }
}
