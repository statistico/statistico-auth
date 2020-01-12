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

    protected function configure()
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
        if (!$input->getArgument('firstName')) {
            throw new \InvalidArgumentException("Required field 'firstName' is missing");
        }

        if (!$input->getArgument('lastName')) {
            throw new \InvalidArgumentException("Required field 'lastName' is missing");
        }

        if (!$input->getArgument('email')) {
            throw new \InvalidArgumentException("Required field 'email' is missing");
        }

        if (!$input->getArgument('password')) {
            throw new \InvalidArgumentException("Required field 'email' is missing");
        }

        return new UserCommand(
            $input->getArgument('firstName'),
            $input->getArgument('lastName'),
            $input->getArgument('email'),
            $input->getArgument('password'),
        );
    }
}
