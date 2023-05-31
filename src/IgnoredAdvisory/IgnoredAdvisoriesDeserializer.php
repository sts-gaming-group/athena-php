<?php

declare(strict_types=1);

namespace Athena\IgnoredAdvisory;

class IgnoredAdvisoriesDeserializer
{
    public function deserialize(string $cveIgnoreContent): IgnoredAdvisoryList
    {
        $advisoryArray = json_decode($cveIgnoreContent, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('JSON deserialization error: '.json_last_error_msg());
        }

        $ignoredAdvisories = [];

        foreach ($advisoryArray as $advisoryId => $advisoryData) {
            $expiry = new \DateTimeImmutable('@'.$advisoryData['expiry']);
            $ignoredAdvisories[] = new IgnoredAdvisory($advisoryId, $expiry, $advisoryData['notes']);
        }

        return new IgnoredAdvisoryList($ignoredAdvisories);
    }
}