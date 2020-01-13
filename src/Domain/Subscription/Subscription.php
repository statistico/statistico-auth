<?php

namespace Statistico\Auth\Domain\Subscription;

use Ramsey\Uuid\UuidInterface;

class Subscription
{
    /**
     * @var Data
     */
    private $data;
    /**
     * @var Betting
     */
    private $betting;
    /**
     * @var UuidInterface
     */
    private $userId;

    public function __construct(UuidInterface $userId, Data $data, Betting $betting)
    {
        $this->data = $data;
        $this->betting = $betting;
        $this->userId = $userId;
    }

    public function getData(): Data
    {
        return $this->data;
    }

    public function getBetting(): Betting
    {
        return $this->betting;
    }
}
