<?php

namespace Statistico\Auth\Application\Console\User;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Statistico\Auth\Boundary\User\Exception\UserCreationException;
use Statistico\Auth\Boundary\User\UserCommand;
use Statistico\Auth\Boundary\User\UserService;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

class UserCreateCommandTest extends TestCase
{
    /**
     * @var UserService|ObjectProphecy
     */
    private $service;
    /**
     * @var Command
     */
    private $command;

    public function setUp(): void
    {
        $this->service = $this->prophesize(UserService::class);
        $command = new UserCreateCommand($this->service->reveal());
        $app = new Application();
        $app->add($command);
        $this->command = $app->find('user:create');
    }

    public function test_run_creates_a_new_user_and_returns_the_user_id()
    {
        $input = new ArrayInput([
            'firstName' => 'Joe',
            'lastName' => 'Sweeny',
            'email' => 'joe@statistico.io',
            'password' => 'password',
        ]);

        $this->service->register(Argument::that(function (UserCommand $command) {
            $this->assertEquals('Joe', $command->getFirstName());
            $this->assertEquals('Sweeny', $command->getLastName());
            $this->assertEquals('joe@statistico.io', $command->getEmail());
            $this->assertTrue($command->getPassword()->verify('password'));
            return true;
        }))->willReturn('72233d32-cf1d-47db-9957-6c1a8e6c102d');

        $output = new BufferedOutput();

        $result = $this->command->run($input, $output);

        $this->assertEquals('[OK] User created. ID 72233d32-cf1d-47db-9957-6c1a8e6c102d', trim($output->fetch()));
        $this->assertEquals(0, $result);
    }

    public function test_run_returns_error_if_required_field_is_missing()
    {
        $input = new ArrayInput([
            'firstName' => 'Joe',
            'lastName' => 'Sweeny',
            'email' => '',
            'password' => 'password',
        ]);

        $this->service->register(Argument::type(UserCommand::class))->shouldNotBeCalled();

        $output = new BufferedOutput();

        $result = $this->command->run($input, $output);

        $this->assertEquals(
            "[ERROR] Required field 'email' is missing or in an invalid format",
            trim($output->fetch())
        );
        $this->assertEquals(1, $result);
    }

    public function test_run_returns_error_if_user_service_throws_an_exception()
    {
        $input = new ArrayInput([
            'firstName' => 'Joe',
            'lastName' => 'Sweeny',
            'email' => 'joe@statistico.io',
            'password' => 'password',
        ]);

        $this->service->register(Argument::that(function (UserCommand $command) {
            $this->assertEquals('Joe', $command->getFirstName());
            $this->assertEquals('Sweeny', $command->getLastName());
            $this->assertEquals('joe@statistico.io', $command->getEmail());
            $this->assertTrue($command->getPassword()->verify('password'));
            return true;
        }))->willThrow(new UserCreationException('User exists'));

        $output = new BufferedOutput();

        $result = $this->command->run($input, $output);

        $this->assertEquals("[ERROR] User exists", trim($output->fetch()));
        $this->assertEquals(1, $result);
    }

    public function test_run_returns_error_if_required_argument_is_not_passed_as_a_string()
    {
        $input = new ArrayInput([
            'firstName' => 'Joe',
            'lastName' => [
                'Sweeny',
                'Legend',
            ],
            'email' => 'joe@statistico.io',
            'password' => 'password',
        ]);

        $this->service->register(Argument::type(UserCommand::class))->shouldNotBeCalled();

        $output = new BufferedOutput();

        $result = $this->command->run($input, $output);

        $this->assertEquals(
            "[ERROR] Required field 'lastName' is missing or in an invalid format",
            trim($output->fetch())
        );
        $this->assertEquals(1, $result);
    }
}
