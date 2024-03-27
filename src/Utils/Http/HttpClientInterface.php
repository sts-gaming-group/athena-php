<?php

namespace Athena\Utils\Http;

interface HttpClientInterface
{
    public function get(string $url): string;
}
