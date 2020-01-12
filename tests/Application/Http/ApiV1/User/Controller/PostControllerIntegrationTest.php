<?php

namespace Statistico\Auth\Application\Http\ApiV1\User\Controller;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelInterface;

class PostControllerIntegrationTest extends KernelTestCase
{
    /**
     * @var KernelInterface
     */
    private $app;

    public function setUp(): void
    {
        $this->bootKernel();
        $this->app = static::createKernel();
    }

    public function test_invoke_returns_201_response_containing_user_id_in_location_header()
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

    public function test_invoke_returns_400_response_if_request_body_cannot_be_parsed()
    {
        $request = Request::create('/api/v1/user', 'POST', [], [], [], [], 'Not valid');

        $response = $this->app->handle($request);

        $json = json_decode($response->getContent());

        $expected = (object) [
            'status' => 'fail',
            'data' => (object) [
                'errors' => [
                    (object) [
                        'message' => 'Request body provided is not in a valid format',
                        'code' => 1,
                    ]
                ]
            ]
        ];

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals($expected, $json);
    }

    public function test_invoke_returns_422_response_if_required_request_body_parameter_is_missing()
    {
        $body = (object) [
            'firstName' => 'Joe',
            'email' => 'joe@statistico.io',
            'password' => 'password123'
        ];

        $request = Request::create('/api/v1/user', 'POST', [], [], [], [], json_encode($body));

        $response = $this->app->handle($request);

        $json = json_decode($response->getContent());

        $expected = (object) [
            'status' => 'fail',
            'data' => (object) [
                'errors' => [
                    (object) [
                        'message' => "User creation failed with the message: Required field 'lastName' is missing",
                        'code' => 1,
                    ]
                ]
            ]
        ];

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertEquals($expected, $json);
    }

    public function test_invoke_returns_422_response_if_user_exists_with_email()
    {
        $body = (object) [
            'firstName' => 'Joe',
            'lastName' => 'Sweeny',
            'email' => 'joe@statistico.io',
            'password' => 'password123'
        ];

        $request = Request::create('/api/v1/user', 'POST', [], [], [], [], json_encode($body));

        $this->app->handle($request);

        $response = $this->app->handle($request);

        $json = json_decode($response->getContent());

        $expected = (object) [
            'status' => 'fail',
            'data' => (object) [
                'errors' => [
                    (object) [
                        'message' => "User creation failed with the message: User already exists with the email provided",
                        'code' => 1,
                    ]
                ]
            ]
        ];

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertEquals($expected, $json);
    }
}
