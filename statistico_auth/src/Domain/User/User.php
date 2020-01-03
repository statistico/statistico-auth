<?php

namespace Statistico\Auth\Domain\User;

use Statistico\Auth\Framework\Entity\Timestamps;

class User
{
    /**
     * @var string
     */
    private $firstName;
    /**
     * @var string
     */
    private $lastName;
    /**
     * @var string
     */
    private $email;
    /**
     * @var Timestamps
     */
    private $timestamps;

    public function __construct(string $firstName, string $lastName, string $email, Timestamps $timestamps)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->timestamps = $timestamps;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->timestamps->getCreated();
    }

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->timestamps->getUpdated();
    }
}
