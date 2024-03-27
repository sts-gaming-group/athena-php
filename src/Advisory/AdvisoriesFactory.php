<?php

declare(strict_types=1);

namespace Athena\Advisory;

use Athena\VulnerabilityMetrics\VulnerabilityMetricsApiInterface;

class AdvisoriesFactory
{
    public function __construct(private readonly VulnerabilityMetricsApiInterface $metricsApi)
    {
    }
    public function create(string $auditOutput): AdvisoryList
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

                if ($advisory->hasCve()) {
                    $metrics = $this->metricsApi->get($advisory->cve);
                    $advisory->setMetrics($metrics);
                }

                $advisories[] = $advisory;
            }
        }

        return new AdvisoryList($advisories);
    }
}
