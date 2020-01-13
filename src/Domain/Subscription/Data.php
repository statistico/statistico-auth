<?php

namespace Statistico\Auth\Domain\Subscription;

class Data
{
    /**
     * @var array|int[]
     */
    private $competitions;

    public function __construct(array $competitions)
    {
        $this->competitions = $competitions;
    }

    /**
     * @return array|int[]
     */
    public function getCompetitions(): array
    {
        return $this->competitions;
    }
}
