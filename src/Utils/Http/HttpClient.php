<?php

declare(strict_types=1);

namespace Athena\Utils\Http;

class HttpClient implements HttpClientInterface
{
    public function get(string $url): string
    {
        return file_get_contents($url);
    }
}
