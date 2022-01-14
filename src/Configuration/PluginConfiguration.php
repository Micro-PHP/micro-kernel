<?php

namespace Micro\Framework\Kernel\Configuration;

class PluginConfiguration
{
    /**
     * @param ApplicationConfigurationInterface $configuration
     */
    public function __construct(protected ApplicationConfigurationInterface $configuration)
    {
    }
}
