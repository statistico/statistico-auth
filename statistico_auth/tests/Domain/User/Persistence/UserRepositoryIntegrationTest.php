<?php

namespace Statistico\Auth\Domain\User\Persistence;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ramsey\Uuid\Uuid;
use Statistico\Auth\Domain\User\User;
use Statistico\Auth\Framework\Entity\Timestamps;
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
        $kernel = self::bootKernel();
        $this->repository = $kernel->getContainer()->get(UserRepository::class);
        $this->connection = $kernel->getContainer()->get(Connection::class)->createQueryBuilder();
    }

    public function test_insert_increases_table_count()
    {
        $timestamps = new Timestamps(
            new \DateTimeImmutable('2020-02-03T00:00:00'),
            new \DateTimeImmutable('2020-02-03T00:00:00')
        );

        $id = Uuid::fromString('723b2b66-c1fb-4292-95de-21bb0aed9745');

        $user = new User($id,'Joe', 'Sweeny', 'joe@statistico.io', $timestamps);

        $this->repository->insert($user);

        $total = $this->connection->select('*')->from('user')->execute();

        dd($total);
    }
}
