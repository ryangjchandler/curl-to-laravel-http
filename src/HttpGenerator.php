<?php

namespace RyanChandler\CurlToLaravelHttp;

use PhpParser\BuilderFactory;
use PhpParser\PrettyPrinter\Standard;

class HttpGenerator
{
    protected string $code = 'Http';

    protected bool $hasCalledStaticMethod = false;

    public function __construct(
        protected CurlCommand $curlCommand,
    ) {}

    public function generate(): string
    {
        if ($this->curlCommand->userAgent !== null) {
            $this->append("withUserAgent('{$this->curlCommand->userAgent}')");
        }

        if ($this->curlCommand->data) {
            $this->append(sprintf("%s('%s', %s)", strtolower($this->curlCommand->method), $this->curlCommand->url, str_replace(['array (', ')'], ['[', ']'], var_export($this->curlCommand->data, return: true))));
        } else {
            $this->append(sprintf("%s('%s')", strtolower($this->curlCommand->method), $this->curlCommand->url));
        }

        return $this->code . ';';
    }

    protected function append(string $code): void
    {
        if ($this->hasCalledStaticMethod) {
            $this->code .= "\n    ->";
        } else {
            $this->code .= '::';
        }

        $this->hasCalledStaticMethod = true;
        $this->code .= $code;
    }
}