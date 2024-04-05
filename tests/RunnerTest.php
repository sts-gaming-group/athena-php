<?php

declare(strict_types=1);

namespace Athena\Tests;

use Athena\Advisory\AdvisoryList;
use Athena\Auditor;
use Athena\CveIgnore;
use Athena\Runner;
use Athena\Tests\ObjectMother\AdvisoryMother;
use Athena\Writer;
use PHPUnit\Framework\TestCase;

class RunnerTest extends TestCase
{
    private readonly Auditor $auditor;
    private readonly CveIgnore $cveIgnore;
    private readonly Runner $runner;

    protected function setUp(): void
    {
        $writer = $this->createMock(Writer::class);
        $this->auditor = $this->createMock(Auditor::class);
        $this->cveIgnore = $this->createMock(CveIgnore::class);
        $this->runner = new Runner($writer, $this->auditor, $this->cveIgnore);
    }

    public function testRunNoAdvisories(): void
    {
        $this->auditor
            ->expects($this->once())
            ->method('processAudit')
            ->willReturn(
                new AdvisoryList([])
            );

        $status = $this->runner->runAthena();

        $this->assertEquals(0, $status);
    }

    public function testRunWithNotIgnoredAdvisories(): void
    {
        $this->auditor
            ->expects($this->once())
            ->method('processAudit')
            ->willReturn(
                new AdvisoryList([
                        AdvisoryMother::create(),
                    ]
                )
            );
        $this->cveIgnore
            ->expects($this->once())
            ->method('defaultFileExist')
            ->willReturn(true);
        $this->cveIgnore
            ->expects($this->once())
            ->method('ignoreAdvisories');

        $status = $this->runner->runAthena();

        $this->assertEquals(1, $status);
    }

    public function testRunWithIgnoredAndNotIgnoredAdvisories(): void
    {
        $this->auditor
            ->expects($this->once())
            ->method('processAudit')
            ->willReturn(
                new AdvisoryList([
                        AdvisoryMother::create(),
                        AdvisoryMother::createIgnored(new \DateTime('+7 days')),
                    ]
                )
            );
        $this->cveIgnore
            ->expects($this->once())
            ->method('defaultFileExist')
            ->willReturn(true);
        $this->cveIgnore
            ->expects($this->once())
            ->method('ignoreAdvisories');

        $status = $this->runner->runAthena();

        $this->assertEquals(1, $status);
    }

    public function testRunWithIgnoredAdvisories(): void
    {
        $this->auditor
            ->expects($this->once())
            ->method('processAudit')
            ->willReturn(
                new AdvisoryList([
                        AdvisoryMother::createIgnored(new \DateTime('+1 days')),
                        AdvisoryMother::createIgnored(new \DateTime('+7 days')),
                    ]
                )
            );
        $this->cveIgnore
            ->expects($this->once())
            ->method('defaultFileExist')
            ->willReturn(true);
        $this->cveIgnore
            ->expects($this->once())
            ->method('ignoreAdvisories');

        $status = $this->runner->runAthena();

        $this->assertEquals(0, $status);
    }

    public function testRunWithExpiryIgnoredAdvisories(): void
    {
        $this->auditor
            ->expects($this->once())
            ->method('processAudit')
            ->willReturn(
                new AdvisoryList([
                        AdvisoryMother::createIgnored(new \DateTime('-1 second')),
                        AdvisoryMother::createIgnored(new \DateTime('+7 days')),
                    ]
                )
            );
        $this->cveIgnore
            ->expects($this->once())
            ->method('defaultFileExist')
            ->willReturn(true);
        $this->cveIgnore
            ->expects($this->once())
            ->method('ignoreAdvisories');

        $status = $this->runner->runAthena();

        $this->assertEquals(1, $status);
    }
}