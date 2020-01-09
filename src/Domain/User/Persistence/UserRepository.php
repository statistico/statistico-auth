<?php

namespace Statistico\Auth\Domain\User\Persistence;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Statistico\Auth\Domain\User\User;
use Statistico\Auth\Framework\Exception\NotFoundException;

interface UserRepository
{
    public function insert(User $user): void;

    /**
     * @param UuidInterface $id
     * @return User
     * @throws NotFoundException
     */
    public function getById(UuidInterface $id): User;

    public function existsWithEmail(string $email): bool;
}
