<?php

namespace Micro\Framework\Kernel\Configuration\Resolver;

class PluginNameResolver implements PluginConfigurationClassResolverInterface
{
    /**
     * {@inheritDoc}
     */
    public function resolve(string $pluginClass): string
    {
        return $pluginClass . 'Configuration';
    }
}
