<?php

namespace Statistico\Auth\Domain\Subscription;

class Betting
{
    /**
     * @var array|BetType[]
     */
    private $types;
    /**
     * @var Exclude
     */
    private $exclude;

    public function __construct(array $types, Exclude $exclude)
    {
        $this->types = $types;
        $this->exclude = $exclude;
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
}
