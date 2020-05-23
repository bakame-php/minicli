<?php

declare(strict_types=1);

namespace Minicli\Command;

class CommandCall
{
    /** @var string|null */
    protected $command;

    /** @var string */
    protected $subcommand;

    /** @var array */
    protected $args = [];

    /** @var array  */
    protected $raw_args = [];

    /** @var array */
    protected $params = [];

    /** @var array */
    protected $flags = [];

    /**
     * CommandCall constructor.
     * @param array $argv
     */
    public function __construct(array $argv)
    {
        $this->raw_args = $argv;
        $this->parseCommand($argv);

        $this->command = $this->args[1] ?? null;

        $this->subcommand = $this->args[2] ?? 'default';
    }

    protected function parseCommand(iterable $argv): void
    {
        foreach ($argv as $arg) {

            $pair = explode('=', $arg);

            if (count($pair) == 2) {
                $this->params[$pair[0]] = $pair[1];
                continue;
            }

            if (substr($arg, 0, 2) == '--') {
                $this->flags[] = $arg;
                continue;
            }

            $this->args[] = $arg;
        }
    }

    public function hasParam(string $param): bool
    {
        return isset($this->params[$param]);
    }

    public function hasFlag(string $flag): bool
    {
        return in_array($flag, $this->flags, true);
    }

    public function getParam(string $param): ?string
    {
        return $this->params[$param] ?? null;
    }

    public function getParams(): array
    {
        return $this->params;
    }

    public function getRawArgs(): array
    {
        return $this->raw_args;
    }

    public function getArgs(): array
    {
        return $this->args;
    }

    public function getFlags(): array
    {
        return $this->flags;
    }

    public function getCommand(): ?string
    {
        return $this->command;
    }

    public function getSubCommand(): string
    {
        return $this->subcommand;
    }
}
