<?php

namespace Statistico\Auth\Domain\User\Persistence;

use Doctrine\DBAL\Connection;
use Ramsey\Uuid\Uuid;
use Statistico\Auth\Domain\User\User;
use Statistico\Auth\Framework\Exception\NotFoundException;
use Statistico\Auth\Framework\Security\PasswordHash;
use Statistico\Auth\Framework\Time\FixedClock;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserRepositoryIntegrationTest extends KernelTestCase
{
    /**
     * @var UserRepository
     */
    private $repository;
    /**
     * @var Connection
     */
    private $connection;

    public function setUp(): void
    {
        static::bootKernel();
        $this->connection = self::$container->get(Connection::class);
        $this->repository = new DatabaseUserRepository(
            $this->connection,
            new FixedClock(new \DateTimeImmutable('2020-02-03T00:00:00'))
        );
    }

    public function test_insert_increases_table_count()
    {
        for ($i = 1; $i < 4; $i++) {
            $user = new User(
                Uuid::uuid4(),
                'Joe',
                'Sweeny',
                'joe@statistico.io',
                PasswordHash::createFromRaw('password'),
                null
            );

            $this->repository->insert($user);

            $statement = $this->connection->createQueryBuilder()->select('*')->from('user')->execute();

            $this->assertEquals($i, $statement->rowCount());
        }
    }

    public function test_getById_returns_a_user_object()
    {
        $user = new User(
            $id = Uuid::uuid4(),
            'Joe',
            'Sweeny',
            'joe@statistico.io',
            PasswordHash::createFromRaw('password'),
            null
        );

        $this->repository->insert($user);

        $fetched = $this->repository->getById($id);

        $this->assertEquals($id, $fetched->getId());
        $this->assertEquals('Joe', $fetched->getFirstName());
        $this->assertEquals('Sweeny', $fetched->getLastName());
        $this->assertEquals('joe@statistico.io', $fetched->getEmail());
        $this->assertEquals(new \DateTimeImmutable('2020-02-03T00:00:00'), $fetched->getCreatedAt());
        $this->assertEquals(new \DateTimeImmutable('2020-02-03T00:00:00'), $fetched->getUpdatedAt());
    }

    public function test_getById_throws_NotFoundException_if_user_does_not_exist()
    {
        $this->expectException(NotFoundException::class);
        $this->repository->getById(Uuid::uuid4());
    }

    public function test_existsWithEmail_returns_true_if_user_exists_with_email_address()
    {
        $user = new User(
            $id = Uuid::uuid4(),
            'Joe',
            'Sweeny',
            'joe@statistico.io',
            PasswordHash::createFromRaw('password'),
            null
        );

        $this->repository->insert($user);

        $this->assertTrue($this->repository->existsWithEmail($user->getEmail()));
    }

    public function test_existsWithEmail_returns_false_if_user_does_not_exist_with_email_address()
    {
        $this->assertFalse($this->repository->existsWithEmail('joe@doesnotexist.com'));
    }
}
