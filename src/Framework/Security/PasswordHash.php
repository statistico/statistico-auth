<?php

namespace Statistico\Auth\Framework\Security;

final class PasswordHash
{
    /**
     * @var int
     */
    private static $algo = PASSWORD_BCRYPT;
    /**
     * @var array|array[]
     */
    private static $algoOptions = [];
    /**
     * @var string
     */
    private $hash;

    public function __construct(string $hash)
    {
        if (!$hash) {
            throw new \InvalidArgumentException('Password hash cannot be empty');
        }

        $this->hash = $hash;
    }

    /**
     * @return bool
     */
    public function needsRehash(): bool
    {
        return password_needs_rehash($this->__toString(), static::$algo, static::$algoOptions);
    }

    /**
     * Verify a raw password against this password hash
     *
     * @param string $rawPassword
     * @return bool
     */
    public function verify(string $rawPassword)
    {
        return password_verify($rawPassword, $this->__toString());
    }

    public static function createFromRaw(string $rawPassword): PasswordHash
    {
        if (empty($rawPassword)) {
            throw new \InvalidArgumentException('Password cannot be empty');
        }

        $hash = password_hash($rawPassword, static::$algo, static::$algoOptions);

        if ($hash === false) {
            throw new \RuntimeException("Error creating password hash");
        }

        return new static($hash);
    }

    public function __toString()
    {
        return $this->hash;
    }
}
