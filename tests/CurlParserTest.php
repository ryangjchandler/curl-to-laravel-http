<?php

it('can parse a simple curl request', function () {
    curl('https://google.com')
        ->toHaveProperty('url', 'https://google.com');
});

it('can parse an HTTP method', function () {
    curl('-X POST https://google.com')
        ->toHaveProperty('method', 'POST')
        ->toHaveProperty('url', 'https://google.com');

    curl('--request POST https://google.com')
        ->toHaveProperty('method', 'POST')
        ->toHaveProperty('url', 'https://google.com');
});

it('can parse the multipart form option', function () {
    curl('-F')
        ->toHaveProperty('multipart', true);

    curl('--form')
        ->toHaveProperty('multipart', true);
});

it('can parse the user agent option', function () {
    curl('-A MyUserAgent')
        ->toHaveProperty('userAgent', 'MyUserAgent');

    curl('--user-agent MyUserAgent')
        ->toHaveProperty('userAgent', 'MyUserAgent');
});

it('can parse the follow links option', function () {
    curl('-L')
        ->toHaveProperty('followRedirects', true);

    curl('--location')
        ->toHaveProperty('followRedirects', true);
});

it('can parse header options', function () {
    curl('-H "X-Foo: bar"')
        ->toHaveProperty('headers', [
            'X-Foo' => 'bar',
        ]);
    
    curl('--header "X-Foo: bar"')
        ->toHaveProperty('headers', [
            'X-Foo' => 'bar',
        ]);

    curl('-H "X-Foo: bar" -H "X-Baz: car"')
        ->toHaveProperty('headers', [
            'X-Foo' => 'bar',
            'X-Baz' => 'car'
        ]);
});

it('can parse username', function () {
    curl('-u foo')
        ->toHaveProperty('username', 'foo');
});

it('can parse username and password', function () {
    curl('-u foo:bar')
        ->toHaveProperty('username', 'foo')
        ->toHaveProperty('password', 'bar');
});

it('can parse data', function () {
    curl('-d mydata')
        ->toHaveProperty('data', [
            'mydata' => '',
        ]);

    curl('-d mydata=foo')
        ->toHaveProperty('data', [
            'mydata' => 'foo',
        ]);

    curl('-d mydata="foo"')
        ->toHaveProperty('data', [
            'mydata' => 'foo',
        ]);

    curl('-d mydata="foo"')
        ->toHaveProperty('data', [
            'mydata' => 'foo',
        ]);

    curl('-d mydata[]=foo')
        ->toHaveProperty('data', [
            'mydata' => ['foo'],
        ]);

    curl('-d mydata[]=foo -d mydata[]=bar')
        ->toHaveProperty('data', [
            'mydata' => ['foo', 'bar'],
        ]);

    curl('-d mydata[]="foo" -d mydata[]="bar"')
        ->toHaveProperty('data', [
            'mydata' => ['foo', 'bar'],
        ]);
});