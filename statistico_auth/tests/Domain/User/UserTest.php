<?php

namespace Statistico\Auth\Domain\User;

use PHPUnit\Framework\TestCase;
use Statistico\Auth\Framework\Entity\Timestamps;

class UserTest extends TestCase
{
    public function test_properties_set_on_instantiated_user()
    {
        $timestamps = new Timestamps(
            new \DateTimeImmutable('2020-02-03T00:00:00'),
            new \DateTimeImmutable('2020-02-03T00:00:00')
        );

        $user = new User('Joe', 'Sweeny', 'joe@statistico.io', $timestamps);

        $this->assertEquals('Joe', $user->getFirstName());
        $this->assertEquals('Sweeny', $user->getLastName());
        $this->assertEquals('joe@statistico.io', $user->getEmail());
        $this->assertEquals(new \DateTimeImmutable('2020-02-03T00:00:00'), $user->getCreatedAt());
        $this->assertEquals(new \DateTimeImmutable('2020-02-03T00:00:00'), $user->getUpdatedAt());
    }
}
