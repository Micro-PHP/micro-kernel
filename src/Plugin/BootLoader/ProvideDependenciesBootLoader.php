<?php

namespace Micro\Framework\Kernel\Plugin\BootLoader;

use Micro\Component\DependencyInjection\Container;
use Micro\Framework\Kernel\Plugin\ApplicationPluginInterface;
use Micro\Framework\Kernel\Plugin\PluginBootLoaderInterface;

class ProvideDependenciesBootLoader implements PluginBootLoaderInterface
{
    public function __construct(private Container $container)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function boot(ApplicationPluginInterface $applicationPlugin): void
    {
        $applicationPlugin->provideDependencies($this->container);
    }
}
