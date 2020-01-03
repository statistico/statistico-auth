<?php

namespace Statistico\Auth\Framework\Entity;

class Timestamps
{
    /**
     * @var \DateTimeImmutable
     */
    private $created;
    /**
     * @var \DateTimeImmutable
     */
    private $updated;

    public function __construct(\DateTimeImmutable $created, \DateTimeImmutable $updated)
    {
        $this->created = $created;
        $this->updated = $updated;
    }

    public function getCreated(): \DateTimeImmutable
    {
        return $this->created;
    }

    public function getUpdated(): \DateTimeImmutable
    {
        return $this->updated;
    }
}
