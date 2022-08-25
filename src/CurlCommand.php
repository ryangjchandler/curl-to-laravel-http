<?php

namespace RyanChandler\CurlToLaravelHttp;

final class CurlCommand
{
    public ?string $url;

    public string $method = 'GET';

    public ?string $userAgent = null;

    public bool $followRedirects = false;

    public array $headers = [];

    public ?string $username = null;

    public ?string $password = null;

    public array $data = [];
}