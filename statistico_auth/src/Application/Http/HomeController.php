<?php

namespace Statistico\Auth\Application\Http;

use Symfony\Component\HttpFoundation\Response;

class HomeController
{
    public function __invoke()
    {
        return new Response('Welcome to the Statistico Auth API');
    }
}
