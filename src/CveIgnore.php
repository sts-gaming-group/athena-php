<?php

declare(strict_types=1);

namespace Athena;

use Athena\Advisory\AdvisoryList;
use Athena\Exception\CveignoreFileDontExistException;
use Athena\IgnoredAdvisory\IgnoredAdvisoriesDeserializer;
use Athena\IgnoredAdvisory\IgnoredAdvisory;
use Athena\IgnoredAdvisory\IgnoredAdvisoryList;

class CveIgnore
{
    private const DEFAULT_FILENAME = '.cveignore';

    public function __construct(private readonly Filemanager $filemanager)
    {
    }

    public function defaultFileExist(): bool
    {
        return $this->filemanager->exist(sprintf('%s/%s', getcwd(), self::DEFAULT_FILENAME));
    }

    public function ignoreAdvisories(AdvisoryList $advisoryList): void
    {
        $ignoredAdvisoriesList = $this->deserializeIgnoreFile();

        foreach ($advisoryList->advisories as $advisory) {
            $ignoredAdvisory = $ignoredAdvisoriesList->contains($advisory);
            if ($ignoredAdvisory instanceof IgnoredAdvisory) {
                $advisory->ignore($ignoredAdvisory->expiry, $ignoredAdvisory->notes);
            }
        }
    }

    private function deserializeIgnoreFile(): IgnoredAdvisoryList
    {
        if (!$this->defaultFileExist()) {
            throw new CveignoreFileDontExistException();
        }

        $fileContent = $this->filemanager->read(getcwd() . '/.cveignore');
        if (empty($fileContent)) {
            return new IgnoredAdvisoryList([]);
        }
        $deserializer = new IgnoredAdvisoriesDeserializer();

        return $deserializer->deserialize($fileContent);
    }
}
