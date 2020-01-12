<?php

namespace Statistico\Auth\Application\Http\ApiV1\User\Controller;

use Doctrine\DBAL\Connection;
use Ramsey\Uuid\Uuid;
use Statistico\Auth\Domain\User\Persistence\DatabaseUserRepository;
use Statistico\Auth\Domain\User\User;
use Statistico\Auth\Framework\Security\PasswordHash;
use Statistico\Auth\Framework\Time\FixedClock;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelInterface;

class GetControllerIntegrationTest extends KernelTestCase
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

    public function test_invoke_returns_200_response_containing_user_data()
    {
        $user = new User(
            $id = Uuid::uuid4(),
            'Joe',
            'Sweeny',
            'joe@statistico.io',
            PasswordHash::createFromRaw('password')
        );

        $this->createUser($user);

        $request = Request::create("/api/v1/user/{$id}", 'GET');

        $response = $this->app->handle($request);

        $json = json_decode($response->getContent());

        $expected = (object) [
            'status' => 'success',
            'data' => (object) [
                'user' => (object) [
                    'id' => (string) $id,
                    'firstName' => 'Joe',
                    'lastName' => 'Sweeny',
                    'email' => 'joe@statistico.io',
                    'createdAt' => '2020-01-12T00:00:00+00:00',
                    'updatedAt' => '2020-01-12T00:00:00+00:00',
                ]
            ]
        ];

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($expected, $json);
    }

    public function test_invoke_returns_404_response_if_id_provided_is_an_invalid_uuid_string()
    {
        $request = Request::create('/api/v1/user/1', 'GET');

        $response = $this->app->handle($request);

        $json = json_decode($response->getContent());

        $expected = (object) [
            'status' => 'fail',
            'data' => (object) [
                'errors' => [
                    (object) [
                        'message' => 'ID 1 is not a valid Uuid string',
                        'code' => 1,
                    ]
                ]
            ]
        ];

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals($expected, $json);
    }

    public function test_invoke_returns_a_404_response_if_user_does_not_exist()
    {
        $request = Request::create('/api/v1/user/4081e365-709a-429b-aa25-d829750e8b95', 'GET');

        $response = $this->app->handle($request);

        $json = json_decode($response->getContent());

        $expected = (object) [
            'status' => 'fail',
            'data' => (object) [
                'errors' => [
                    (object) [
                        'message' => 'User with ID 4081e365-709a-429b-aa25-d829750e8b95 does not exist',
                        'code' => 1,
                    ]
                ]
            ]
        ];

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals($expected, $json);
    }

    private function createUser(User $user)
    {
        $connection = self::$container->get(Connection::class);

        $repo = new DatabaseUserRepository(
            $connection,
            new FixedClock(new \DateTimeImmutable('2020-01-12T00:00:00+00:00'))
        );

        $repo->insert($user);
    }
}
