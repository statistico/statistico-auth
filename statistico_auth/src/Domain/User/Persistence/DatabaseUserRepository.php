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
//        $query = $this->userTable()->where('id = ?')->setParameter(0, $id->getBytes());

        return 'Hello World';
    }

    public function insert(User $user): void
    {
        $query = $this->connection->createQueryBuilder()
            ->insert('user')
            ->values([
               'id' => $user->getId()->getBytes(),
               'first_name' => $user->getFirstName(),
               'last_name' => $user->getLastName(),
               'email' => $user->getEmail(),
               'created_at' => 11111111,
               'updated_at' => 11111111,
            ]);

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
