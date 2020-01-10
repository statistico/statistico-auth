<?php

namespace Statistico\Auth\Application\Http\ApiV1\User\Controller;

use Statistico\Auth\Application\Http\ApiV1\CreatesJsendResponses;
use Statistico\Auth\Boundary\User\Exception\UserCreationException;
use Statistico\Auth\Boundary\User\UserCommand;
use Statistico\Auth\Boundary\User\UserService;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PostController
{
    use CreatesJsendResponses;

    /**
     * @var UserService
     */
    private $userService;
    /**
     * @var ContainerBagInterface
     */
    private $config;

    public function __construct(UserService $userService, ContainerBagInterface $config)
    {
        $this->userService = $userService;
        $this->config = $config;
    }

    public function __invoke(Request $request): Response
    {
        $body = json_decode((string) $request->getContent());

        if (!$body) {
            return $this->createFailResponse(['Request body provided is not in a valid format'], 400);
        }

        try {
            $command = $this->hydrateUserCommand($body);

            $userId = $this->userService->register($command);
        } catch (\InvalidArgumentException | UserCreationException $e) {
            return $this->createFailResponse(["User creation failed with the message: {$e->getMessage()}"], 422);
        }

        $headers = [
            'Location' => "{$this->config->get('app.host')}/api/v1/user/{$userId}"
        ];

        return $this->createSuccessResponse([], 201, $headers);
    }

    private function hydrateUserCommand(\stdClass $body): UserCommand
    {
        if (!isset($body->firstName)) {
            throw new \InvalidArgumentException("Required field 'firstName' is missing");
        }

        if (!isset($body->lastName)) {
            throw new \InvalidArgumentException("Required field 'lastName' is missing");
        }

        if (!isset($body->email)) {
            throw new \InvalidArgumentException("Required field 'email' is missing");
        }

        if (!isset($body->password)) {
            throw new \InvalidArgumentException("Required field 'email' is missing");
        }

        $command = new UserCommand(
            $body->firstName,
            $body->lastName,
            $body->email,
            $body->password,
        );

        return $command;
    }
}
