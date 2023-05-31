<?php

declare(strict_types=1);

namespace Athena\Tests\ObjectMother;

use Athena\Advisory\Advisory;
use Athena\Advisory\Source;

class AdvisoryMother
{
    public static function create(): Advisory
    {
        return new Advisory(
            'id'.rand(),
            'packageName',
            '1.0.0',
            'Fake Vulnerability',
            'CVE-2023-12345',
            'https://example.com/fake-advisory',
            new \DateTime('2023-04-17T16:00:00+00:00'),
            [
                new Source('FakeSource1', 'FAKE-wxmh-65f7-jcvw'),
                new Source('FakeSource2', 'fake-package/xyz/CVE-2023-12345.yaml'),
            ]
        );
    }

    public static function createIgnored(\DateTimeInterface $expiry): Advisory
    {
        $advisory = self::create();
        $advisory->ignore($expiry, 'Test notes');

        return $advisory;
    }
}