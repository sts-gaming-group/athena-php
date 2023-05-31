<?php

declare(strict_types=1);

namespace Athena;

class Filemanager
{
    public function exist(string $file): bool
    {
        return file_exists($file);
    }

    public function read(string $file): string {
        return file_get_contents($file);
    }
}