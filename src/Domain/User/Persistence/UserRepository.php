<?php

namespace Statistico\Auth\Domain\User\Persistence;

use Ramsey\Uuid\Uuid;
use Statistico\Auth\Domain\User\User;
use Statistico\Auth\Framework\Exception\NotFoundException;

interface UserRepository
{
    public function exists(Uuid $id): bool;
    public function insert(User $user): void;
//
//    /**
//     * @param Uuid $id
//     * @return User
//     * @throws NotFoundException
//     */
//    public function getById(Uuid $id): User;
}
