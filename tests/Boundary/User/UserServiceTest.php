<?php

namespace Statistico\Auth\Boundary\User;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Ramsey\Uuid\UuidInterface;
use Statistico\Auth\Boundary\User\Exception\UserCreationException;
use Statistico\Auth\Domain\User\Persistence\UserRepository;
use Statistico\Auth\Domain\User\User;

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
        $this->service = new UserService($this->repository->reveal());
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
}
