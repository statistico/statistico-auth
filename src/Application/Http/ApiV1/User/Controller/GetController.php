<?php

namespace Statistico\Auth\Application\Http\ApiV1\User\Controller;

use Ramsey\Uuid\Exception\InvalidUuidStringException;
use Ramsey\Uuid\Uuid;
use Statistico\Auth\Application\Http\ApiV1\CreatesJsendResponses;
use Statistico\Auth\Boundary\User\UserService;
use Statistico\Auth\Framework\Exception\NotFoundException;
use Symfony\Component\HttpFoundation\Response;

class GetController
{
    use CreatesJsendResponses;

    /**
     * @var UserService
     */
    private $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    public function __invoke(string $id): Response
    {
        try {
            $userId = Uuid::fromString($id);
        } catch (InvalidUuidStringException $e) {
            return $this->createFailResponse(["ID {$id} is not a valid Uuid string"], 404);
        }

        try {
            $user = $this->service->getUserById($userId);
        } catch (NotFoundException $e) {
            return $this->createFailResponse([$e->getMessage()], 404);
        }

        return $this->createSuccessResponse(['user' => $user], 200);
    }
}
