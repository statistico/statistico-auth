<?php

namespace Statistico\Auth\Domain\User\Persistence;

use
    Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\Statement;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Statistico\Auth\Domain\User\User;
use Statistico\Auth\Framework\Entity\Timestamps;
use Statistico\Auth\Framework\Exception\NotFoundException;
use Statistico\Auth\Framework\Security\PasswordHash;
use Statistico\Auth\Framework\Time\Clock;
use Statistico\Auth\Framework\Time\ParsesTime;

class DatabaseUserRepository implements UserRepository
{
    use ParsesTime;

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
            ->setParameter(':password', $user->getPasswordHash()->__toString())
            ->setParameter(':created', $this->clock->now()->getTimestamp())
            ->setParameter(':updated', $this->clock->now()->getTimestamp());

        $query->execute();
    }

    public function getById(UuidInterface $id): User
    {
        $executed = $this->connection->createQueryBuilder()
            ->select('*')
            ->from('user')
            ->where('id = :id')
            ->setParameter(':id', $id->getBytes())
            ->execute();

        if (!$executed instanceof Statement) {
            throw new NotFoundException("User with ID {$id->toString()} does not exist");
        }

        $row = $executed->fetch();

        if ($row === false) {
            throw new NotFoundException("User with ID {$id->toString()} does not exist");
        }

        return $this->hydrateUserFromRow((object) $row);
    }

    public function existsWithEmail(string $email): bool
    {
        $executed = $this->connection->createQueryBuilder()
            ->select('id')
            ->from('user')
            ->where('email = :email')
            ->setParameter(':email', $email)
            ->execute();

        if (!$executed instanceof Statement) {
            return false;
        }

        return $executed->fetch() !== false;
    }

    private function hydrateUserFromRow(\stdClass $row): User
    {
        $timestamps = new Timestamps(
            $this->fromUnixTimestamp($row->created_at),
            $this->fromUnixTimestamp($row->updated_at)
        );

        $user = new User(
            Uuid::fromBytes($row->id),
            $row->first_name,
            $row->last_name,
            $row->email,
            new PasswordHash($row->password),
            $timestamps
        );

        return $user;
    }
}
