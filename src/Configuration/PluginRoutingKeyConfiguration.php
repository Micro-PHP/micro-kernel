<?php

namespace Micro\Framework\Kernel\Configuration;

class PluginRoutingKeyConfiguration extends PluginConfiguration
{
    /**
     * @param ApplicationConfigurationInterface $configuration
     * @param string $configRoutingKey
     */
    public function __construct(
        ApplicationConfigurationInterface $configuration,
        protected readonly string $configRoutingKey
    )
    {
        parent::__construct($configuration);
    }

    /**
     * @param  string $key
     * @return string
     */
    protected function cfg(string $key): string
    {
        return sprintf($key, mb_strtoupper($this->configRoutingKey));
    }

    /**
     * @param  string $key
     * @param  mixed $default
     * @param bool $nullable
     * @return mixed
     */
    protected function get(string $key, mixed $default = null, bool $nullable = true): mixed
    {
        return $this->configuration->get(
            $this->cfg($key),
            $default,
            $nullable
        );
    }
}
