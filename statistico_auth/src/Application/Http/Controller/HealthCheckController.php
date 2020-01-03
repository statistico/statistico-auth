<?php

namespace App\Application\Http\Controller;

use Symfony\Component\HttpFoundation\Response;

class HealthCheckController
{
    public function __invoke()
    {
        return new Response('Healthcheck OK');
    }
}
