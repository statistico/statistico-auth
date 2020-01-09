<?php

namespace Statistico\Auth\Framework\Time;

use Cake\Chronos\Chronos;
use DateTimeImmutable;

class FixedClock implements Clock
{
    /**
     * @var DateTimeImmutable
     */
    private $currentDatetime;

    public function __construct(DateTimeImmutable $currentDatetime = null)
    {
        $this->currentDatetime = $currentDatetime ?: new DateTimeImmutable();
    }

    /**
     * {@inheritDoc}
     */
    public function getCurrentDatetime()
    {
        return Chronos::instance($this->currentDatetime);
    }

    /**
     * {@inheritdoc}
     */
    public function now()
    {
        return $this->getCurrentDatetime();
    }

    /**
     * Travel to the given datetime.
     *
     * @param DateTimeImmutable $time
     */
    public function travelTo(DateTimeImmutable $time)
    {
        $this->currentDatetime = $time;
    }

    public function getCurrentUnixtime(): float
    {
        return $this->currentDatetime->getTimestamp();
    }
}
