<?php

namespace Micro\Framework\Kernel\Configuration;

use Micro\Framework\Kernel\Configuration\Resolver\PluginConfigurationClassResolverInterface;
use Micro\Framework\Kernel\Configuration\Resolver\PluginNameResolver;
use Micro\Framework\Kernel\Configuration\Resolver\PluginNameShortResolver;

class PluginConfigurationClassResolver
{
    /**
     * @var PluginConfigurationClassResolverInterface[]
     */
    private array $resolvers;

    /**
     * @param string $pluginClass
     * @param ApplicationConfigurationInterface $applicationConfiguration
     */
    public function __construct(
        private string $pluginClass,
        private ApplicationConfigurationInterface $applicationConfiguration
    ) {
        $this->resolvers[] = new PluginNameResolver();
        $this->resolvers[] = new PluginNameShortResolver();
    }

    /**
     * @return PluginConfiguration
     */
    public function resolve(): PluginConfiguration
    {
        $configClassDefault = PluginConfiguration::class;
        $configClasses = [];

        foreach ($this->resolvers as $resolver) {
            $configClass = $resolver->resolve($this->pluginClass);

            if(!class_exists($configClass)) {
                continue;
            }

            $configClasses[] = $configClass;
        }

        if(count($configClasses) > 1) {
            throw new \RuntimeException(
                sprintf(
                    'Too many configuration classes for Application plugin "%s". [%s]',
                    $this->pluginClass,
                    implode(", ", $configClasses)
                )
            );
        }

        $configClass = $configClasses[0] ?? $configClassDefault;

        return new $configClass($this->applicationConfiguration);
    }
}
