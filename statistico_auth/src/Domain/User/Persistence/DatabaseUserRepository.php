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
               'id' => '?',
               'first_name' => '?',
               'last_name' => '?',
               'email' => '?',
               'password' => '?',
               'created_at' => 11111111,
               'updated_at' => 11111111,
            ])
            ->setParameter(0, $user->getId()->getBytes())
            ->setParameter(1, $user->getFirstName())
            ->setParameter(2, $user->getLastName())
            ->setParameter(3, $user->getEmail())
            ->setParameter(4, 'password');

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
