<?php

namespace Micro\Framework\Kernel\Configuration;

class DefaultApplicationConfiguration implements ApplicationConfigurationInterface
{
    /**
     * @param array<string, mixed> $configuration
     */
    public function __construct(private array $configuration)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function get(string $key, $default = null): mixed
    {
        return $this->configuration[$key] ?? $default;
    }
}
