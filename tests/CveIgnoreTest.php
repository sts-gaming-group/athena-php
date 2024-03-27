<?php

declare(strict_types=1);

namespace Athena\Tests;

use Athena\CveIgnore;
use Athena\Advisory\AdvisoryList;
use Athena\Exception\CveignoreFileDontExistException;
use Athena\Filemanager;
use Athena\IgnoredAdvisory\IgnoredAdvisoriesDeserializer;
use Athena\IgnoredAdvisory\IgnoredAdvisoryList;
use PHPUnit\Framework\TestCase;

class CveIgnoreTest extends TestCase
{
    private $fileManagerMock;
    private $cveIgnore;
    private $advisoryListMock;

    protected function setUp(): void
    {
        $this->fileManagerMock = $this->createMock(Filemanager::class);

        $this->cveIgnore = new CveIgnore($this->fileManagerMock);

        $this->advisoryListMock = $this->createMock(AdvisoryList::class);
    }

    public function testDefaultFileExistTrue()
    {
        $this->fileManagerMock->method('exist')->willReturn(true);

        $this->assertTrue($this->cveIgnore->defaultFileExist());
    }

    public function testDefaultFileExistFalse()
    {
        $this->fileManagerMock->method('exist')->willReturn(false);

        $this->assertFalse($this->cveIgnore->defaultFileExist());
    }

    public function testIgnoreAdvisoriesWithNoFile()
    {
        $this->expectException(CveignoreFileDontExistException::class);

        $this->fileManagerMock->method('exist')->willReturn(false);

        $this->cveIgnore->ignoreAdvisories($this->advisoryListMock);
    }


}