<?php

declare(strict_types=1);

namespace Athena\Exception;

class CveignoreFileDontExistException extends \Exception
{
    public function __construct($message = ".cveignore file dont exist", $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
