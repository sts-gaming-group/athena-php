<?php
declare(strict_types=1);

namespace Athena\Tests\Advisory;

use Athena\Advisory\AdvisoriesDeserializer;
use Athena\Advisory\Advisory;
use Athena\Advisory\AdvisoryList;
use Athena\Advisory\Source;
use PHPUnit\Framework\TestCase;

class AdvisoriesDeserializerTest extends TestCase
{
    /**
     * @dataProvider advisoriesDataProvider
     */
    public function testDeserialize(string $json, int $expectedCount)
    {
        $deserializer = new AdvisoriesDeserializer();
        $advisoryList = $deserializer->deserialize($json);

        $this->assertInstanceOf(AdvisoryList::class, $advisoryList);
        $this->assertCount($expectedCount, $advisoryList->advisories);

        if ($expectedCount > 0) {
            $firstAdvisory = $advisoryList->advisories[0];
            $this->assertInstanceOf(Advisory::class, $firstAdvisory);

            $this->assertEquals('FAKE-hn62-zkx4-1y5q', $firstAdvisory->advisoryId);
            $this->assertEquals('fake-package/xyz', $firstAdvisory->packageName);
            $this->assertEquals('>=1,<1.2.3|<1.0.0', $firstAdvisory->affectedVersions);
            $this->assertEquals('Fake Vulnerability', $firstAdvisory->title);
            $this->assertEquals('CVE-2023-12345', $firstAdvisory->cve);
            $this->assertEquals('https://example.com/fake-advisory', $firstAdvisory->link);
            $this->assertEquals(new \DateTime('2023-04-17T16:00:00+00:00'), $firstAdvisory->reportedAt);

            $this->assertCount(2, $firstAdvisory->sources);
            $this->assertInstanceOf(Source::class, $firstAdvisory->sources[0]);
            $this->assertEquals('FakeSource1', $firstAdvisory->sources[0]->name);
            $this->assertEquals('FAKE-wxmh-65f7-jcvw', $firstAdvisory->sources[0]->remoteId);
        }
    }

    public static function advisoriesDataProvider()
    {
        $json = '{
            "advisories": {
                "fake-package/xyz": [
                    {
                        "advisoryId": "FAKE-hn62-zkx4-1y5q",
                        "packageName": "fake-package/xyz",
                        "affectedVersions": ">=1,<1.2.3|<1.0.0",
                        "title": "Fake Vulnerability",
                        "cve": "CVE-2023-12345",
                        "link": "https://example.com/fake-advisory",
                        "reportedAt": "2023-04-17T16:00:00+00:00",
                        "sources": [
                            {
                                "name": "FakeSource1",
                                "remoteId": "FAKE-wxmh-65f7-jcvw"
                            },
                            {
                                "name": "FakeSource2",
                                "remoteId": "fake-package/xyz/CVE-2023-12345.yaml"
                            }
                        ]
                    }
                ],
                "another-fake-package/abc": [
                    {
                        "advisoryId": "FAKE-8ds9-sp96-ghmb",
                        "packageName": "another-fake-package/abc",
                        "affectedVersions": "<2.3.4",
                        "title": "Another Fake Vulnerability",
                        "cve": null,
                        "link": "https://example.com/another-fake-advisory",
                        "reportedAt": "2023-04-18T00:00:00+00:00",
                        "sources": [
                            {
                                "name": "AnotherFakeSource1",
                                "remoteId": "FAKE-wjfc-pgfp-pv9c"
                            },
                            {
                                "name": "AnotherFakeSource2",
                                "remoteId": "another-fake-package/abc/2023-04-18.yaml"
                            }
                        ]
                    }
                ]
            }
        }';

        return [
            [$json, 2],
            ['{ "advisories": [] }', 0]
        ];
    }
}