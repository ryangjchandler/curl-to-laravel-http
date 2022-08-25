<?php

namespace RyanChandler\CurlToLaravelHttp;

use Closure;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\StringInput;

class CurlParser
{
    protected ?StringInput $input;

    public function __construct(string $command)
    {
        $this->input = !empty($command) ? new StringInput($command) : null;
    }

    public function parse(): ?CurlCommand
    {
        if ($this->input === null) {
            return null;
        }

        $definition = new InputDefinition([
            new InputArgument('url', InputArgument::REQUIRED),
            new InputOption('request', 'X', InputOption::VALUE_REQUIRED),
            new InputOption('user-agent', 'A', InputOption::VALUE_REQUIRED),
            new InputOption('header', 'H', InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED),
            new InputOption('user', 'u', InputOption::VALUE_REQUIRED),
            new InputOption('data', 'd', InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED),
            new InputOption('location', 'L'),
        ]);

        $this->input->bind($definition);

        $command = new CurlCommand;

        if ($this->input->getOption('request')) {
            $command->method = $this->input->getOption('request');
        }

        if ($this->input->getOption('user-agent')) {
            $command->userAgent = $this->input->getOption('user-agent');
        }

        if ($headers = $this->input->getOption('header')) {
            foreach ($headers as $header) {
                [$name, $value] = Str::of($header)->trim('"')->explode(':')->map(fn ($_) => trim($_))->all();
                $command->headers[$name] = $value;
            }
        }

        if ($user = $this->input->getOption('user')) {
            [$username, $password] = str_contains($user, ':') ? explode(':', $user) : [$user, null];

            $command->username = $username;
            $command->password = $password;
        }

        if ($data = $this->input->getOption('data')) {
            parse_str(implode('&', $data), $parsed);
            
            $command->data = $this->recursiveMap($parsed, fn ($_) => trim($_, '"'));
        }

        $command->followRedirects = $this->input->getOption('location');
        $command->url = $this->input->getArgument('url');

        return $command;
    }

    protected function recursiveMap(array $input, Closure $callback): array
    {
        return collect($input)->map(fn ($item) => is_array($item) ? $this->recursiveMap($item, $callback) : $callback($item))->all();
    }
}