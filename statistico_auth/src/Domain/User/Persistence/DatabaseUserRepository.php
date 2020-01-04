<?php

namespace Statistico\Auth\Domain\User\Persistence;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ramsey\Uuid\Uuid;
use Statistico\Auth\Domain\User\User;
use Statistico\Auth\Framework\Exception\NotFoundException;

class DatabaseUserRepository implements UserRepository
{
    /**
     * @var Connection
     */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function exists(Uuid $id): bool
    {
        $query = $this->userTable()->where('id = ?')->setParameter(0, $id->getBytes());

        return true;
    }

    public function insert(User $user): void
    {
        // TODO: Implement insert() method.
    }

    /**
     * @param Uuid $id
     * @return User
     * @throws NotFoundException
     */
    public function getById(Uuid $id): User
    {
        // TODO: Implement getById() method.
    }

    private function userTable(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()->from('user');
    }
}
