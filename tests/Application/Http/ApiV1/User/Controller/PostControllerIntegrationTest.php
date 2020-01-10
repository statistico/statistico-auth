<?php

namespace Statistico\Auth\Application\Http\ApiV1\User\Controller;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;

class PostControllerIntegrationTest extends KernelTestCase
{
    /**
     * @var \Symfony\Component\HttpKernel\KernelInterface
     */
    private $app;

    public function setUp(): void
    {
        $this->bootKernel();
        $this->app = static::createKernel();
    }

    public function test_invoke_returns_200_response_containing_user_id_in_location_header()
    {
        $body = (object) [
            'firstName' => 'Joe',
            'lastName' => 'Sweeny',
            'email' => 'joe@statistico.io',
            'password' => 'password123'
        ];

        $request = Request::create('/api/v1/user', 'POST', [], [], [], [], json_encode($body));

        $response = $this->app->handle($request);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertTrue($response->headers->has('Location'));
    }
}
