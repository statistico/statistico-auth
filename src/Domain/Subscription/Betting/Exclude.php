<?php

namespace Statistico\Auth\Domain\Subscription\Betting;

class Exclude
{
    /**
     * @var array|int[]
     */
    private $teams;

    /**
     * Exclude constructor.
     * @param array|int[] $teams
     */
    public function __construct(array $teams)
    {
        $this->teams = $teams;
    }

    /**
     * @return array|int[]
     */
    public function getTeams(): array
    {
        return $this->teams;
    }
}
