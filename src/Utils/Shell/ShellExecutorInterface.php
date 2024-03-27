<?php

namespace Athena\Utils\Shell;

interface ShellExecutorInterface
{
    public function execute(string $command): string;
}
