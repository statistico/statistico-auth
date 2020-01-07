<?php

namespace Statistico\Auth\Domain\User\Persistence;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ramsey\Uuid\Uuid;
use Statistico\Auth\Domain\User\User;
use Statistico\Auth\Framework\Entity\Timestamps;
use Statistico\Auth\Framework\Security\PasswordHash;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserRepositoryIntegrationTest extends KernelTestCase
{
    /**
     * @var UserRepository
     */
    private $repository;
    /**
     * @var QueryBuilder
     */
    private $connection;

    public function setUp(): void
    {
        static::bootKernel();
        $this->repository = self::$container->get(UserRepository::class);
        $this->connection = self::$container->get(Connection::class)->createQueryBuilder();
    }

    public function test_insert_increases_table_count()
    {
        $timestamps = new Timestamps(
            new \DateTimeImmutable('2020-02-03T00:00:00'),
            new \DateTimeImmutable('2020-02-03T00:00:00')
        );

        for ($i = 1; $i < 4; $i++) {
            $user = new User(
                Uuid::uuid4(),
                'Joe',
                'Sweeny',
                'joe@statistico.io',
                new PasswordHash('password'),
                $timestamps
            );

            $this->repository->insert($user);

            $statement = $this->connection->select('*')->from('user')->execute();

            $this->assertEquals($i, $statement->rowCount());
        }
    }
}
