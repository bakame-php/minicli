<?php

declare(strict_types=1);

namespace Minicli\Output;

use Minicli\App;
use Minicli\OutputInterface;
use Minicli\ServiceInterface;

final class OutputService implements ServiceInterface
{
    /**
     * @var OutputInterface
     */
    private $output;

    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }

    public function load(App $app)
    {
    }

    public function output(): OutputInterface
    {
        return $this->output;
    }
}
