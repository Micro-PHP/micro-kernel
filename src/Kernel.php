<?php

namespace Micro\Framework\Kernel;



use Micro\Component\DependencyInjection\Container;
use Micro\Framework\Kernel\Configuration\ApplicationConfigurationInterface;
use Micro\Framework\Kernel\Configuration\PluginConfiguration;
use Micro\Framework\Kernel\Configuration\PluginConfigurationClassResolver;
use Micro\Framework\Kernel\Plugin\ApplicationPluginInterface;

class Kernel
{
    /**
     * @param string[] $applicationPluginCollection
     */
    public function __construct(
    private array $applicationPluginCollection,
    private ApplicationConfigurationInterface $configuration,
    private ?Container $container = null
    ) {
    }

    /**
     * @return void
     */
    public function run(): void
    {
        $this->bootPlugins();
    }

    /**
     * @return void
     */
    public function terminate(): void
    {
    }

    /**
     * @return void
     */
    private function bootPlugins(): void
    {
        foreach ($this->applicationPluginCollection as $applicationPlugin) {
            $this->bootPlugin($applicationPlugin);
        }
    }

    /**
     * @template    T of \Micro\Framework\Kernel\Plugin\ApplicationPluginInterface
     * @psalm-param class-string<T>
     *
     * @param  string $applicationPluginClass
     * @return void
     */
    protected function bootPlugin(string $applicationPluginClass): void
    {
        $pluginConfiguration = $this->resolvePluginConfiguration($applicationPluginClass);
        /*** @var ApplicationPluginInterface $plugin */
        $plugin = new $applicationPluginClass($pluginConfiguration);

        $plugin->provideDependencies($this->getContainer());
    }

    /**
     * @template    T of \Micro\Framework\Kernel\Plugin\ApplicationPluginInterface
     * @psalm-param class-string<T>
     *
     * @param  string $applicationPluginClass
     * @return void
     */
    private function resolvePluginConfiguration(string $applicationPluginClass): PluginConfiguration
    {
        $resolver = new PluginConfigurationClassResolver($applicationPluginClass, $this->configuration);

        return $resolver->resolve();
    }

    /**
     * @return Container
     */
    private function getContainer(): Container
    {
        if(!$this->container) {
            $this->container = new Container();
        }

        return $this->container;
    }
}
