<?php

namespace Statistico\Auth\Domain\Subscription\Betting\Persistence;

use Doctrine\DBAL\Connection;
use Ramsey\Uuid\UuidInterface;
use Statistico\Auth\Domain\Subscription\Betting\BettingSubscription;
use Statistico\Auth\Framework\Exception\NotFoundException;
use Statistico\Auth\Framework\Time\Clock;

class DatabaseBettingSubscriptionRepository implements BettingSubscriptionRepository
{
    /**
     * @var Connection
     */
    private $connection;
    /**
     * @var Clock
     */
    private $clock;

    public function __construct(Connection $connection, Clock $clock)
    {
        $this->connection = $connection;
        $this->clock = $clock;
    }

    public function insert(BettingSubscription $subscription): void
    {
        $query = $this->connection->createQueryBuilder()
            ->insert('betting_subscription')
            ->values([
                'user_id' => ':user_id',
                'type' => ':type',
                'exclude_teams' => ':exclude_teams',
                'created_at' => ':created',
                'updated_at' => ':updated',
            ])
            ->setParameter(':user_id', $subscription->getUserId()->getBytes())
            ->setParameter(':type', implode(',', $subscription->getTypes()))
            ->setParameter(':exclude_teams', implode(',', $subscription->getExclude()->getTeams()))
            ->setParameter(':created', $this->clock->now()->getTimestamp())
            ->setParameter(':updated', $this->clock->now()->getTimestamp());

        $query->execute();
    }

//    /**
//     * @inheritDoc
//     */
//    public function update(BettingSubscription $subscription): void
//    {
//        // TODO: Implement update() method.
//    }
//
//    /**
//     * @inheritDoc
//     */
//    public function getByUserId(UuidInterface $id): BettingSubscription
//    {
//        // TODO: Implement getByUserId() method.
//    }
//
    public function exists(UuidInterface $id): bool
    {
        // TODO: Implement exists() method.
    }
}
