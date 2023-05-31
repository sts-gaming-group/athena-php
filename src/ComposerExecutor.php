<?php

declare(strict_types=1);

namespace Athena;

use Athena\Advisory\AdvisoriesDeserializer;
use Athena\Advisory\AdvisoryList;
use Athena\Exception\ComposerNotInstalledException;
use Athena\Exception\ComposerVersionTooLowException;

class ComposerExecutor
{
    public function validateVersion(): void
    {
        $output = shell_exec('composer --version');
        $pattern = '/(\d+\.\d+\.\d+)/'; // Regular expression pattern to extract the version number
        preg_match($pattern, $output, $matches);

        if (isset($matches[1])) {
            $version = $matches[1];
            if (version_compare($version, '2.4', '<')) {
                throw new ComposerVersionTooLowException();
            }
        } else {
            throw new ComposerNotInstalledException();
        }
    }

    public function processAudit(): AdvisoryList
    {
        $auditOutput = '{
    "advisories": {
        "guzzlehttp/psr7": [
            {
                "advisoryId": "PKSA-hn62-zkx4-1y5q",
                "packageName": "guzzlehttp/psr7",
                "affectedVersions": ">=2,<2.4.5|<1.9.1",
                "title": "Improper header validation",
                "cve": "CVE-2023-29197",
                "link": "https://github.com/guzzle/psr7/security/advisories/GHSA-wxmh-65f7-jcvw",
                "reportedAt": "2023-04-17T16:00:00+00:00",
                "sources": [
                    {
                        "name": "GitHub",
                        "remoteId": "GHSA-wxmh-65f7-jcvw"
                    },
                    {
                        "name": "FriendsOfPHP/security-advisories",
                        "remoteId": "guzzlehttp/psr7/CVE-2023-29197.yaml"
                    }
                ]
            }
        ],
        "nyholm/psr7": [
            {
                "advisoryId": "PKSA-8ds9-sp96-ghmb",
                "packageName": "nyholm/psr7",
                "affectedVersions": "<1.6.1",
                "title": "Improper Input Validation in headers",
                "cve": null,
                "link": "https://github.com/advisories/GHSA-wjfc-pgfp-pv9c",
                "reportedAt": "2023-04-17T00:00:00+00:00",
                "sources": [
                    {
                        "name": "GitHub",
                        "remoteId": "GHSA-wjfc-pgfp-pv9c"
                    },
                    {
                        "name": "FriendsOfPHP/security-advisories",
                        "remoteId": "nyholm/psr7/2023-04-17.yaml"
                    }
                ]
            }
        ]
    }
}';
//        $auditOutput = shell_exec('composer audit --format=json');
        $deserializer = new AdvisoriesDeserializer();

        return $deserializer->deserialize($auditOutput);
    }
}