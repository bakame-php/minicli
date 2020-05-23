<?php

declare(strict_types=1);

namespace Minicli\Command;

use Minicli\App;
use Minicli\Exception\CommandNotFoundException;
use Minicli\ServiceInterface;

class CommandRegistry implements ServiceInterface
{
    /** @var string */
    protected $commands_path;

    /** @var array */
    protected $namespaces = [];

    /** @var array */
    protected $default_registry = [];

    public function __construct(string $commands_path)
    {
        $this->commands_path = $commands_path;
    }

    public function load(App $app): void
    {
        $this->autoloadNamespaces();
    }

    public function autoloadNamespaces(): void
    {
        foreach (glob($this->getCommandsPath() . '/*', GLOB_ONLYDIR) as $namespace_path) {
            $this->registerNamespace(basename($namespace_path));
        }
    }

    public function registerNamespace(string $command_namespace): void
    {
        $namespace = new CommandNamespace($command_namespace);
        $namespace->loadControllers($this->getCommandsPath());
        $this->namespaces[strtolower($command_namespace)] = $namespace;
    }

    public function getNamespace(string $command): ?CommandNamespace
    {
        return $this->namespaces[$command] ?? null;
    }

    public function getCommandsPath(): string
    {
        return $this->commands_path;
    }

    /**
     * Registers an anonymous function as single command.
     */
    public function registerCommand(string $name, callable $callable): void
    {
        $this->default_registry[$name] = $callable;
    }

    public function getCommand(string $command): ?callable
    {
        return $this->default_registry[$command] ?? null;
    }

    public function getCallableController(string $command, string $subcommand = "default"): ?CommandController
    {
        $namespace = $this->getNamespace($command);

        if ($namespace !== null) {
            return $namespace->getController($subcommand);
        }

        return null;
    }

    /**
     * @throws CommandNotFoundException
     */
    public function getCallable(string $command): ?callable
    {
        $single_command = $this->getCommand($command);
        if ($single_command === null) {
            throw new CommandNotFoundException(sprintf("Command \"%s\" not found.", $command));
        }

        return $single_command;
    }

    public function getCommandMap(): array
    {
        $map = [];

        foreach ($this->default_registry as $command => $callback) {
            $map[$command] = $callback;
        }

        /**
         * @var  string $command
         * @var  CommandNamespace $namespace
         */
        foreach ($this->namespaces as $command => $namespace) {
            $controllers = $namespace->getControllers();
            $subs = [];
            foreach ($controllers as $subcommand => $controller) {
                $subs[] = $subcommand;
            }

            $map[$command] = $subs;
        }

        return $map;
    }
}
