<?php

declare(strict_types=1);

namespace Athena;

use Athena\Advisory\Advisory;
use Athena\Advisory\AdvisoryList;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class Writer
{
    private readonly SymfonyStyle $io;

    public function __construct(InputInterface $input, OutputInterface $output)
    {
        $this->io = new SymfonyStyle($input, $output);
    }

    public function writeSuccess(string $text): void
    {
        $this->io->success($text);
    }

    public function writeAdvisories(AdvisoryList $advisoryList): void
    {
        if ($advisoryList->hasNotIgnoredAdvisories()) {
            $this->writeNotIgnoredAdvisories($advisoryList->getNotIgnored());
        }

        if ($advisoryList->hasIgnoredAdvisories()) {
            $this->writeIgnoredAdvisories($advisoryList->getIgnored());
        }
    }

    /**
     * @param array<Advisory> $advisories
     */
    private function writeNotIgnoredAdvisories(array $advisories): void
    {
        $this->io->error('Vulnerability advisories found:');

        foreach ($advisories as $advisory) {
            $this->io->horizontalTable(
                [
                    'Id',
                    'Package',
                    'CVE',
                    'Base score',
                    'Base Severity',
                    'Title',
                    'URL',
                    'Affected versions',
                    'Reported at',
                ],
                [
                    [
                        $advisory->advisoryId,
                        $advisory->packageName,
                        $advisory->cve,
                        $advisory->metrics->baseScore ?? "",
                        $advisory->metrics->baseSeverity ?? "",
                        $advisory->title,
                        $advisory->link,
                        $advisory->affectedVersions,
                        $advisory->reportedAt->format('Y-m-d H:i:s'),
                    ],
                ]
            );
        }
    }

    /**
     * @param array<Advisory> $advisories
     */
    private function writeIgnoredAdvisories(array $advisories): void
    {
        $this->io->note('Ignored Advisories:');

        foreach ($advisories as $advisory) {
            $this->io->horizontalTable(
                [
                    'Id',
                    'Package',
                    'CVE',
                    'Base score',
                    'Base severity',
                    'Title',
                    'URL',
                    'Affected versions',
                    'Reported at',
                    'Expiry at',
                    'Notes',
                ],
                [
                    [
                        $advisory->advisoryId,
                        $advisory->packageName,
                        $advisory->cve,
                        $advisory->metrics->baseScore ?? "",
                        $advisory->metrics->baseSeverity ?? "",
                        $advisory->title,
                        $advisory->link,
                        $advisory->affectedVersions,
                        $advisory->reportedAt->format('Y-m-d H:i:s'),
                        $advisory->ignoreDetails->expiryAt->format('Y-m-d H:i:s'),
                        $advisory->ignoreDetails->notes,
                    ],
                ]
            );
        }
    }
}
