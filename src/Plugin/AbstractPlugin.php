<?php

namespace Micro\Framework\Kernel\Plugin;

use Micro\Component\DependencyInjection\Container;
use Micro\Framework\Kernel\Configuration\PluginConfiguration;
use Micro\Framework\Kernel\Configuration\PluginConfigurationInterface;

abstract class AbstractPlugin implements ApplicationPluginInterface
{
    public function __construct(protected PluginConfiguration $configuration)
    {
    }

    public function provideDependencies(Container $container): void
    {
    }

    /**
     * @return PluginConfigurationInterface
     */
    protected function configuration(): PluginConfigurationInterface
    {
        return $this->configuration;
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return get_class($this);
    }
}
