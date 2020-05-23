<?php

namespace Assets\Command\Test;

use Minicli\App;
use Minicli\Command\CommandController;

class DefaultController extends CommandController
{

    public function handle(): void
    {
        $this->getPrinter()->out('test default');
    }
}
