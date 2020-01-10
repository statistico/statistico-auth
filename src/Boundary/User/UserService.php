<?php

namespace Statistico\Auth\Boundary\User;

use Ramsey\Uuid\UuidInterface;
use Statistico\Auth\Boundary\User\Exception\UserCreationException;
use Statistico\Auth\Domain\User\Persistence\UserRepository;
use Statistico\Auth\Domain\User\User;
use Statistico\Auth\Framework\Exception\NotFoundException;
use Statistico\Auth\Framework\Identity\GeneratesUuid;

class UserService
{
    use GeneratesUuid;

    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var UserPresenter
     */
    private $presenter;

    public function __construct(UserRepository $userRepository, UserPresenter $presenter)
    {
        $this->userRepository = $userRepository;
        $this->presenter = $presenter;
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

    /**
     * @param UuidInterface $id
     * @return \stdClass
     * @throws NotFoundException
     */
    public function getUserById(UuidInterface $id): \stdClass
    {
        $user = $this->userRepository->getById($id);

        return $this->presenter->userToDto($user);
    }
}
