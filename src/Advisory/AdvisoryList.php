<?php

declare(strict_types=1);

namespace Athena\Advisory;

class AdvisoryList
{

    /**
     * @param Advisory[] $advisories
     */
    public function __construct(public readonly array $advisories)
    {
    }

    public function hasAdvisories(): bool
    {
        return count($this->advisories) > 0;
    }

    public function hasNotIgnoredAdvisories(): bool
    {
        return $this->hasAdvisoriesByIgnoreStatus(false);
    }

    public function hasIgnoredAdvisories()
    {
        return $this->hasAdvisoriesByIgnoreStatus(true);
    }


    /**
     * @return Advisory[]
     */
    public function getIgnored(): array
    {
        return $this->getAdvisoriesByIgnoreStatus(true);
    }

    /**
     * @return Advisory[]
     */
    public function getNotIgnored(): array
    {
        return $this->getAdvisoriesByIgnoreStatus(false);
    }

    private function getAdvisoriesByIgnoreStatus(bool $isIgnored): array
    {
        $advisories = [];
        foreach ($this->advisories as $advisory) {
            if ($advisory->isIgnored() === $isIgnored) {
                $advisories[] = $advisory;
            }
        }

        return $advisories;
    }

    private function hasAdvisoriesByIgnoreStatus(bool $isIgnored): bool
    {
        foreach ($this->advisories as $advisory) {
            if ($advisory->isIgnored() == $isIgnored) {
                return true;
            }
        }

        return false;
    }
}