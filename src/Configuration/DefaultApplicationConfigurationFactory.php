<?php

namespace Micro\Framework\Kernel\Configuration;

class DefaultApplicationConfigurationFactory implements ApplicationConfigurationFactoryInterface
{
    /**
     * @param array<string, mixed> $configuration
     */
    public function __construct(private array $configuration)
    {
    }

    /**
     * @return ApplicationConfigurationInterface
     */
    public function create(): ApplicationConfigurationInterface
    {
        return new DefaultApplicationConfiguration($this->configuration);
    }
}
