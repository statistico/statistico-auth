<?php

namespace App\Application\Http\Controller;

use Symfony\Component\HttpFoundation\Response;

class HomeController
{
    public function __invoke()
    {
        return new Response('Welcome to the Statistico Auth API');
    }
}
