<?php

namespace Statistico\Auth\Domain\Subscription\Betting\Persistence;

use Doctrine\DBAL\Connection;
use Ramsey\Uuid\Uuid;
use Statistico\Auth\Domain\Subscription\Betting\BettingSubscription;
use Statistico\Auth\Domain\Subscription\Betting\BetType;
use Statistico\Auth\Domain\Subscription\Betting\Exclude;
use Statistico\Auth\Framework\Time\FixedClock;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class BettingSubscriptionRepositoryIntegrationTest extends KernelTestCase
{
    /**
     * @var Connection
     */
    private $connection;
    /**
     * @var DatabaseBettingSubscriptionRepository
     */
    private $repository;

    public function setUp(): void
    {
        $this->bootKernel();
        $this->connection = self::$container->get(Connection::class);
        $this->repository = new DatabaseBettingSubscriptionRepository(
            $this->connection,
            new FixedClock(new \DateTimeImmutable('2020-02-03T00:00:00'))
        );
    }

    public function test_insert_increases_table_count()
    {
        for ($i = 1; $i < 4; $i++) {
            $subscription = new BettingSubscription(
                Uuid::uuid4(),
                [BetType::OVER_UNDER_25()],
                new Exclude([8920, 56013]),
                null
            );

            $this->repository->insert($subscription);

            $statement = $this->connection->createQueryBuilder()
                ->select('*')
                ->from('betting_subscription')
                ->execute();

            $this->assertEquals($i, $statement->rowCount());
        }
    }
}
