<?php

declare(strict_types=1);

namespace Athena;

use Symfony\Component\Console\Command\Command;

class Runner
{
    public function __construct(
        private readonly Writer $writer,
        private readonly Auditor $auditor,
        private readonly CveIgnore $cveIgnore
    ) {
    }

    public function runAthena(): int
    {
        $advisoryList = $this->auditor->processAudit();
        if (false === $advisoryList->hasAdvisories()) {
            $this->writer->writeSuccess('No security vulnerability advisories found');

            return Command::SUCCESS;
        }

        if ($this->cveIgnore->defaultFileExist()) {
            $this->cveIgnore->ignoreAdvisories($advisoryList);
        }

        $this->writer->writeAdvisories($advisoryList);

        if ($advisoryList->hasNotIgnoredAdvisories()) {
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
