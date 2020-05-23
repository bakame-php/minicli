<?php

declare(strict_types=1);

namespace Minicli;

class Input
{
    /** @var array  */
    protected $input_history = [];

    /** @var string */
    protected $prompt;

    public function __construct(string $prompt = 'minicli$> ')
    {
        $this->setPrompt($prompt);
    }

    public function read(): string
    {
        $input = readline($this->getPrompt());
        $this->input_history[] = $input;

        return $input;
    }

    /**
     * @return array<string>
     */
    public function getInputHistory(): array
    {
        return $this->input_history;
    }

    public function getPrompt(): string
    {
        return $this->prompt;
    }

    public function setPrompt(string $prompt): void
    {
        $this->prompt = $prompt;
    }
}
