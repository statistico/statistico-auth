<?php

namespace Statistico\Auth\Framework\Time;

trait ParsesTime
{
    public function fromUnixTimestamp(int $time): \DateTimeImmutable
    {
        $parsed = \DateTimeImmutable::createFromFormat('U', $time);

        if ($parsed === false) {
            throw new \RuntimeException("Date {$time} is not a valid unix timestamp");
        }

        return $parsed;
    }
}
