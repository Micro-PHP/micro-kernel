<?php

namespace Micro\Framework\Kernel\Configuration;

use JetBrains\PhpStorm\Pure;

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
    #[Pure] public function create(): ApplicationConfigurationInterface
    {
        return new DefaultApplicationConfiguration($this->configuration);
    }
}
