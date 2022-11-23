<?php

namespace Micro\Framework\Kernel\Configuration\Resolver;

use Micro\Framework\Kernel\Configuration\ApplicationConfigurationInterface;
use Micro\Framework\Kernel\Configuration\PluginConfiguration;
use Micro\Framework\Kernel\Configuration\PluginConfigurationInterface;

class PluginConfigurationClassResolver
{
    /**
     * @param string                            $pluginClass
     * @param ApplicationConfigurationInterface $applicationConfiguration
     */
    public function __construct(
    private string $pluginClass,
    private ApplicationConfigurationInterface $applicationConfiguration
    )
    {
    }

    /**
     * @return PluginConfigurationInterface
     */
    public function resolve(): PluginConfigurationInterface
    {
        $configClassDefault = PluginConfiguration::class;
        $configClasses      = [];

        foreach ($this->getPluginClassResolvers() as $resolver) {
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
        /** @var class-string<PluginConfigurationInterface> $configClass */
        $configClass = $configClasses[0] ?? $configClassDefault;

        return new $configClass($this->applicationConfiguration);
    }

    /**
     * @return PluginConfigurationClassResolverInterface[]
     */
    protected function getPluginClassResolvers(): array
    {
        return [
            new PluginNameResolver(),
            new PluginNameShortResolver(),
        ];
    }
}
