<?php

declare(strict_types=1);

namespace Athena;

use Athena\Advisory\AdvisoriesFactory;
use Athena\Advisory\AdvisoryList;
use Athena\VulnerabilityMetrics\VulnerabilityMetricsApiInterface;
use Composer\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

class Auditor
{
    public function __construct(
        private readonly VulnerabilityMetricsApiInterface $vulnerabilityMetricsApi,
    ) {
    }

    public function processAudit(): AdvisoryList
    {
        $application = new Application();
        $advisoriesFactory = new AdvisoriesFactory($this->vulnerabilityMetricsApi);

        $input = new ArrayInput(['command' => 'audit', '--format' => 'json', '--locked' => true]);
        $output = new BufferedOutput();

        $application->setAutoExit(false);
        $application->run($input, $output);

        return $advisoriesFactory->create($output->fetch());
    }
}
