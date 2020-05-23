<?php

declare(strict_types=1);

namespace Minicli\Output;

use Minicli\OutputInterface;

class BasicPrinter implements OutputInterface
{
    public function out(string $message): void
    {
        echo $message;
    }

    public function newline(): void
    {
        $this->out("\n");
    }

    public function display(string $message): void
    {
        $this->newline();
        $this->out($message);
        $this->newline();
        $this->newline();
    }
}
