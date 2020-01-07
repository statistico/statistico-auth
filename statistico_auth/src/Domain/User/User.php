<?php

namespace Statistico\Auth\Domain\User;

use Ramsey\Uuid\UuidInterface;
use Statistico\Auth\Framework\Entity\Timestamps;
use Statistico\Auth\Framework\Security\PasswordHash;

class User
{
    /**
     * @var UuidInterface
     */
    private $id;
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
    /**
     * @var PasswordHash
     */
    private $passwordHash;

    public function __construct(
        UuidInterface $id,
        string $firstName,
        string $lastName,
        string $email,
        PasswordHash $passwordHash,
        Timestamps $timestamps
    ) {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->passwordHash = $passwordHash;
        $this->timestamps = $timestamps;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
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

    public function getPasswordHash(): PasswordHash
    {
        return $this->passwordHash;
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
