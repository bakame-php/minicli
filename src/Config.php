<?php

declare(strict_types=1);

namespace Minicli;

class Config implements ServiceInterface
{
    /** @var array */
    protected $config;

    /**
     * Config constructor.
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    /**
     * @param string $name
     * @return string|null
     */
    public function __get(string $name)
    {
        return $this->config[$name] ?? null;
    }

    /**
     * @param string $name
     * @param string $value
     */
    public function __set($name, $value)
    {
        $this->config[$name] = $value;
    }

    public function has(string $name): bool
    {
        return isset($this->config[$name]);
    }

    public function load(App $app)
    {
        return null;
    }
}
