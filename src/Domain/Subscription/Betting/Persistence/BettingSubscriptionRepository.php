<?php

namespace Statistico\Auth\Domain\Subscription\Betting\Persistence;

use Ramsey\Uuid\UuidInterface;
use Statistico\Auth\Domain\Subscription\Betting\BettingSubscription;
use Statistico\Auth\Framework\Exception\NotFoundException;

interface BettingSubscriptionRepository
{
    public function insert(BettingSubscription $subscription): void;

//    /**
//     * @param BettingSubscription $subscription
//     * @return void
//     * @throws NotFoundException
//     */
//    public function update(BettingSubscription $subscription): void;
//
//    /**
//     * @param UuidInterface $id
//     * @return BettingSubscription
//     * @throws NotFoundException
//     */
//    public function getByUserId(UuidInterface $id): BettingSubscription;
//
//    public function exists(UuidInterface $id): bool;
}
