<?php

namespace Micro\Framework\Kernel;

use Micro\Component\DependencyInjection\Container;
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
     * @var object[]
     */
    private array $plugins;

    /**
     * @var string[]
     */
    private array $pluginsLoaded;

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

        $this->pluginsLoaded = [];
        $this->plugins = [];
    }

    /**
     * {@inheritDoc}
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
     * {@inheritDoc}
     */
    public function terminate(): void
    {
        if(!$this->isStarted || $this->isTerminated) {
            return;
        }

        $this->isTerminated = true;
    }

    /**
     * {@inheritDoc}
     */
    public function container(): Container
    {
        return $this->container;
    }

    /**
     * {@inheritDoc}
     */
    public function loadPlugin(string $applicationPluginClass): void
    {
        if (in_array($applicationPluginClass, $this->pluginsLoaded, true)) {
            return;
        }

        $plugin = new $applicationPluginClass();

        foreach ($this->pluginBootLoaderCollection as $bootLoader) {
            $bootLoader->boot($plugin);
        }

        $this->plugins[] = $plugin;
        $this->pluginsLoaded[] = $applicationPluginClass;
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
