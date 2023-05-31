<?php
declare(strict_types=1);

namespace Athena\Advisory;

class AdvisoriesDeserializer
{
    public function deserialize(string $auditOutput): AdvisoryList
    {
        $data = json_decode($auditOutput, true);

        $advisories = [];
        foreach ($data['advisories'] as $packageName => $advisoriesData) {

            foreach ($advisoriesData as $advisoryData) {

                $sources = [];
                foreach ($advisoryData['sources'] as $sourceData) {
                    $source = new Source(
                        $sourceData['name'],
                        $sourceData['remoteId'],
                    );

                    $sources[] = $source;
                }


                $advisory = new Advisory(
                    $advisoryData['advisoryId'],
                    $advisoryData['packageName'],
                    $advisoryData['affectedVersions'],
                    $advisoryData['title'],
                    $advisoryData['cve'],
                    $advisoryData['link'],
                    new \DateTime($advisoryData['reportedAt']),
                    $sources
                );

                $advisories[] = $advisory;
            }

        }

        return new AdvisoryList($advisories);
    }
}