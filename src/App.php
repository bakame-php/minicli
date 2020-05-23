<?php

declare(strict_types=1);

namespace Minicli;

use Minicli\Command\CommandCall;
use Minicli\Command\CommandRegistry;
use Minicli\Output\CliPrinter;
use Minicli\Output\OutputService;
use function method_exists;

class App
{
    /** @var  string  */
    protected $app_signature;

    /** @var  ServiceContainer */
    protected $container;

    public function __construct(ServiceContainer $container)
    {
        $this->container = $container;
        if (method_exists($container, 'setApp')) {
            $this->container->setApp($this);
        }

        $this->setSignature('./minicli help');
    }

    /**
     * @param array<string,string> $settings
     */
    public static function createFromConfig(array $settings = []): self
    {
        $settings = $settings + [
            'app_path' => __DIR__ . '/../app/Command',
            'theme' => 'regular',
        ];

        $app = new self(new ServiceContainer());
        $app->addService('config', new Config($settings));
        $app->addService('command_registry', new CommandRegistry($settings['app_path']));
        $app->addService('printer', new OutputService(new CliPrinter()));

        return $app;
    }

    /**
     * Magic method implements lazy loading for services.
     */
    public function __get(string $name): ?ServiceInterface
    {
        return $this->container->get($name);
    }

    public function addService(string $name, ServiceInterface $service): void
    {
        $this->container->add($name, $service);
    }

    public function getPrinter(): OutputInterface
    {
        /** @var OutputService $printer */
        $printer = $this->container->get('printer');

        return $printer->output();
    }

    public function getSignature(): string
    {
        return $this->app_signature;
    }

    public function printSignature(): void
    {
        $this->getPrinter()->display($this->getSignature());
    }

    public function setSignature(string $app_signature): void
    {
        $this->app_signature = $app_signature;
    }

    public function registerCommand(string $name, callable $callable): void
    {
        /** @var CommandRegistry $registry */
        $registry = $this->container->get('command_registry');

        $registry->registerCommand($name, $callable);
    }

    /**
     * @param array<string,string|array<string>> $argv
     */
    public function runCommand(array $argv = []): void
    {
        $input = new CommandCall($argv);

        if (count($input->getArgs()) < 2) {
            $this->printSignature();
            return;
        }

        /** @var CommandRegistry $registry */
        $registry = $this->container->get('command_registry');
        $controller = $registry->getCallableController($input->getCommand(), $input->getSubCommand());

        if ($controller instanceof ControllerInterface) {
            $controller->boot($this);
            $controller->run($input);
            $controller->teardown();
            return;
        }

        $this->runSingle($input);
    }

    protected function runSingle(CommandCall $input): void
    {
        try {
            /** @var CommandRegistry $registry */
            $registry = $this->container->get('command_registry');
            $callable = $registry->getCallable($input->getCommand());
            ($callable)($input);
        } catch (\Exception $e) {
            $this->getPrinter()->display("ERROR: " . $e->getMessage());
        }
    }
}
