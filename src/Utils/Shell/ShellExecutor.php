<?php

declare(strict_types=1);

namespace Athena\Utils\Shell;

class ShellExecutor implements ShellExecutorInterface
{
    public function execute(string $command): string
    {
        return shell_exec($command);
    }
}
