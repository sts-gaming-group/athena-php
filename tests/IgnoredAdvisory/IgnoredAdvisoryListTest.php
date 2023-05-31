<?php

declare(strict_types=1);

namespace Athena\Tests\IgnoredAdvisory;

use Athena\Advisory\Advisory;
use Athena\IgnoredAdvisory\IgnoredAdvisory;
use Athena\IgnoredAdvisory\IgnoredAdvisoryList;
use PHPUnit\Framework\TestCase;

class IgnoredAdvisoryListTest extends TestCase
{
    public function testContains()
    {
        $ignoredAdvisory1 = new IgnoredAdvisory(
            'id1',
            new \DateTime(),
            'notes'
        );

        $ignoredAdvisory2 = new IgnoredAdvisory(
            'id2',
            new \DateTime(),
            'notes'
        );

        $ignoredAdvisoryList = new IgnoredAdvisoryList(
            [$ignoredAdvisory1, $ignoredAdvisory2]
        );

        $advisory1 = new Advisory(
            'id1',
            'packageName',
            'affectedVersions',
            'title',
            'cve',
            'link',
            new \DateTime(),
            [],
        );

        $advisory2 = new Advisory(
            'id3',
            'packageName',
            'affectedVersions',
            'title',
            'cve',
            'link',
            new \DateTime(),
            [],
        );

        $this->assertInstanceOf(IgnoredAdvisory::class, $ignoredAdvisoryList->contains($advisory1));
        $this->assertFalse($ignoredAdvisoryList->contains($advisory2));
    }
}