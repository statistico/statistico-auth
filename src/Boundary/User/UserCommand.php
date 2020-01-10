<?php

namespace Statistico\Auth\Boundary\User;

use Statistico\Auth\Framework\Security\PasswordHash;

class UserCommand
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
     * @var PasswordHash
     */
    private $password;

    /**
     * UserCommand constructor.
     * @param string $firstName
     * @param string $lastName
     * @param string $email
     * @param string $password
     * @throws \InvalidArgumentException
     */
    public function __construct(string $firstName, string $lastName, string $email, string $password)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->password = PasswordHash::createFromRaw($password);
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

    public function getPassword(): PasswordHash
    {
        return $this->password;
    }
}
