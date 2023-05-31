<?php

declare(strict_types=1);

namespace Athena\Advisory;

class Advisory
{

    public ?IgnoreDetails $ignoreDetails = null;

    public function __construct(
        public readonly string $advisoryId,
        public readonly string $packageName,
        public readonly string $affectedVersions,
        public readonly string $title,
        public readonly ?string $cve,
        public readonly string $link,
        public readonly \DateTimeInterface $reportedAt,
        public readonly array $sources,
    ) {
    }

    public function ignore(\DateTimeInterface $expiry, string $notes): void
    {
        $this->ignoreDetails = new IgnoreDetails($expiry, $notes);
    }

    public function isIgnored(): bool
    {
        return $this->ignoreDetails instanceof IgnoreDetails && $this->ignoreDetails->isExpired();
    }
}