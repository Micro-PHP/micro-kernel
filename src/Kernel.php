<?php

namespace Micro\Framework\Kernel;

use Micro\Component\DependencyInjection\Container;
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
     * @param Container|null                    $container
     * @param PluginBootLoaderInterface[]       $pluginBootLoaderCollection
     */
    public function __construct(
        private readonly array $applicationPluginCollection,
        private readonly iterable $pluginBootLoaderCollection,
        private readonly ?Container $container = null
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
     * @param  string $applicationPluginClass
     * @return void
     */
    protected function loadPlugin(string $applicationPluginClass): void
    {
        $plugin = new $applicationPluginClass();

        foreach ($this->pluginBootLoaderCollection as $bootLoader) {
            $bootLoader->boot($plugin);
        }

        $this->plugins[] = $plugin;
    }

    /**
     * {@inheritDoc}
     */
    public function plugins(string $interfaceInherited = null): iterable
    {
        foreach ($this->plugins as $plugin) {
            if(!$interfaceInherited || ($plugin instanceof $interfaceInherited)) {
                yield $plugin;
            }
        }
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
