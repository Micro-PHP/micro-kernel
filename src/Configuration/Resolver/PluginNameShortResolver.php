<?php

namespace Micro\Framework\Kernel\Configuration\Resolver;

class PluginNameShortResolver implements PluginConfigurationClassResolverInterface
{
    /**
     * {@inheritDoc}
     */
    public function resolve(string $pluginClass): string
    {
        return $pluginClass . 'Config';
    }
}
