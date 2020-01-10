<?php

namespace Statistico\Auth\Boundary\User;

use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Statistico\Auth\Domain\User\User;
use Statistico\Auth\Framework\Entity\Timestamps;
use Statistico\Auth\Framework\Security\PasswordHash;

class UserPresenterTest extends TestCase
{
    public function test_userToDto_returns_a_scalar_object_containing_user_data()
    {
        $id = Uuid::fromString('723b2b66-c1fb-4292-95de-21bb0aed9745');

        $timestamps = new Timestamps(
            new \DateTimeImmutable('2020-02-03T00:00:00'),
            new \DateTimeImmutable('2020-02-03T00:00:00')
        );

        $user = new User(
            $id,
            'Joe',
            'Sweeny',
            'joe@statistico.io',
            PasswordHash::createFromRaw('password'),
            $timestamps
        );

        $dto = (new UserPresenter())->userToDto($user);

        $this->assertEquals('723b2b66-c1fb-4292-95de-21bb0aed9745', $dto->id);
        $this->assertEquals('Joe', $dto->firstName);
        $this->assertEquals('Sweeny', $dto->lastName);
        $this->assertEquals('joe@statistico.io', $dto->email);
        $this->assertEquals('2020-02-03T00:00:00+00:00', $dto->createdAt);
        $this->assertEquals('2020-02-03T00:00:00+00:00', $dto->updatedAt);
    }
}
