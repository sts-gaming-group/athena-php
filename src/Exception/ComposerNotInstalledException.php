<?php

declare(strict_types=1);

namespace Athena\Exception;

class ComposerNotInstalledException extends \Exception
{
    public function __construct($message = "Composer is not installed", $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
