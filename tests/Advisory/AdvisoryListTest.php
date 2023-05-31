<?php
declare(strict_types=1);

namespace Athena\Tests\Advisory;

use Athena\Advisory\Advisory;
use Athena\Advisory\AdvisoryList;
use Athena\Advisory\Source;
use PHPUnit\Framework\TestCase;

class AdvisoryListTest extends TestCase
{
    public function testHasAdvisories()
    {
        $advisory1 = new Advisory(
            'FAKE-hn62-zkx4-1y5q',
            'fake-package/xyz',
            '>=1,<1.2.3|<1.0.0',
            'Fake Vulnerability',
            'CVE-2023-12345',
            'https://example.com/fake-advisory',
            new \DateTime('2023-04-17T16:00:00+00:00'),
            [
                new Source('FakeSource1', 'FAKE-wxmh-65f7-jcvw'),
                new Source('FakeSource2', 'fake-package/xyz/CVE-2023-12345.yaml')
            ]
        );

        $advisoryList = new AdvisoryList([$advisory1]);

        $this->assertTrue($advisoryList->hasAdvisories());
    }

    public function testNotHasAdvisories()
    {
        $emptyAdvisoryList = new AdvisoryList([]);

        $this->assertFalse($emptyAdvisoryList->hasAdvisories());
    }
}