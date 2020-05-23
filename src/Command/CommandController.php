<?php

declare(strict_types=1);

namespace Minicli\Command;

use Minicli\App;
use Minicli\ControllerInterface;
use Minicli\OutputInterface;

abstract class CommandController implements ControllerInterface
{
    /** @var  App */
    protected $app;

    /** @var  CommandCall */
    protected $input;

    /**
     * Command Logic.
     * @return void
     */
    abstract public function handle(): void;

    /**
     * Called before `run`.
     */
    public function boot(App $app): void
    {
        $this->app = $app;
    }

    public function run(CommandCall $input): void
    {
        $this->input = $input;
        $this->handle();
    }

    /**
     * Called when `run` is successfully finished.
     */
    public function teardown(): void
    {
        //
    }

    protected function getArgs(): array
    {
        return $this->input->getArgs();
    }

    protected function getParams(): array
    {
        return $this->input->getParams();
    }

    protected function hasParam(string $param): bool
    {
        return $this->input->hasParam($param);
    }

    protected function hasFlag(string $flag): bool
    {
        return $this->input->hasFlag($flag);
    }

    protected function getParam(string $param): ?string
    {
        return $this->input->getParam($param);
    }

    protected function getApp(): App
    {
        return $this->app;
    }

    protected function getPrinter(): OutputInterface
    {
        return $this->getApp()->getPrinter();
    }
}
