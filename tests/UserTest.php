<?php

declare(strict_types=1);

use App\Models\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testUserName()
    {
        $user = new User(
            'Kristaps',
            'Rigasts',
            1,
            'test@gmail.com',
            '2@ertorpe$'
        );
        $this->assertSame('Kristaps', $user->getName());
    }

    public function testUserSurname()
    {
        $user = new User(
            'Kristaps',
            'Rigasts',
            1,
            'test@gmail.com',
            '2@ertorpe$'
        );
        $this->assertSame('Rigasts', $user->getSurname());
    }

    public function testUserId()
    {
        $user = new User(
            'Kristaps',
            'Rigasts',
            1,
            'test@gmail.com',
            '2@ertorpe$'
        );
        $this->assertSame(1, $user->getId());
    }

    public function testUserEmail()
    {
        $user = new User(
            'Kristaps',
            'Rigasts',
            1,
            'test@gmail.com',
            '2@ertorpe$'
        );
        $this->assertSame('test@gmail.com', $user->getEmail());
    }

    public function testUserPassword()
    {
        $user = new User(
            'Kristaps',
            'Rigasts',
            1,
            'test@gmail.com',
            '2@ertorpe$'
        );
        $this->assertSame('2@ertorpe$', $user->getPassword());
    }
}