<?php

namespace Micro\Framework\Kernel\Configuration\Resolver;

interface PluginConfigurationClassResolverInterface
{
    /**
     * @param  string $pluginClass
     * @return string
     */
    public function resolve(string $pluginClass): string;
}
