<?php

declare(strict_types=1);

namespace Athena\Tests;

use Athena\Advisory\AdvisoriesFactory;
use Athena\Advisory\AdvisoryList;
use Athena\Auditor;
use Athena\Exception\ComposerNotInstalledException;
use Athena\Exception\ComposerVersionTooLowException;
use Athena\VulnerabilityMetrics\VulnerabilityMetricsApiInterface;
use Composer\Console\Application;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Output\BufferedOutput;

class AuditorTest extends TestCase
{
    private readonly VulnerabilityMetricsApiInterface $vulnerabilityMetricsApi;
    private readonly Auditor $auditor;

    public function setUp(): void
    {
        $this->vulnerabilityMetricsApi = $this->createMock(VulnerabilityMetricsApiInterface::class);

        $this->auditor = new Auditor($this->vulnerabilityMetricsApi);
    }

    public function testProcessAuditReturnsAdvisoryList(): void
    {
        $advisoryList = $this->auditor->processAudit();

        $this->assertInstanceOf(AdvisoryList::class, $advisoryList);
    }
}