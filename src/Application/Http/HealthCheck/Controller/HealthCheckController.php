<?php

namespace Statistico\Auth\Application\Http\HealthCheck\Controller;

use Symfony\Component\HttpFoundation\Response;

class HealthCheckController
{
    public function __invoke()
    {
        return new Response('Healthcheck OK');
    }
}
