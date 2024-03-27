<?php

declare(strict_types=1);

namespace Athena;

use Athena\Advisory\AdvisoriesFactory;
use Athena\Advisory\AdvisoryList;
use Athena\Exception\ComposerNotInstalledException;
use Athena\Exception\ComposerVersionTooLowException;
use Athena\Utils\Shell\ShellExecutorInterface;
use Athena\VulnerabilityMetrics\VulnerabilityMetricsApiInterface;

class ComposerExecutor
{
    public function __construct(
        private readonly ShellExecutorInterface $shellExecutor,
        private readonly VulnerabilityMetricsApiInterface $vulnerabilityMetricsApi
    ) {
    }

    public function validateVersion(): bool
    {
        $output = $this->shellExecutor->execute('composer --version');
        $pattern = '/(\d+\.\d+\.\d+)/'; // Regular expression pattern to extract the version number
        preg_match($pattern, $output, $matches);

        if (isset($matches[1])) {
            $version = $matches[1];
            if (version_compare($version, '2.4', '<')) {
                throw new ComposerVersionTooLowException();
            }
        } else {
            throw new ComposerNotInstalledException();
        }

        return true;
    }

    public function processAudit(): AdvisoryList
    {
        $auditOutput = $this->shellExecutor->execute('composer audit --format=json --locked');
        $advisoriesFactory = new AdvisoriesFactory($this->vulnerabilityMetricsApi);

        return $advisoriesFactory->create($auditOutput);
    }
}
