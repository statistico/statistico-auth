<?php

namespace Statistico\Auth\Framework\Identity;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

trait GeneratesUuid
{
    public function generateUuid(): UuidInterface
    {
        try {
            return Uuid::uuid4();
        } catch (\Exception $e) {
            throw new \RuntimeException("Error generating Uuid: {$e->getMessage()}");
        }
    }
}
