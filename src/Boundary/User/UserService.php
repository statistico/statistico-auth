<?php

namespace Statistico\Auth\Boundary\User;

use Psr\Log\LoggerInterface;
use Ramsey\Uuid\UuidInterface;
use Statistico\Auth\Boundary\User\Exception\UserCreationException;
use Statistico\Auth\Domain\User\Persistence\UserRepository;
use Statistico\Auth\Domain\User\User;
use Statistico\Auth\Framework\Identity\GeneratesUuid;

class UserService
{
    use GeneratesUuid;

    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(UserRepository $userRepository, LoggerInterface $logger)
    {
        $this->userRepository = $userRepository;
        $this->logger = $logger;
    }

    /**
     * @param UserCommand $command
     * @return UuidInterface
     * @throws UserCreationException
     */
    public function register(UserCommand $command): UuidInterface
    {
        if ($this->userRepository->existsWithEmail($command->getEmail())) {
            throw new UserCreationException("User already exists with the email provided");
        }

        $user = new User(
            $this->generateUuid(),
            $command->getFirstName(),
            $command->getLastName(),
            $command->getEmail(),
            $command->getPassword()
        );

        $this->userRepository->insert($user);

        return $user->getId();
    }
}
