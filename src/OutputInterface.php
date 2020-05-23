<?php

declare(strict_types=1);

namespace Minicli;

interface OutputInterface
{
    /**
     * Prints a message (no linebreak).
     *
     * @return mixed
     */
    public function out(string $message): void;

    /**
     * Prints a line break.
     */
    public function newline(): void;

    /**
     * Displays a message wrapped in new lines.
     */
    public function display(string $message): void;
}
