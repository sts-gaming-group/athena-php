<?php

declare(strict_types=1);

namespace Athena\IgnoredAdvisory;

class IgnoredAdvisory
{
    public function __construct(
        public readonly string $advisoryId,
        public readonly \DateTimeInterface $expiry,
        public readonly string $notes
    ) {
    }
}
