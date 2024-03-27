<?php

declare(strict_types=1);

namespace Athena\Advisory;

class Source
{
    public function __construct(public readonly string $name, public readonly string $remoteId)
    {
    }
}
