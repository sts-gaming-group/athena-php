<?php

declare(strict_types=1);

namespace Athena\Advisory;

class IgnoreDetails
{
    public function __construct(public readonly \DateTimeInterface $expiryAt, public readonly string $notes)
    {
    }

    public function isExpired(): bool
    {
        return $this->expiryAt > new \DateTime();
    }
}