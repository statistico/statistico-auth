<?php

namespace Statistico\Auth\Boundary\User;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Statistico\Auth\Boundary\User\Exception\UserCreationException;
use Statistico\Auth\Domain\User\Persistence\UserRepository;
use Statistico\Auth\Domain\User\User;
use Statistico\Auth\Framework\Entity\Timestamps;
use Statistico\Auth\Framework\Exception\NotFoundException;
use Statistico\Auth\Framework\Security\PasswordHash;

class UserServiceTest extends TestCase
{
    /**
     * @var UserRepository|ObjectProphecy
     */
    private $repository;
    /**
     * @var UserService
     */
    private $service;

    public function setUp(): void
    {
        $this->repository = $this->prophesize(UserRepository::class);
        $this->service = new UserService($this->repository->reveal(), new UserPresenter());
    }

    public function test_register_inserts_new_user_and_returns_uuid()
    {
        $command = new UserCommand('Joe', 'Sweeny', 'joe@statistico.io', 'new-password');

        $this->repository->existsWithEmail($command->getEmail())->willReturn(false);

        $this->repository->insert(Argument::that(function (User $user) {
            $this->assertEquals('Joe', $user->getFirstName());
            $this->assertEquals('Sweeny', $user->getLastName());
            $this->assertEquals('joe@statistico.io', $user->getEmail());
            $this->assertTrue($user->getPasswordHash()->verify('new-password'));
            return true;
        }))->shouldBeCalled();

        $id = $this->service->register($command);

        $this->assertInstanceOf(UuidInterface::class, $id);
    }

    public function test_register_throws_UserCreationException_if_user_exists_with_email_provided()
    {
        $command = new UserCommand('Joe', 'Sweeny', 'joe@statistico.io', 'new-password');

        $this->repository->existsWithEmail($command->getEmail())->willReturn(true);

        $this->repository->insert(Argument::type(User::class))->shouldNotBeCalled();

        $this->expectException(UserCreationException::class);
        $this->service->register($command);
    }

    public function test_getUserById_returns_a_scalar_object_containing_user_data()
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

        $this->repository->getById($id)->willReturn($user);

        $fetched = $this->service->getUserById($id);

        $this->assertEquals('723b2b66-c1fb-4292-95de-21bb0aed9745', $fetched->id);
        $this->assertEquals('Joe', $fetched->firstName);
        $this->assertEquals('Sweeny', $fetched->lastName);
        $this->assertEquals('joe@statistico.io', $fetched->email);
        $this->assertEquals('2020-02-03T00:00:00+00:00', $fetched->createdAt);
        $this->assertEquals('2020-02-03T00:00:00+00:00', $fetched->updatedAt);
    }

    public function test_getUserById_throws_NotFoundException_if_user_does_not_exist()
    {
        $id = Uuid::fromString('723b2b66-c1fb-4292-95de-21bb0aed9745');

        $this->repository->getById($id)->willThrow(new NotFoundException());

        $this->expectException(NotFoundException::class);
        $this->service->getUserById($id);
    }
}
