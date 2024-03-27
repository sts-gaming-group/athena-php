<?php

declare(strict_types=1);

namespace Athena\Exception;

class ComposerVersionTooLowException extends \Exception
{
    public function __construct(
        $message = "Composer version is lower than 2.4.",
        $code = 0,
        \Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
