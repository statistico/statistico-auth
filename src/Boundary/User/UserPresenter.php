<?php

namespace Statistico\Auth\Boundary\User;

use Statistico\Auth\Domain\User\User;

class UserPresenter
{
    public function userToDto(User $user): \stdClass
    {
        return (object) [
            'id' => $user->getId()->toString(),
            'firstName' => $user->getFirstName(),
            'lastName' => $user->getLastName(),
            'email' => $user->getEmail(),
            'createdAt' => $user->getCreatedAt() !== null ? $user->getCreatedAt()->format(\DATE_RFC3339) : null,
            'updatedAt' => $user->getCreatedAt() !== null ? $user->getUpdatedAt()->format(\DATE_RFC3339) : null,
        ];
    }
}
