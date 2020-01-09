<?php

namespace Statistico\Auth\Application\Http\HealthCheck\Controller;

use Symfony\Component\HttpFoundation\Response;

class HealthCheckController
{
    public function __invoke(): Response
    {
        return new Response('Healthcheck OK');
    }
}
