<?php

namespace Statistico\Auth\Domain\Subscription;

use Ramsey\Uuid\UuidInterface;
use Statistico\Auth\Framework\Entity\Timestamps;

class BettingSubscription
{
    /**
     * @var array|BetType[]
     */
    private $types;
    /**
     * @var Exclude
     */
    private $exclude;
    /**
     * @var UuidInterface
     */
    private $userId;
    /**
     * @var Timestamps
     */
    private $timestamps;

    public function __construct(UuidInterface $userId, array $types, Exclude $exclude, Timestamps $timestamps)
    {
        $this->userId = $userId;
        $this->types = $types;
        $this->exclude = $exclude;
        $this->timestamps = $timestamps;
    }

    public function getUserId(): UuidInterface
    {
        return $this->userId;
    }

    /**
     * @return array|BetType[]
     */
    public function getTypes(): array
    {
        return $this->types;
    }

    public function getExclude(): Exclude
    {
        return $this->exclude;
    }
    
    public function getTimestamps(): Timestamps
    {
        return $this->timestamps;
    }
}
