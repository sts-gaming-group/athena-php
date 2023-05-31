<?php
declare(strict_types=1);

namespace Athena\Tests\IgnoredAdvisory;

use Athena\IgnoredAdvisory\IgnoredAdvisoriesDeserializer;
use Athena\IgnoredAdvisory\IgnoredAdvisory;
use Athena\IgnoredAdvisory\IgnoredAdvisoryList;
use PHPUnit\Framework\TestCase;

class IgnoredAdvisoriesDeserializerTest extends TestCase
{
    private readonly IgnoredAdvisoriesDeserializer $deserializer;

    public function setUp(): void
    {
        $this->deserializer = new IgnoredAdvisoriesDeserializer();
    }

    public function testDeserialize(): void
    {
        $json = '{
            "PKSA-8ds9-sp96-ghmb": {
                "expiry": 1704074400,
                "notes": "Test notes"
            },
            "PKSA-8d21-sp56-82h1": {
                "expiry": 1704074400,
                "notes": "Test notes"
            }
        }';

        $advisoryList = $this->deserializer->deserialize($json);

        $this->assertInstanceOf(IgnoredAdvisoryList::class, $advisoryList);
        $this->assertCount(2, $advisoryList->ignoredAdvisories);

        $advisory = $advisoryList->ignoredAdvisories[0];
        $this->assertInstanceOf(IgnoredAdvisory::class, $advisory);
        $this->assertEquals('PKSA-8ds9-sp96-ghmb', $advisory->advisoryId);
        $this->assertEquals('Test notes', $advisory->notes);
        $this->assertEquals(1704074400, $advisory->expiry->getTimestamp());
    }
}