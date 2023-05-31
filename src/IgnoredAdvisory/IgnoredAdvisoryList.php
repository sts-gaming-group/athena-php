<?php

declare(strict_types=1);

namespace Athena\IgnoredAdvisory;

use Athena\Advisory\Advisory;

class IgnoredAdvisoryList
{
    /**
     * @param IgnoredAdvisory[] $ignoredAdvisories
     */
    public function __construct(public readonly array $ignoredAdvisories)
    {
    }

    public function contains(Advisory $advisory): IgnoredAdvisory|false
    {
        foreach ($this->ignoredAdvisories as $ignoredAdvisory) {
            if ($ignoredAdvisory->advisoryId === $advisory->advisoryId) {
                return $ignoredAdvisory;
            }
        }

        return false;
    }
}