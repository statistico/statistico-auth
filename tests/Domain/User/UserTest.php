<?php

namespace Statistico\Auth\Domain\User;

use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Statistico\Auth\Framework\Entity\Timestamps;
use Statistico\Auth\Framework\Security\PasswordHash;

class UserTest extends TestCase
{
    public function test_properties_set_on_instantiated_user()
    {
        $timestamps = new Timestamps(
            new \DateTimeImmutable('2020-02-03T00:00:00'),
            new \DateTimeImmutable('2020-02-03T00:00:00')
        );

        $id = Uuid::fromString('723b2b66-c1fb-4292-95de-21bb0aed9745');

        $user = new User(
            $id,
            'Joe',
            'Sweeny',
            'joe@statistico.io',
            PasswordHash::createFromRaw('password'),
            $timestamps
        );

        $this->assertEquals($id, $user->getId());
        $this->assertEquals('Joe', $user->getFirstName());
        $this->assertEquals('Sweeny', $user->getLastName());
        $this->assertEquals('joe@statistico.io', $user->getEmail());
        $this->assertTrue($user->getPasswordHash()->verify('password'));
        $this->assertEquals(new \DateTimeImmutable('2020-02-03T00:00:00'), $user->getCreatedAt());
        $this->assertEquals(new \DateTimeImmutable('2020-02-03T00:00:00'), $user->getUpdatedAt());
    }
}
