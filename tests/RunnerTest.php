<?php

declare(strict_types=1);

namespace Athena\Tests;

use Athena\Advisory\AdvisoryList;
use Athena\ComposerExecutor;
use Athena\CveIgnore;
use Athena\Runner;
use Athena\Tests\ObjectMother\AdvisoryMother;
use Athena\Writer;
use PHPUnit\Framework\TestCase;

class RunnerTest extends TestCase
{
    private readonly ComposerExecutor $composerExecutor;
    private readonly CveIgnore $cveIgnore;
    private readonly Runner $runner;

    protected function setUp(): void
    {
        $writer = $this->createMock(Writer::class);
        $this->composerExecutor = $this->createMock(ComposerExecutor::class);
        $this->cveIgnore = $this->createMock(CveIgnore::class);
        $this->runner = new Runner($writer, $this->composerExecutor, $this->cveIgnore);
    }

    public function testRunNoAdvisories(): void
    {
        $this->composerExecutor
            ->expects($this->once())
            ->method('validateVersion');
        $this->composerExecutor
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
        $this->composerExecutor
            ->expects($this->once())
            ->method('validateVersion');
        $this->composerExecutor
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
        $this->composerExecutor
            ->expects($this->once())
            ->method('validateVersion');
        $this->composerExecutor
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
        $this->composerExecutor
            ->expects($this->once())
            ->method('validateVersion');
        $this->composerExecutor
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
        $this->composerExecutor
            ->expects($this->once())
            ->method('validateVersion');
        $this->composerExecutor
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