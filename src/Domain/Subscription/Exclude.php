<?php

namespace Statistico\Auth\Domain\Subscription;

class Exclude
{
    /**
     * @var array|int[]
     */
    private $teams;

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
