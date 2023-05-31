<?php

declare(strict_types=1);

namespace Athena;

use Symfony\Component\Console\Command\Command;

class Runner
{
    public function __construct(
        private readonly Writer $writer,
        private readonly ComposerExecutor $composerExecutor,
        private readonly CveIgnore $cveIgnore
    ) {
    }

    public function runAthena(): int
    {
        $this->composerExecutor->validateVersion();
        $advisoryList = $this->composerExecutor->processAudit();
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