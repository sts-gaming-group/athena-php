<?php

declare(strict_types=1);

namespace Athena\Tests;

use Athena\ComposerExecutor;
use Athena\Exception\ComposerNotInstalledException;
use Athena\Exception\ComposerVersionTooLowException;
use Athena\Utils\Shell\ShellExecutorInterface;
use Athena\VulnerabilityMetrics\VulnerabilityMetricsApiInterface;
use PHPUnit\Framework\TestCase;

class ComposerExecutorTest extends TestCase
{
    private readonly ShellExecutorInterface $shellExecutor;
    private readonly VulnerabilityMetricsApiInterface $vulnerabilityMetricsApi;
    private readonly ComposerExecutor $composerExecutor;

    public function setUp(): void
    {
        $this->shellExecutor = $this->createMock(ShellExecutorInterface::class);
        $this->vulnerabilityMetricsApi = $this->createMock(VulnerabilityMetricsApiInterface::class);

        $this->composerExecutor = new ComposerExecutor($this->shellExecutor, $this->vulnerabilityMetricsApi);
    }

    public function testValidateVersionEnough(): void
    {
        $this->shellExecutor->method('execute')
            ->willReturn('Composer version 2.4.0 2021-07-07 13:57:34');

        $this->assertTrue($this->composerExecutor->validateVersion());
    }

    public function testValidateVersionAbove(): void
    {
        $this->shellExecutor->method('execute')
            ->willReturn('Composer version 2.5.0 2021-07-07 13:57:34');

        $this->assertTrue($this->composerExecutor->validateVersion());
    }


    public function testValidateVersionToLow(): void
    {
        $this->shellExecutor->method('execute')
            ->willReturn('Composer version 2.3.9 2021-07-07 13:57:34');

        $this->expectException(ComposerVersionTooLowException::class);

        $this->composerExecutor->validateVersion();
    }

    public function testValidateVersionComposerNotInstalled()
    {
        $this->shellExecutor->method('execute')
            ->willReturn('command not found: composer');

        $this->expectException(ComposerNotInstalledException::class);

        $this->composerExecutor->validateVersion();
    }
}