<?php

namespace Micro\Framework\Kernel;

use Micro\Component\DependencyInjection\Container;
use Micro\Framework\Kernel\Configuration\ApplicationConfigurationInterface;
use Micro\Framework\Kernel\Configuration\PluginConfiguration;
use Micro\Framework\Kernel\Configuration\Resolver\PluginConfigurationClassResolver;
use Micro\Framework\Kernel\Plugin\ApplicationPluginInterface;
use Micro\Framework\Kernel\Plugin\PluginBootLoaderInterface;

class Kernel implements KernelInterface
{
    /**
     * @var bool
     */
    private bool $isStarted;

    /**
     * @var bool
     */
    private bool $isTerminated;

    /**
     * @var ApplicationPluginInterface[]
     */
    private array $plugins;

    /**
     * @param array                             $applicationPluginCollection
     * @param ApplicationConfigurationInterface $configuration
     * @param Container|null                    $container
     * @param PluginBootLoaderInterface[]       $pluginBootLoaderCollection
     */
    public function __construct(
    private array $applicationPluginCollection,
    private ApplicationConfigurationInterface $configuration,
    private array $pluginBootLoaderCollection,
    private ?Container $container = null
    )
    {
        $this->isStarted    = false;
        $this->isTerminated = false;

        $this->plugins = [];
    }

    /**
     * @return void
     */
    public function run(): void
    {
        if($this->isStarted) {
            return;
        }

        $this->loadPlugins();
        $this->isStarted = true;
    }

    /**
     * @return void
     */
    public function terminate(): void
    {
        if(!$this->isStarted || $this->isTerminated) {
            return;
        }

        $this->isTerminated = true;
    }

    /**
     * @return Container
     */
    public function container(): Container
    {
        return $this->container;
    }

    /**
     * @template    T of \Micro\Framework\Kernel\Plugin\ApplicationPluginInterface
     * @psalm-param class-string<T>
     *
     * @param  string $applicationPluginClass
     * @return void
     */
    protected function loadPlugin(string $applicationPluginClass): void
    {
        $pluginConfiguration = $this->resolvePluginConfiguration($applicationPluginClass);
        /***
 * @var ApplicationPluginInterface $plugin 
*/
        $plugin = new $applicationPluginClass($pluginConfiguration);

        foreach ($this->pluginBootLoaderCollection as $bootLoader) {
            $bootLoader->boot($plugin);
        }

        $this->plugins[] = $plugin;
    }

    /**
     * {@inheritDoc}
     */
    public function plugins(): array
    {
        return $this->plugins;
    }

    /**
     * @param  string $applicationPluginClass
     * @return PluginConfigurationClassResolver
     */
    protected function createPluginConfigurationResolver(string $applicationPluginClass): PluginConfigurationClassResolver
    {
        return new PluginConfigurationClassResolver($applicationPluginClass, $this->configuration);
    }

    /**
     * @template    T of \Micro\Framework\Kernel\Plugin\ApplicationPluginInterface
     * @psalm-param class-string<T>
     *
     * @param  string $applicationPluginClass
     * @return void
     */
    protected function resolvePluginConfiguration(string $applicationPluginClass): PluginConfiguration
    {
        return $this->createPluginConfigurationResolver($applicationPluginClass)->resolve();
    }

    /**
     * @return void
     */
    protected function loadPlugins(): void
    {
        foreach ($this->applicationPluginCollection as $applicationPlugin) {
            $this->loadPlugin($applicationPlugin);
        }
    }
}
