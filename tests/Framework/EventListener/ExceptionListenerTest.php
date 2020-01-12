<?php

namespace Statistico\Auth\Framework\EventListener;

use PHPUnit\Framework\TestCase;
use Statistico\Auth\Framework\Exception\NotAuthenticatedException;
use Statistico\Auth\Framework\Exception\NotAuthorizedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class ExceptionListenerTest extends TestCase
{
    /**
     * @var ExceptionListener
     */
    private $listener;

    public function setUp(): void
    {
        $this->listener = new ExceptionListener();
    }

    public function test_onKernelException_sets_401_response_object_on_event_if_exception_is_NotAuthenticatedException()
    {
        $event = new ExceptionEvent(
            $this->prophesize(HttpKernelInterface::class)->reveal(),
            $this->prophesize(Request::class)->reveal(),
            1,
            new NotAuthenticatedException('Not authenticated')
        );

        $this->listener->onKernelException($event);

        $response = $event->getResponse();

        $expected = (object) [
            'status' => 'fail',
            'data' => (object) [
                'errors' => [
                    (object) [
                        'message' => "You are not authenticated to perform that action",
                        'code' => 1,
                    ]
                ]
            ]
        ];

        $this->assertEquals(401, $response->getStatusCode());
        $this->assertEquals($expected, json_decode($response->getContent()));
    }

    public function test_onKernelException_sets_403_response_object_on_event_if_exception_is_NotAuthorizedException()
    {
        $event = new ExceptionEvent(
            $this->prophesize(HttpKernelInterface::class)->reveal(),
            $this->prophesize(Request::class)->reveal(),
            1,
            new NotAuthorizedException('Not authorized')
        );

        $this->listener->onKernelException($event);

        $response = $event->getResponse();

        $expected = (object) [
            'status' => 'fail',
            'data' => (object) [
                'errors' => [
                    (object) [
                        'message' => "You are not authorized to perform that action",
                        'code' => 1,
                    ]
                ]
            ]
        ];

        $this->assertEquals(403, $response->getStatusCode());
        $this->assertEquals($expected, json_decode($response->getContent()));
    }

    public function test_onKernelException_sets_500_response_object_on_event_as_default_exception_handler()
    {
        $event = new ExceptionEvent(
            $this->prophesize(HttpKernelInterface::class)->reveal(),
            $this->prophesize(Request::class)->reveal(),
            1,
            new \Exception('Default exception')
        );

        $this->listener->onKernelException($event);

        $response = $event->getResponse();

        $expected = (object) [
            'status' => 'error',
            'data' => (object) [
                'errors' => [
                    (object) [
                        'message' => "Server unavailable",
                        'code' => 1,
                    ]
                ]
            ]
        ];

        $this->assertEquals(500, $response->getStatusCode());
        $this->assertEquals($expected, json_decode($response->getContent()));
    }
}
