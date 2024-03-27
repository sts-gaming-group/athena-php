<?php

declare(strict_types=1);

namespace Athena\Advisory;

use Athena\VulnerabilityMetrics\VulnerabilityMetrics;

class Advisory
{
    public ?IgnoreDetails $ignoreDetails = null;
    public ?VulnerabilityMetrics $metrics = null;

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

    public function setMetrics(?VulnerabilityMetrics $metrics): void
    {
        $this->metrics = $metrics;
    }

    public function ignore(\DateTimeInterface $expiry, string $notes): void
    {
        $this->ignoreDetails = new IgnoreDetails($expiry, $notes);
    }

    public function isIgnored(): bool
    {
        return $this->ignoreDetails instanceof IgnoreDetails && $this->ignoreDetails->isExpired();
    }

    public function hasCve(): bool
    {
        return $this->cve !== null;
    }
}
