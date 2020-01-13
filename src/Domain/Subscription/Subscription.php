<?php

namespace Statistico\Auth\Domain\Subscription;

class Subscription
{
    /**
     * @var Data
     */
    private $data;
    /**
     * @var Betting
     */
    private $betting;

    public function __construct(Data $data, Betting $betting)
    {
        $this->data = $data;
        $this->betting = $betting;
    }

    public function getData(): Data
    {
        return $this->data;
    }
    
    public function getBetting(): Betting
    {
        return $this->betting;
    }
}
