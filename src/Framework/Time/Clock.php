<?php

namespace Statistico\Auth\Framework\Time;

use Cake\Chronos\Chronos;

interface Clock
{
    /**
     * @return Chronos
     */
    public function getCurrentDatetime();

    /**
     * Alias of getCurrentDatetime()
     *
     * @return Chronos
     */
    public function now();

    /**
     * Get the current UNIX timestamp
     *
     * @return float
     */
    public function getCurrentUnixtime(): float;
}
