<?php

use RyanChandler\CurlToLaravelHttp\HttpGenerator;

it('can generate a simple request', function () {
    http('https://google.co.uk')
        ->toEqual(<<<'http'
        Http::get('https://google.co.uk');
        http);
});

it('can generate a request with a user agent', function () {
    http('-A MyUserAgent')
        ->toContain(<<<'http'
        withUserAgent('MyUserAgent')
        http);
});

it('can send data with a request', function () {
    http('-d foo https://google.co.uk')
        ->toContain(<<<'http'
        get('https://google.co.uk', [
          'foo' => '',
        ])
        http);
});