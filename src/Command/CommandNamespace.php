<?php

declare(strict_types=1);

namespace Minicli\Command;

class CommandNamespace
{
    /** @var  string */
    protected $name;

    /** @var array  */
    protected $controllers = [];

    /**
     * CommandNamespace constructor.
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Load namespace controllers
     * @return array
     */
    public function loadControllers(string $commands_path): array
    {
        foreach (glob($commands_path . '/' . $this->getName() . '/*Controller.php') as $controller_file) {
            $this->loadCommandMap($controller_file);
        }

        return $this->getControllers();
    }

    /**
     * @return array
     */
    public function getControllers(): array
    {
        return $this->controllers;
    }

    public function getController(string $command_name): ?CommandController
    {
        return isset($this->controllers[$command_name]) ? $this->controllers[$command_name] : null;
    }

    protected function loadCommandMap(string $controller_file): void
    {
        $filename = basename($controller_file);

        $controller_class = str_replace('.php', '', $filename);
        $command_name = strtolower(str_replace('Controller', '', $controller_class));
        $full_class_name = sprintf("%s\\%s", $this->getNamespace($controller_file), $controller_class);

        /** @var CommandController $controller */
        $controller = new $full_class_name();
        $this->controllers[$command_name] = $controller;
    }

    protected function getNamespace(string $filename): string
    {
        $lines = preg_grep('/^namespace /', file($filename));
        $namespace_line = array_shift($lines);
        $match = [];
        preg_match('/^namespace (.*);$/', $namespace_line, $match);

        return array_pop($match);
    }
}
