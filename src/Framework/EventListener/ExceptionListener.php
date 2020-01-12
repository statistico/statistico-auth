<?php

namespace Statistico\Auth\Framework\EventListener;

use Statistico\Auth\Application\Http\ApiV1\CreatesJsendResponses;
use Statistico\Auth\Framework\Exception\NotAuthenticatedException;
use Statistico\Auth\Framework\Exception\NotAuthorizedException;
use Statistico\Auth\Framework\Exception\NotFoundException;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ExceptionListener
{
    use CreatesJsendResponses;

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        switch (get_class($exception)) {
            case NotAuthenticatedException::class:
                $response = $this->createFailResponse(['You are not authenticated to perform that action'], 401);
                break;
            case NotAuthorizedException::class:
                $response = $this->createFailResponse(['You are not authorized to perform that action'], 403);
                break;
            default:
                $response = $this->createErrorResponse(['Server unavailable'], 500);
                break;
        }

        $event->setResponse($response);
    }
}
