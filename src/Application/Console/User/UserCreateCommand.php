<?php

namespace Statistico\Auth\Application\Console\User;

use Statistico\Auth\Boundary\User\Exception\UserCreationException;
use Statistico\Auth\Boundary\User\UserCommand;
use Statistico\Auth\Boundary\User\UserService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UserCreateCommand extends Command
{
    protected static $defaultName = 'user:create';
    /**
     * @var UserService
     */
    private $service;

    public function __construct(UserService $service)
    {
        parent::__construct();
        $this->service = $service;
    }

    protected function configure(): void
    {
        $this->setDescription('Create a new user')
            ->addArgument('firstName', InputArgument::REQUIRED)
            ->addArgument('lastName', InputArgument::REQUIRED)
            ->addArgument('email', InputArgument::REQUIRED)
            ->addArgument('password', InputArgument::REQUIRED);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $style = new SymfonyStyle($input, $output);

        try {
            $command = $this->hydrateUserCommand($input);
        } catch (\InvalidArgumentException $e) {
            $style->error($e->getMessage());
            return 1;
        }

        try {
            $userId = $this->service->register($command);
        } catch (UserCreationException $e) {
            $style->error($e->getMessage());
            return 1;
        }

        $style->success("User created. ID {$userId}");
        
        return 0;
    }

    /**
     * @param InputInterface $input
     * @return UserCommand
     * @throws \InvalidArgumentException
     */
    private function hydrateUserCommand(InputInterface $input): UserCommand
    {
        $firstName = $input->getArgument('firstName');

        if (!$firstName || !is_string($firstName)) {
            throw new \InvalidArgumentException("Required field 'firstName' is missing or in an invalid format");
        }

        $lastName = $input->getArgument('lastName');

        if (!$lastName || !is_string($lastName)) {
            throw new \InvalidArgumentException("Required field 'lastName' is missing or in an invalid format");
        }

        $email = $input->getArgument('email');

        if (!$email || !is_string($email)) {
            throw new \InvalidArgumentException("Required field 'email' is missing or in an invalid format");
        }

        $password = $input->getArgument('password');

        if (!$password || !is_string($password)) {
            throw new \InvalidArgumentException("Required field 'password' is missing or in an invalid format");
        }

        return new UserCommand($firstName, $lastName, $email, $password);
    }
}
