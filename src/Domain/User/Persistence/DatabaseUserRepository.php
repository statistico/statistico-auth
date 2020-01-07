<?php

namespace Statistico\Auth\Domain\User\Persistence;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ramsey\Uuid\Uuid;
use Statistico\Auth\Domain\User\User;
use Statistico\Auth\Framework\Exception\NotFoundException;
use Statistico\Auth\Framework\Time\Clock;

class DatabaseUserRepository implements UserRepository
{
    /**
     * @var Connection
     */
    private $connection;
    /**
     * @var Clock
     */
    private $clock;

    public function __construct(Connection $connection, Clock $clock)
    {
        $this->connection = $connection;
        $this->clock = $clock;
    }

    public function exists(Uuid $id): bool
    {
//        $query = $this->userTable()->where('id = ?')->setParameter(0, $id->getBytes());

        return 'Hello World';
    }

    public function insert(User $user): void
    {
        $query = $this->connection->createQueryBuilder()
            ->insert('user')
            ->values([
               'id' => ':id',
               'first_name' => ':first',
               'last_name' => ':last',
               'email' => ':email',
               'password' => ':password',
               'created_at' => ':created',
               'updated_at' => ':updated',
            ])
            ->setParameter(':id', $user->getId()->getBytes())
            ->setParameter(':first', $user->getFirstName())
            ->setParameter(':last', $user->getLastName())
            ->setParameter(':email', $user->getEmail())
            ->setParameter(':password', 'password')
            ->setParameter(':created', $this->clock->now()->getTimestamp())
            ->setParameter(':updated', $this->clock->now()->getTimestamp());

        $query->execute();
    }

//    /**
//     * @param Uuid $id
//     * @return User
//     * @throws NotFoundException
//     */
//    public function getById(Uuid $id): User
//    {
//        // TODO: Implement getById() method.
//    }

    private function userTable(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()->from('user');
    }
}
