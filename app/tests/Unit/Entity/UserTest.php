<?php

declare(strict_types = 1);

namespace App\Tests\Unit\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testGetUsername()
    {
        $testUser = new User();
        $testUser->setUsername('my_name');
        
        self::assertEquals('my_name', $testUser->getUsername());
    }
}
