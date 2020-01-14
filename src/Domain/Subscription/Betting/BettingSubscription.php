<?php

namespace Statistico\Auth\Domain\Subscription\Betting;

use Ramsey\Uuid\UuidInterface;
use Statistico\Auth\Framework\Entity\Timestamps;

class BettingSubscription
{
    /**
     * @var UuidInterface
     */
    private $userId;
    /**
     * @var array|BetType[]
     */
    private $types;
    /**
     * @var Exclude
     */
    private $exclude;
    /**
     * @var Timestamps|null
     */
    private $timestamps;

    /**
     * BettingSubscription constructor.
     * @param UuidInterface $userId
     * @param array|BetType[] $types
     * @param Exclude $exclude
     * @param Timestamps|null $timestamps
     */
    public function __construct(UuidInterface $userId, array $types, Exclude $exclude, ?Timestamps $timestamps)
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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->timestamps !== null ? $this->timestamps->getCreated() : null;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->timestamps !== null ? $this->timestamps->getUpdated() : null;
    }
}
