<?php

declare(strict_types=1);

namespace Minicli;

use function array_key_exists;

class ServiceContainer
{
    /**
     * @var array
     */
    private $services = [];

    /**
     * @var array
     */
    private $loaded_services = [];

    /**
     * @var App|null
     */
    private $app;

    public function __construct(iterable $services = [])
    {
        foreach ($services as $name => $service) {
            $this->add($name, $service);
        }
    }

    public function setApp(App $app): void
    {
        $this->app = $app;
    }

    public function add(string $name, ServiceInterface $service): void
    {
        $this->services[$name] = $service;
    }

    /**
     * Magic method implements lazy loading for services.
     */
    public function get(string $name): ?ServiceInterface
    {
        if (!$this->has($name)) {
            return null;
        }

        if (!array_key_exists($name, $this->loaded_services)) {
            /** @var ServiceInterface $service */
            $service = $this->services[$name];
            if (null !== $this->app) {
                $service->load($this->app);
            }

            $this->loaded_services[$name] = $service;
        }

        return $this->services[$name];
    }

    public function has($name): bool
    {
        return array_key_exists($name, $this->services);
    }
}
